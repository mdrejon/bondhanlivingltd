<?php

namespace App\Support;

use App\Models\District;
use App\Models\PoliceStation;
use App\Models\Upazila;

/**
 * Shared "give me every district/upazila/police-station for a cascading select"
 * props helper — used by Admin\HotelController (Phase 4) and Admin\CustomerController
 * (Phase 5), both of which need the full Bangladesh geography tree client-side for
 * instant cascading dropdowns (no per-keystroke round-trips).
 */
class Geography
{
    public static function selectOptions(): array
    {
        return [
            'districts'      => District::orderBy('name')->get(['id', 'name', 'name_bn']),
            'upazilas'       => Upazila::orderBy('name')->get(['id', 'district_id', 'name', 'name_bn']),
            'policeStations' => PoliceStation::orderBy('name')->get(['id', 'district_id', 'upazila_id', 'name', 'name_bn']),
        ];
    }
}
