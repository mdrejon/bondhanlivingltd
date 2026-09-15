<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InquiryController extends Controller
{
    public function index(): Response
    {
        $inquiries = Inquiry::orderByDesc('id')->get();

        $stats = [
            'total'   => $inquiries->count(),
            'new'     => $inquiries->where('status', 'new')->count(),
            'read'    => $inquiries->where('status', 'read')->count(),
            'replied' => $inquiries->where('status', 'replied')->count(),
        ];

        return Inertia::render('Admin/Inquiries/Index', [
            'inquiries' => $inquiries,
            'stats'     => $stats,
        ]);
    }

    public function show(Inquiry $inquiry): Response
    {
        // Auto-mark as read when opened
        if ($inquiry->status === Inquiry::STATUS_NEW) {
            $inquiry->update(['status' => Inquiry::STATUS_READ]);
        }

        return Inertia::render('Admin/Inquiries/Show', [
            'inquiry' => $inquiry,
        ]);
    }

    public function updateStatus(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $data = $request->validate([
            'status' => 'required|in:new,read,replied',
            'notes'  => 'nullable|string',
        ]);

        $update = ['status' => $data['status']];
        if (isset($data['notes'])) {
            $update['notes'] = $data['notes'];
        }

        $inquiry->update($update);

        return back()->with('success', 'Inquiry status updated.');
    }

    public function destroy(Inquiry $inquiry): RedirectResponse
    {
        $inquiry->delete();

        return redirect()->route('admin.inquiries.index')
            ->with('success', 'Inquiry deleted.');
    }
}
