<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stylist extends Model
{
    protected $fillable = [
        'user_id', 'outlet_id', 'name', 'avatar_url', 'specialty', 'rating', 
        'experience_years', 'schedule', 'is_active'
    ];

    protected $casts = [
        'schedule' => 'array',
        'is_active' => 'boolean',
        'rating' => 'decimal:2',
        'experience_years' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(StylistSchedule::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(StylistLeaveRequest::class);
    }
}
