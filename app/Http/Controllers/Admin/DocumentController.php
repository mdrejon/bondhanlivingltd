<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Document;
use App\Models\Hotel;
use App\Support\AuditLogger;
use App\Support\DocumentUploader;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Serves files from the `documents` table's protected `local` disk (see
 * App\Support\DocumentUploader's docblock for why they aren't on the public disk).
 * Every request re-checks both module permission and tenant/jurisdiction scope —
 * this route, not the disk, is what actually protects NID/passport/guest-photo files.
 */
class DocumentController extends Controller
{
    public function show(Document $document): StreamedResponse
    {
        $this->authorizeAccess($document);

        if (!Storage::disk(DocumentUploader::DISK)->exists($document->file_path)) {
            abort(404);
        }

        if ($document->documentable_type === Customer::class) {
            AuditLogger::log('viewed_guest_document', $document->documentable, ['category' => $document->category]);
        }

        return Storage::disk(DocumentUploader::DISK)->response(
            $document->file_path,
            $document->original_filename
        );
    }

    private function authorizeAccess(Document $document): void
    {
        if ($document->documentable_type === Hotel::class) {
            // Hotels aren't tenant-scoped (they're the tenant boundary itself) — the
            // hotel-registration module permission is the only gate, same as HotelController.
            if (!auth()->user()->hasPermission('hotel-registration', 'view')) {
                abort(403);
            }
            return;
        }

        if ($document->documentable_type === Customer::class) {
            if (!auth()->user()->hasPermission('customers', 'view')) {
                abort(403);
            }

            // Customer carries the tenant/jurisdiction global scope, so this is null for
            // a guest outside the current user's hotel/jurisdiction — reuses the exact
            // same protection every other guest-data query in this app relies on.
            if (!Customer::find($document->documentable_id)) {
                abort(404);
            }
            return;
        }

        abort(404);
    }
}
