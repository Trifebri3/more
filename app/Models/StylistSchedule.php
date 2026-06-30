<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StylistSchedule extends Model
{
    protected $fillable = [
        'stylist_id', 'date', 'start_time', 'end_time', 'status'
    ];

    public function stylist(): BelongsTo
    {
        return $this->belongsTo(Stylist::class);
    }
}
