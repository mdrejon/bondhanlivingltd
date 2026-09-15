<?php

namespace App\Models;

use App\Models\Concerns\BelongsToHotel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Customer extends Model
{
    use BelongsToHotel;

    protected $fillable = [
        'hotel_id', 'name', 'phone', 'email', 'nationality', 'address',
        'document_type', 'nid_number', 'passport_number', 'document_image',

        // Personal (PRD § 5.3)
        'father_name', 'mother_name', 'gender', 'date_of_birth', 'occupation',
        'emergency_contact', 'present_address', 'permanent_address',
        'district_id', 'upazila_id', 'police_station_id', 'post_code',

        // Identity
        'birth_certificate_number', 'driving_license_number',

        // Foreign guest
        'is_foreign_guest', 'visa_number', 'arrival_date_bd',

        // Marriage / companion (MVP scope)
        'is_couple', 'spouse_name', 'marriage_date',

        // Police-only flag — see User::canManagePoliceFlag()
        'is_flagged', 'flagged_note',
    ];

    protected $casts = [
        'date_of_birth'     => 'date',
        'arrival_date_bd'   => 'date',
        'marriage_date'     => 'date',
        'is_foreign_guest'  => 'boolean',
        'is_couple'         => 'boolean',
        'is_flagged'        => 'boolean',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(RoomBooking::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /** The guest's home district/upazila/police station — distinct from the hotel's own. */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function upazila(): BelongsTo
    {
        return $this->belongsTo(Upazila::class);
    }

    public function policeStation(): BelongsTo
    {
        return $this->belongsTo(PoliceStation::class);
    }
}
