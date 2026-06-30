<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = [
        'booking_code', 'customer_id', 'outlet_id', 'stylist_id', 
        'booking_date', 'booking_time', 'duration', 'total_price', 
        'discount_amount', 'final_price', 'status', 'service_ids', 
        'source', 'qr_code_url', 'notes'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'service_ids' => 'array',
        'duration' => 'integer',
        'total_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_price' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function stylist(): BelongsTo
    {
        return $this->belongsTo(Stylist::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(BookingDetail::class);
    }

    public function checkIn(): HasOne
    {
        return $this->hasOne(CheckIn::class);
    }

    public function queue(): HasOne
    {
        return $this->hasOne(Queue::class);
    }
}
