<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'stylist_id',
        'rating',
        'comment'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function stylist()
    {
        return $this->belongsTo(Stylist::class);
    }
}
