<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class accounts extends Model
{
    protected $guarded = [];

    public function scopeBusiness($query)
    {
        return $query->where('category', 'Business');
    }

    public function scopeSupplier($query)
    {
        return $query->where('category', 'Supplier');
    }

    public function scopeCustomer($query)
    {
        return $query->where('category', 'Customer');
    }

    public function branch()
    {
        return $this->belongsTo(branches::class);
    }
}
