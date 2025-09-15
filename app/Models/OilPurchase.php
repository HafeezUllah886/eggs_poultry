<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OilPurchase extends Model
{
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(OilPurchaseDetails::class, 'purchaseID');
    }

    public function vendor()
    {
        return $this->belongsTo(accounts::class, 'vendorID');
    }
}
