<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name',
        'designation',
        'text',
        'rating',
        'image',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
