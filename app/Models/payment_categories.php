<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class payment_categories extends Model
{
    protected $fillable = [
        'name',
        'for',
    ];

    public function scopeReceive($query)
    {
        return $query->where('for', 'Receive');
    }

    public function scopePayment($query)
    {
        return $query->where('for', 'Payment');
    }
}
