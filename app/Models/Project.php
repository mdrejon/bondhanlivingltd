<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'status',
        'client',
        'location',
        'description',
        'thumbnail',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'archived');
    }

    public function scopeRunning($query)
    {
        return $query->where('status', 'running');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }
}
