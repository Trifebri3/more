<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Outlet extends Model
{
    protected $fillable = [
        'name', 'slug', 'address', 'phone', 'latitude', 'longitude', 
        'image_url', 'rating', 'opening_hours', 'description'
    ];

    protected $casts = [
        'opening_hours' => 'array',
        'rating' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function stylists(): HasMany
    {
        return $this->hasMany(Stylist::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
