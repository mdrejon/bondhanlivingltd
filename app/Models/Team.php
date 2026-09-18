<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'designation',
        'image',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'status',
        'order',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
