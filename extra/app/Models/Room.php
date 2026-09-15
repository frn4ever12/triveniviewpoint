<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name',
        'room_number',
        'room_type',
        'floor',
        'price_per_night',
        'capacity',
        'bed_count',
        'bed_type',

        // Amenities
        'has_ac',
        'has_tv',
        'has_wifi',
        'has_minibar',
        'has_balcony',
        'is_smoking_allowed',
        'is_wheelchair_accessible',

        // Status & Management
        'status',
        'description',
        'notes',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'has_ac' => 'boolean',
        'has_tv' => 'boolean',
        'has_wifi' => 'boolean',
        'has_minibar' => 'boolean',
        'has_balcony' => 'boolean',
        'is_smoking_allowed' => 'boolean',
        'is_wheelchair_accessible' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'maintenance');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('room_type', $type);
    }
}
