<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalkIn extends Model
{
    protected $fillable = [
        'customer_name', 'whatsapp', 'service_id', 'stylist_id', 
        'queue_number', 'status'
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function stylist(): BelongsTo
    {
        return $this->belongsTo(Stylist::class);
    }
}
