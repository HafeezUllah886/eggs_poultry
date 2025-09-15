<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiveTT extends Model
{
    protected $guarded = [];

    public function bank()
    {
        return $this->belongsTo(accounts::class, 'bank_id');
    }
}
