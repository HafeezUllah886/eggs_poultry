<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class accounts extends Model
{
    protected $guarded = [];

    public function branch()
    {
        return $this->belongsTo(branches::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeBusiness($query)
    {
        return $query->where('category', 'Business');
    }

    public function scopeCustomer($query)
    {
        return $query->where('category', 'Customer');
    }

    public function scopeSupplier($query)
    {
        return $query->where('category', 'Supplier');
    }

    public function scopeCurrentBranch($query)
    {
        return $query->where('branch_id', auth()->user()->branch_id);
    }
}
