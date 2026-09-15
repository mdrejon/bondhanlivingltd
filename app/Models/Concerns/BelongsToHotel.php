<?php

namespace App\Models\Concerns;

use App\Models\Hotel;
use App\Support\CurrentHotel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Applied to every tenant-owned model (RoomType, Room, Customer, RoomBooking,
 * Inquiry). Automatically filters queries to the current hotel and stamps new
 * records with it — see App\Support\CurrentHotel for the resolution rules.
 *
 * Deliberately NOT applied to User: the auth guard resolves the logged-in user via
 * User::find() on every request, and a global scope that calls auth()->check()/
 * auth()->user() from inside that same lookup risks recursive/inconsistent
 * resolution. User's own tenant filtering (e.g. hotel-scoped user management lists)
 * is applied explicitly per-query instead — see docs/hgrm-saas/DATABASE-SCHEMA.md.
 */
trait BelongsToHotel
{
    protected static function bootBelongsToHotel(): void
    {
        static::addGlobalScope('hotel', function (Builder $builder) {
            $hotelIds = CurrentHotel::visibleHotelIds();

            if ($hotelIds === null) {
                return; // super admin — unrestricted
            }

            // whereIn on an empty array compiles to an always-false condition (Laravel's
            // query grammar), so a jurisdiction-less/misconfigured account fails closed
            // (zero rows) rather than needing special-cased handling here.
            $builder->whereIn($builder->qualifyColumn('hotel_id'), $hotelIds);
        });

        static::creating(function ($model) {
            if (empty($model->hotel_id)) {
                $model->hotel_id = CurrentHotel::homeId();
            }
        });
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
}
