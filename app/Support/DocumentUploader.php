<?php

namespace App\Support;

use App\Models\Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Shared upload handling for the `documents` polymorphic table (App\Models\Document),
 * used by both Admin\HotelController (Phase 4) and Admin\CustomerController (Phase 5).
 * Two shapes: single-file categories where a re-upload replaces the previous file, and
 * multi-file categories that just accumulate.
 *
 * Stored on the `local` disk (storage_path('app'), not web-accessible), not `public` —
 * a Phase 7 security-pass fix. NID/passport/visa/marriage-certificate/guest-photo and
 * hotel legal-registration scans are exactly the sensitive-PII documents PRD § 6 warns
 * about; the `public` disk this project otherwise uses for marketing images
 * (config/filesystems.php points it at public_path('storage'), served directly by the
 * webserver with zero auth) made every one of them reachable by anyone with the exact
 * URL, no login required. Random hashed filenames made them hard to *guess*, but
 * "hard to guess" isn't "not publicly reachable without auth," which is what PRD § 6 and
 * the Phase 7 checklist actually require. Served back out only through
 * Admin\DocumentController::show, which re-checks scope/permission on every request —
 * see that controller for how.
 */
class DocumentUploader
{
    public const DISK = 'local';

    /**
     * @param Model $model The documentable (Hotel or Customer).
     * @param array<string, string> $fields Request field name => document category.
     * @param string $storagePath e.g. 'hotels/documents' or 'customers/documents'.
     */
    public static function syncSingle(Request $request, Model $model, array $fields, string $storagePath): void
    {
        foreach ($fields as $field => $category) {
            if (!$request->hasFile($field)) {
                continue;
            }

            $existing = $model->documents()->where('category', $category)->first();
            if ($existing) {
                Storage::disk(self::DISK)->delete($existing->file_path);
                $existing->delete();
            }

            $file = $request->file($field);
            Document::create([
                'documentable_type' => get_class($model),
                'documentable_id'   => $model->id,
                'category'          => $category,
                'file_path'         => $file->store($storagePath, self::DISK),
                'original_filename' => $file->getClientOriginalName(),
                'uploaded_by'       => auth()->id(),
            ]);
        }
    }

    /** @param string $field Request field name holding an array of files. */
    public static function addMultiple(Request $request, Model $model, string $field, string $category, string $storagePath): void
    {
        if (!$request->hasFile($field)) {
            return;
        }

        foreach ($request->file($field) as $file) {
            Document::create([
                'documentable_type' => get_class($model),
                'documentable_id'   => $model->id,
                'category'          => $category,
                'file_path'         => $file->store($storagePath, self::DISK),
                'original_filename' => $file->getClientOriginalName(),
                'uploaded_by'       => auth()->id(),
            ]);
        }
    }

    /** Deletes the file from disk and the Document row. Caller is responsible for
     *  authorizing that the document belongs to the expected documentable type. */
    public static function delete(Document $document): void
    {
        Storage::disk(self::DISK)->delete($document->file_path);
        $document->delete();
    }
}
