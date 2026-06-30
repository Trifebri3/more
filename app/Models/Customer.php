<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'user_id', 'full_name', 'email', 'phone', 'whatsapp', 
        'date_of_birth', 'gender', 'membership_status', 'loyalty_points', 
        'total_visits', 'total_spending', 'last_visit_at'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'loyalty_points' => 'integer',
        'total_visits' => 'integer',
        'total_spending' => 'decimal:2',
        'last_visit_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
