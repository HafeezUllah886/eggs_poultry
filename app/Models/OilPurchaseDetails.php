<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OilPurchaseDetails extends Model
{
    protected $guarded = [];

    public function purchase()
    {
        return $this->belongsTo(OilPurchase::class, 'purchaseID');
    }

    public function product()
    {
        return $this->belongsTo(OilProducts::class, 'productID');
    }
}
