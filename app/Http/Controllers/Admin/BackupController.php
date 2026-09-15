<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Backup;
use App\Services\DatabaseBackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BackupController extends Controller
{
    public function index()
    {
        $backups = Backup::with('creator:id,name')
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

        return Inertia::render('Admin/Backup/Index', [
            'backups' => $backups,
        ]);
    }

    public function store(DatabaseBackupService $service)
    {
        $filename = 'backup_' . now()->format('Y-m-d_His') . '.sql';
        $relative = 'backups/' . $filename;

        Storage::disk('local')->makeDirectory('backups');
        $absolute = Storage::disk('local')->path($relative);

        try {
            $service->dump($absolute);

            Backup::create([
                'filename'   => $filename,
                'path'       => $relative,
                'size'       => is_file($absolute) ? (filesize($absolute) ?: 0) : 0,
                'status'     => 'completed',
                'created_by' => auth()->id(),
            ]);

            return back()->with('success', 'Database backup created successfully.');
        } catch (\Throwable $e) {
            if (is_file($absolute)) {
                @unlink($absolute);
            }

            Backup::create([
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

    public function restore(Backup $backup, DatabaseBackupService $service)
    {
        abort_unless($backup->status === 'completed', 404);
        abort_unless(Str::startsWith($backup->path, 'backups/'), 404);
        abort_unless(Storage::disk('local')->exists($backup->path), 404);

        return $this->performRestore($service, $backup->path, $backup->filename);
    }

    public function restoreUpload(Request $request, DatabaseBackupService $service)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:102400'], // 100 MB
        ]);

        $upload = $request->file('file');

        if (strtolower($upload->getClientOriginalExtension()) !== 'sql') {
            return back()->with('error', 'Only .sql backup files can be uploaded.');
        }

        $sql = file_get_contents($upload->getRealPath());
        if ($sql === false) {
            return back()->with('error', 'The uploaded file could not be read.');
        }

        // Structure verification — refuse to restore a dump whose tables or
        // columns differ from the current database.
        $problems = $service->validateStructure($sql);
        if ($problems) {
            return back()->with(
                'error',
                'The uploaded file does not match the current database structure: '
                . implode(' ', array_slice($problems, 0, 5))
                . (count($problems) > 5 ? ' (+' . (count($problems) - 5) . ' more)' : '')
            );
        }

        // Keep the verified upload in the backup list so it can be
        // re-downloaded or restored again later.
        $filename = 'backup_' . now()->format('Y-m-d_His') . '_uploaded.sql';
        Storage::disk('local')->putFileAs('backups', $upload, $filename);

        Backup::create([
            'filename'   => $filename,
            'path'       => 'backups/' . $filename,
            'size'       => Storage::disk('local')->size('backups/' . $filename),
            'status'     => 'completed',
            'created_by' => auth()->id(),
        ]);

        return $this->performRestore($service, 'backups/' . $filename, $upload->getClientOriginalName());
    }

    /**
     * Shared restore flow: take a safety backup of the current state first,
     * then restore from the given (already validated) dump file.
     */
    private function performRestore(DatabaseBackupService $service, string $relativePath, string $sourceName)
    {
        $safetyFilename = 'backup_' . now()->format('Y-m-d_His') . '_pre_restore.sql';
        $safetyRelative = 'backups/' . $safetyFilename;
        $safetyAbsolute = Storage::disk('local')->path($safetyRelative);

        try {
            $service->dump($safetyAbsolute);

            Backup::create([
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
            report($e);

            return back()->with('error', 'Restore aborted: could not create the pre-restore safety backup. ' . $e->getMessage());
        }

        try {
            // The service skips the backups (and sessions) table sections, so
            // the backup log — including the safety backup above — survives.
            $service->restore(Storage::disk('local')->path($relativePath));

            return back()->with('success', "Database restored from {$sourceName}. A safety backup of the previous state was saved as {$safetyFilename}.");
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Restore failed: ' . $e->getMessage() . " A safety backup of the pre-restore state exists as {$safetyFilename}.");
        }
    }

    public function download(Backup $backup)
    {
        // Files are only ever served from the private backups folder, by id —
        // never from a client-supplied path.
        abort_unless($backup->status === 'completed', 404);
        abort_unless(Str::startsWith($backup->path, 'backups/'), 404);
        abort_unless(Storage::disk('local')->exists($backup->path), 404);

        return Storage::disk('local')->download($backup->path, $backup->filename);
    }

    public function destroy(Backup $backup)
    {
        if (Str::startsWith($backup->path, 'backups/')) {
            Storage::disk('local')->delete($backup->path);
        }

        $backup->delete();

        return back()->with('success', 'Backup deleted.');
    }
}
