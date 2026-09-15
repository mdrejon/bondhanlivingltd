<?php

namespace App\Support;


/**
 * Resolves which hotel(s) a tenant-scoped query/write should apply to. Backs
 * App\Models\Concerns\BelongsToHotel — see that trait for how it's used.
 */
class CurrentHotel
{
    public static function visibleHotelIds(): ?array
    {
        return null;
    }

    public static function homeId(): ?int
    {
        return 0;
    }
}
