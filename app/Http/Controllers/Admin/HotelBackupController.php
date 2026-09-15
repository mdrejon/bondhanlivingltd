<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\HotelBackupStaleDataException;
use App\Http\Controllers\Controller;
use App\Models\Backup;
use App\Services\HotelDataBackupService;
use App\Support\CurrentHotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

/**
 * Per-hotel backup/export/import (Phase 8B) — every action scoped to
 * CurrentHotel::homeId(). A hotel admin only ever sees, creates, downloads, or
 * deletes THEIR hotel's backups (backups.hotel_id, never null here), never the
 * platform-wide .sql backups from Admin\BackupController (a separate module,
 * separate table rows, separate storage folder).
 */
class HotelBackupController extends Controller
{
    public function index()
    {
        $hotelId = CurrentHotel::homeId();

        $backups = Backup::where('hotel_id', $hotelId)
            ->with('creator:id,name')
            ->latest()
            ->get()
            ->map(fn (Backup $b) => [
                'id'         => $b->id,
                'filename'   => $b->filename,
                'size'       => $b->size,
                'status'     => $b->status,
                'error'      => $b->error,
                'created_by' => $b->creator?->name ?? '—',
                'created_at' => $b->created_at->format('d M Y, h:i A'),
                'exists'     => $b->status === 'completed' && Storage::disk('local')->exists($b->path),
            ]);

        return Inertia::render('Admin/HotelBackup/Index', [
            'backups' => $backups,
        ]);
    }

