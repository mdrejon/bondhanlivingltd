<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown by HotelDataBackupService::restore() when the hotel has data newer than
 * the backup being restored, and the caller didn't pass force=true. See the
 * restore-semantics decision in the Phase 8B plan: block by default, let the
 * admin consciously override.
 */
class HotelBackupStaleDataException extends RuntimeException
{
    public function __construct(
        public readonly string $hotelNewestAt,
        public readonly string $backupSnapshotAt,
    ) {
        parent::__construct(
            "This hotel has data from {$hotelNewestAt}, newer than this backup (from {$backupSnapshotAt}). "
            . 'Restoring would lose that newer data.'
        );
    }
}
