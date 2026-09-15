<?php
/**
 * Storage Copy Script — for hosts that block symlinks.
 * Copies storage/app/public → public/storage/
 *
 * Visit: https://hotel-beach-way.sydurrahman.com/link-storage.php
 * DELETE THIS FILE after use.
 */

$source = dirname(__DIR__) . '/storage/app/public';
$dest   = __DIR__ . '/storage';

echo '<!DOCTYPE html><html><head><meta charset="utf-8">
<title>Storage Copy</title>
<style>body{font-family:monospace;padding:30px;background:#1a1a2e;color:#eee}
h2{color:#c9a47a}.ok{color:#4caf50}.err{color:#f44336}.info{color:#90caf9}
pre{background:#0d0d1a;padding:20px;border-radius:8px;overflow-x:auto}</style></head><body>';

echo '<h2>Storage Setup</h2><pre>';

// Check source exists
if (!is_dir($source)) {
    echo '<span class="err">ERROR: Source not found: ' . $source . '</span>' . "\n";
    echo 'Make sure storage/app/public exists on your server.';
    echo '</pre></body></html>';
    exit;
}

echo '<span class="info">Source: ' . $source . '</span>' . "\n";
echo '<span class="info">Dest  : ' . $dest . '</span>' . "\n\n";

// Create dest directory if it doesn't exist
if (!is_dir($dest)) {
    if (!mkdir($dest, 0755, true)) {
        echo '<span class="err">ERROR: Cannot create directory: ' . $dest . '</span>' . "\n";
        echo 'Please create the folder "storage" inside your public folder via cPanel File Manager, then re-run.';
        echo '</pre></body></html>';
        exit;
    }
    echo '<span class="ok">Created: public/storage/</span>' . "\n\n";
}

// Recursive copy function
function copyDir($src, $dst) {
    $results = ['copied' => 0, 'skipped' => 0, 'errors' => [], 'dirs' => 0];

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($src, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $item) {
        $target = $dst . DIRECTORY_SEPARATOR . $iterator->getSubPathname();

        if ($item->isDir()) {
            if (!is_dir($target)) {
                mkdir($target, 0755, true);
                $results['dirs']++;
            }
        } else {
            if (!is_dir(dirname($target))) {
                mkdir(dirname($target), 0755, true);
            }
            if (copy($item->getPathname(), $target)) {
                $results['copied']++;
            } else {
                $results['errors'][] = $iterator->getSubPathname();
            }
        }
    }

    return $results;
}

$r = copyDir($source, $dest);

echo 'Directories created : ' . $r['dirs']   . "\n";
echo 'Files copied        : ' . $r['copied'] . "\n";
echo 'Errors              : ' . count($r['errors']) . "\n\n";

if (count($r['errors']) > 0) {
    echo '<span class="err">Failed files:</span>' . "\n";
    foreach ($r['errors'] as $f) {
        echo '  - ' . $f . "\n";
    }
}

if ($r['copied'] > 0 && count($r['errors']) === 0) {
    echo '<span class="ok">✓ All files copied successfully!</span>' . "\n";
    echo '<span class="ok">  Your storage images should now be visible.</span>' . "\n\n";
    echo '<span class="err">⚠ DELETE this file from your server now (link-storage.php)</span>' . "\n";
} elseif ($r['copied'] > 0) {
    echo '<span class="ok">Partially done — some files copied. Check errors above.</span>' . "\n";
} else {
    echo '<span class="err">Nothing was copied. Your storage/app/public may be empty,</span>' . "\n";
    echo '<span class="err">or file permissions are too restrictive.</span>' . "\n";
    echo "\n<span class=\"info\">Manual fix via cPanel:</span>\n";
    echo "1. Open cPanel File Manager\n";
    echo "2. Navigate to: storage/app/public/\n";
    echo "3. Select all folders (about, rooms, etc.)\n";
    echo "4. Copy them to: public/storage/\n";
}

echo '</pre></body></html>';
