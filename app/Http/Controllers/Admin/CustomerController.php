<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Document;
use App\Support\AuditLogger;
use App\Support\DocumentUploader;
use App\Support\Geography;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    /** Single-file KYC document categories — re-uploading replaces the previous one. */
    private const SINGLE_DOC_FIELDS = [
        'nid_front_doc'     => 'nid_front',
        'nid_back_doc'      => 'nid_back',
        'passport_doc'      => 'passport_scan',
        'visa_doc'          => 'visa',
        'marriage_cert_doc' => 'marriage_certificate',
        'guest_photo_doc'   => 'guest_photo',
    ];

    public function index(): Response
    {
        $customers = Customer::withCount('bookings')
            ->with([
                'district:id,name',
                'upazila:id,name',
                'documents',
                'bookings' => function ($q) {
                    $q->latest()->limit(1)->select('id', 'customer_id', 'booking_reference', 'check_in_date', 'booking_status');
                }
            ])
            ->orderByDesc('id')
            ->get();

        if (!auth()->user()->canManagePoliceFlag()) {
            $customers->each->makeHidden(['is_flagged', 'flagged_note']);
        }

        return Inertia::render('Admin/Customers/Index', [
            'customers'      => $customers,
            'canManageFlag'  => auth()->user()->canManagePoliceFlag(),
        ]);
    }

    public function show(Customer $customer): Response
    {
        $customer->load([
            'district', 'upazila', 'policeStation', 'documents.uploadedBy',
            'bookings' => function ($q) {
                $q->with(['rooms.room:id,room_number', 'rooms.roomType:id,name'])
                  ->orderByDesc('id');
            },
        ]);

        $canManageFlag = auth()->user()->canManagePoliceFlag();
        if (!$canManageFlag) {
            $customer->makeHidden(['is_flagged', 'flagged_note']);
        }

        AuditLogger::log('viewed_guest', $customer);

        return Inertia::render('Admin/Customers/Show', [
            'customer'      => $customer,
            'canManageFlag' => $canManageFlag,
        ]);
    }

    public function edit(Customer $customer): Response
    {
        $customer->load('documents');

        $canManageFlag = auth()->user()->canManagePoliceFlag();
        if (!$canManageFlag) {
            $customer->makeHidden(['is_flagged', 'flagged_note']);
        }

        AuditLogger::log('viewed_guest', $customer);

        return Inertia::render('Admin/Customers/Edit', array_merge(
            Geography::selectOptions(),
            ['customer' => $customer, 'canManageFlag' => $canManageFlag]
        ));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $data = $this->validated($request);

        if (auth()->user()->canManagePoliceFlag()) {
            $data['is_flagged']   = $request->boolean('is_flagged');
            $data['flagged_note'] = $request->input('flagged_note');
        }

        $customer->update($data);

        DocumentUploader::syncSingle($request, $customer, self::SINGLE_DOC_FIELDS, 'customers/documents');
        DocumentUploader::addMultiple($request, $customer, 'other_documents', 'other', 'customers/documents');

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', 'Guest record updated.');
    }

    public function deleteDocument(Document $document): RedirectResponse
    {
        if ($document->documentable_type !== Customer::class) {
            abort(404);
        }

        DocumentUploader::delete($document);

        return back()->with('success', 'Document removed.');
    }

    public function history(Request $request): Response
    {
        $search   = trim($request->get('search', ''));
        $customer = null;
        $found    = false;

        if ($search !== '') {
            $customer = Customer::where('phone', $search)
                ->orWhere('email', $search)
                ->orWhere('name', 'like', '%' . $search . '%')
                ->first();

            if ($customer) {
                $customer->load(['bookings' => function ($q) {
                    $q->with(['rooms.room:id,room_number', 'rooms.roomType:id,name'])
                      ->orderByDesc('id');
                }]);
            }

            $found = true;
        }

        return Inertia::render('Admin/Customers/History', [
            'customer' => $customer,
            'search'   => $search,
            'searched' => $found,
        ]);
    }

    public function lookup(Request $request): JsonResponse
    {
        $phone    = $request->input('phone', '');
        $customer = Customer::where('phone', $phone)->first();

        return response()->json($customer);
    }

    public function suggest(Request $request): JsonResponse
    {
        $q = trim($request->input('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $customers = Customer::where('name', 'like', '%' . $q . '%')
            ->orWhere('email', 'like', '%' . $q . '%')
            ->orWhere('phone', 'like', '%' . $q . '%')
            ->orderBy('name')
            ->limit(8)
            ->get(['id', 'name', 'email', 'phone']);

        return response()->json($customers);
    }

    private function validated(Request $request): array
    {
        // Inertia's forceFormData (required here for file uploads) sends JS checkbox
        // booleans through FormData as the literal strings "true"/"false" — which
        // Laravel's `boolean` validation rule actually REJECTS (it only accepts
        // true/false/0/1/'0'/'1', confirmed via tinker; the plain string "false"
        // fails validation outright). Normalize with Request::boolean() first, which
        // already knows how to interpret every one of those shapes correctly.
        $request->merge([
            'is_foreign_guest' => $request->boolean('is_foreign_guest'),
            'is_couple'        => $request->boolean('is_couple'),
        ]);

        foreach (['nid_front_doc', 'nid_back_doc', 'passport_doc', 'visa_doc', 'marriage_cert_doc', 'guest_photo_doc', 'other_documents'] as $fileKey) {
            if (!$request->hasFile($fileKey)) {
                $request->request->remove($fileKey);
            }
        }

        return $request->validate([
            // Personal
            'name'              => 'required|string|max:150',
            'father_name'       => 'nullable|string|max:150',
            'mother_name'       => 'nullable|string|max:150',
            'gender'            => 'nullable|in:male,female,other',
            'date_of_birth'     => 'nullable|date|before:today',
            'nationality'       => 'nullable|string|max:100',
            'occupation'        => 'nullable|string|max:150',
            'phone'             => 'required|string|max:30',
            'email'             => 'nullable|email|max:150',
            'emergency_contact' => 'nullable|string|max:30',
            'present_address'   => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'district_id'       => 'nullable|exists:districts,id',
            'upazila_id'        => 'nullable|exists:upazilas,id',
            'police_station_id' => 'nullable|exists:police_stations,id',
            'post_code'         => 'nullable|string|max:20',

            // Identity
            'document_type'             => 'required|in:nid,passport,other',
            'nid_number'                => 'nullable|string|max:30',
            'birth_certificate_number'  => 'nullable|string|max:30',
            'passport_number'           => 'nullable|string|max:30',
            'driving_license_number'    => 'nullable|string|max:30',

            // Foreign guest — required only when is_foreign_guest is checked. Matched
            // against both "true" and "1": Inertia's forceFormData submissions send JS
            // booleans through FormData as the literal string "true"/"false", but
            // required_if:field,1 alone does NOT match a native PHP boolean true (a
            // real Laravel gotcha, confirmed via tinker) — listing both covers every
            // realistic submission shape.
            'is_foreign_guest' => 'boolean',
            'visa_number'      => 'required_if:is_foreign_guest,true,1|nullable|string|max:50',
            'arrival_date_bd'  => 'required_if:is_foreign_guest,true,1|nullable|date',

            // Marriage / companion — required only when is_couple is checked
            'is_couple'      => 'boolean',
            'spouse_name'    => 'required_if:is_couple,true,1|nullable|string|max:150',
            'marriage_date'  => 'nullable|date',

            // Documents
            'nid_front_doc'       => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120',
            'nid_back_doc'        => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120',
            'passport_doc'        => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120',
            'visa_doc'            => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120',
            'marriage_cert_doc'   => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120',
            'guest_photo_doc'     => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'other_documents'     => 'nullable|array',
            'other_documents.*'   => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120',
        ]);
    }
}
