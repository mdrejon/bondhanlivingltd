<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Hotel;
use App\Models\User;
use App\Support\DocumentUploader;
use App\Support\Geography;
use App\Support\HotelDefaultRoleProvisioner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class HotelController extends Controller
{
    /** Single-file document categories — re-uploading replaces the previous one. */
    private const SINGLE_DOC_FIELDS = [
        'trade_license_doc' => 'trade_license',
        'bin_doc'           => 'bin_certificate',
        'tin_doc'           => 'tin_certificate',
        'owner_nid_doc'     => 'owner_nid_copy',
    ];

    public function index(): Response
    {
        $hotels = Hotel::with(['district', 'upazila', 'policeStation'])
            ->withCount(['roomTypes', 'rooms', 'customers', 'users'])
            ->orderByDesc('id')
            ->get();

        return Inertia::render('Admin/Hotels/Index', [
            'hotels' => $hotels,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Hotels/Create', Geography::selectOptions());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['status'] = 'active'; // Super Admin creates hotels directly — no approval queue yet

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('hotels/logos', 'public');
        }

        $hotel = Hotel::create($data);

        DocumentUploader::syncSingle($request, $hotel, self::SINGLE_DOC_FIELDS, 'hotels/documents');
        DocumentUploader::addMultiple($request, $hotel, 'photos', 'hotel_photo', 'hotels/photos');
        HotelDefaultRoleProvisioner::provision($hotel);

        return redirect()->route('admin.hotels.show', $hotel)
            ->with('success', 'Hotel created successfully.');
    }

    public function show(Hotel $hotel): Response
    {
        $hotel->load(['district', 'upazila', 'policeStation', 'documents.uploadedBy']);
        $hotel->loadCount(['roomTypes', 'rooms', 'customers', 'bookings']);

        return Inertia::render('Admin/Hotels/Show', [
            'hotel' => $hotel,
            'staff' => User::where('hotel_id', $hotel->id)->with('role')->orderBy('name')->get(),
        ]);
    }

    public function edit(Hotel $hotel): Response
    {
        $hotel->load('documents');

        return Inertia::render('Admin/Hotels/Edit', array_merge(
            Geography::selectOptions(),
            ['hotel' => $hotel]
        ));
    }

    public function update(Request $request, Hotel $hotel): RedirectResponse
    {
        $data = $this->validated($request, $hotel->id);

        if ($request->hasFile('logo')) {
            if ($hotel->logo) {
                Storage::disk('public')->delete($hotel->logo);
            }
            $data['logo'] = $request->file('logo')->store('hotels/logos', 'public');
        }

        $hotel->update($data);

        DocumentUploader::syncSingle($request, $hotel, self::SINGLE_DOC_FIELDS, 'hotels/documents');
        DocumentUploader::addMultiple($request, $hotel, 'photos', 'hotel_photo', 'hotels/photos');

        return redirect()->route('admin.hotels.show', $hotel)
            ->with('success', 'Hotel updated successfully.');
    }

    public function updateStatus(Request $request, Hotel $hotel): RedirectResponse
    {
        $data = $request->validate([
            'status' => 'required|in:pending,active,suspended,rejected',
        ]);

        $hotel->update($data);

        return back()->with('success', "Hotel status changed to \"{$data['status']}\".");
    }

    public function deleteDocument(Document $document): RedirectResponse
    {
        if ($document->documentable_type !== Hotel::class) {
            abort(404);
        }

        DocumentUploader::delete($document);

        return back()->with('success', 'Document removed.');
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (Hotel::where('slug', $slug)->exists()) {
            $slug = "{$base}-" . ++$i;
        }
        return $slug;
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $uniqueIgnore = $ignoreId ? ",{$ignoreId}" : '';

        return $request->validate([
            'name'              => 'required|string|max:255',
            'template'          => 'nullable|in:default',
            'category'          => 'nullable|string|max:100',
            'total_rooms'       => 'nullable|integer|min:0',
            'trade_license_no'  => "nullable|string|max:100|unique:hotels,trade_license_no{$uniqueIgnore}",
            'bin_no'            => "nullable|string|max:100|unique:hotels,bin_no{$uniqueIgnore}",
            'tin_no'            => "nullable|string|max:100|unique:hotels,tin_no{$uniqueIgnore}",
            'owner_name'        => 'nullable|string|max:255',
            'owner_nid'         => 'nullable|string|max:50',
            'mobile'            => 'required|string|max:30',
            'email'             => 'nullable|email|max:150',
            'address'           => 'required|string',
            'district_id'       => 'required|exists:districts,id',
            'upazila_id'        => 'nullable|exists:upazilas,id',
            'police_station_id' => 'required|exists:police_stations,id',
            'logo'              => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'trade_license_doc' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120',
            'bin_doc'           => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120',
            'tin_doc'           => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120',
            'owner_nid_doc'     => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120',
            'photos'            => 'nullable|array',
            'photos.*'          => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);
    }
}
