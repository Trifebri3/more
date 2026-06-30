<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StylistLeaveRequest extends Model
{
    protected $fillable = [
        'stylist_id', 'start_date', 'end_date', 'reason', 'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function stylist(): BelongsTo
    {
        return $this->belongsTo(Stylist::class);
    }
}
