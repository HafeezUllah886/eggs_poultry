<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OilProducts extends Model
{
    protected $guarded = [];

    public function oilPurchase()
    {
        return $this->hasMany(OilPurchase::class);
    }
}