    public function store(HotelDataBackupService $service)
    {
        $hotelId  = CurrentHotel::homeId();
        $filename = 'hotel_' . $hotelId . '_' . now()->format('Y-m-d_His') . '.json';
        $relative = "backups/hotel-{$hotelId}/{$filename}";

        Storage::disk('local')->makeDirectory("backups/hotel-{$hotelId}");
        $absolute = Storage::disk('local')->path($relative);

        try {
            $service->dump($hotelId, $absolute);

            Backup::create([
                'hotel_id'   => $hotelId,
                'filename'   => $filename,
                'path'       => $relative,
                'size'       => is_file($absolute) ? (filesize($absolute) ?: 0) : 0,
                'status'     => 'completed',
                'created_by' => auth()->id(),
            ]);

            return back()->with('success', 'Backup created successfully.');
        } catch (\Throwable $e) {
            if (is_file($absolute)) {
                @unlink($absolute);
            }

            Backup::create([
                'hotel_id'   => $hotelId,
                'filename'   => $filename,
                'path'       => $relative,
                'size'       => 0,
                'status'     => 'failed',
                'error'      => Str::limit($e->getMessage(), 900),
                'created_by' => auth()->id(),
            ]);

            report($e);

            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function restoreUpload(Request $request, HotelDataBackupService $service)
    {
        $hotelId = CurrentHotel::homeId();

        $request->validate([
            'file'  => ['required', 'file', 'max:51200'], // 50 MB
            'force' => ['nullable', 'boolean'],
        ]);

        $upload = $request->file('file');

        if (strtolower($upload->getClientOriginalExtension()) !== 'json') {
            return back()->with('error', 'Only .json hotel backup files can be uploaded here.');
        }

        Storage::disk('local')->makeDirectory("backups/hotel-{$hotelId}");
        $filename = 'hotel_' . $hotelId . '_' . now()->format('Y-m-d_His') . '_uploaded.json';
        $relative = "backups/hotel-{$hotelId}/{$filename}";
        Storage::disk('local')->putFileAs("backups/hotel-{$hotelId}", $upload, $filename);

        return $this->attemptRestore($service, $hotelId, $relative, $upload->getClientOriginalName(), $request->boolean('force'), true);
    }

    public function restore(Request $request, Backup $backup, HotelDataBackupService $service)
    {
        $hotelId = CurrentHotel::homeId();
        abort_unless($backup->hotel_id === $hotelId, 404);
        abort_unless($backup->status === 'completed', 404);
        abort_unless(Str::startsWith($backup->path, "backups/hotel-{$hotelId}/"), 404);
        abort_unless(Storage::disk('local')->exists($backup->path), 404);

        return $this->attemptRestore($service, $hotelId, $backup->path, $backup->filename, $request->boolean('force'), false);
    }

    /**
     * $keepUploadAsBackupRow: true for a fresh file upload not yet catalogued as a
     * Backup row — catalogued only on success; cleaned up from disk on failure so a
     * blocked/failed upload doesn't leave an orphaned, untracked file behind. A
     * blocked (stale-data) upload must be re-submitted with force=1 to retry — the
     * browser still has the file selected, so this is just a confirm-and-resubmit,
     * not a multi-step resume flow.
     */
    private function attemptRestore(HotelDataBackupService $service, int $hotelId, string $relativePath, string $sourceName, bool $force, bool $keepUploadAsBackupRow): \Illuminate\Http\RedirectResponse
    {
        // A safety copy of the hotel's current state before overwriting it.
        $safetyFilename = 'hotel_' . $hotelId . '_' . now()->format('Y-m-d_His') . '_pre_restore.json';
        $safetyRelative = "backups/hotel-{$hotelId}/{$safetyFilename}";
        $safetyAbsolute = Storage::disk('local')->path($safetyRelative);

        try {
            $service->dump($hotelId, $safetyAbsolute);

            Backup::create([
                'hotel_id'   => $hotelId,
                'filename'   => $safetyFilename,
                'path'       => $safetyRelative,
                'size'       => is_file($safetyAbsolute) ? (filesize($safetyAbsolute) ?: 0) : 0,
                'status'     => 'completed',
                'created_by' => auth()->id(),
            ]);
        } catch (\Throwable $e) {
            if (is_file($safetyAbsolute)) {
                @unlink($safetyAbsolute);
            }
            if ($keepUploadAsBackupRow) {
                Storage::disk('local')->delete($relativePath);
            }
            report($e);

            return back()->with('error', 'Restore aborted: could not create the pre-restore safety backup. ' . $e->getMessage());
        }

        try {
            $service->restore($hotelId, Storage::disk('local')->path($relativePath), $force);

            if ($keepUploadAsBackupRow) {
                Backup::create([
                    'hotel_id'   => $hotelId,
                    'filename'   => basename($relativePath),
                    'path'       => $relativePath,
                    'size'       => Storage::disk('local')->size($relativePath),
                    'status'     => 'completed',
                    'created_by' => auth()->id(),
                ]);
            }

            return back()->with('success', "Restored from {$sourceName}. A safety backup of the previous state was saved as {$safetyFilename}.");
        } catch (HotelBackupStaleDataException $e) {
            if ($keepUploadAsBackupRow) {
                Storage::disk('local')->delete($relativePath);
            }

            return back()
                ->with('error', $e->getMessage() . ' Re-submit with the "restore anyway" confirmation if you want to proceed.')
                ->with('stale', true);
        } catch (\Throwable $e) {
            if ($keepUploadAsBackupRow) {
                Storage::disk('local')->delete($relativePath);
            }
            report($e);

            return back()->with('error', 'Restore failed: ' . $e->getMessage() . " A safety backup of the pre-restore state exists as {$safetyFilename}.");
        }
    }

    public function download(Backup $backup)
    {
        $hotelId = CurrentHotel::homeId();
        abort_unless($backup->hotel_id === $hotelId, 404);
        abort_unless($backup->status === 'completed', 404);
        abort_unless(Str::startsWith($backup->path, "backups/hotel-{$hotelId}/"), 404);
        abort_unless(Storage::disk('local')->exists($backup->path), 404);

        return Storage::disk('local')->download($backup->path, $backup->filename);
    }

    public function destroy(Backup $backup)
    {
        $hotelId = CurrentHotel::homeId();
        abort_unless($backup->hotel_id === $hotelId, 404);

        if (Str::startsWith($backup->path, "backups/hotel-{$hotelId}/")) {
            Storage::disk('local')->delete($backup->path);
        }

        $backup->delete();

        return back()->with('success', 'Backup deleted.');
    }
}
