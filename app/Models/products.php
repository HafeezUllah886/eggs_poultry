<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function units()
    {
        return $this->hasMany(product_units::class, 'product_id');
    }

    public function saleDetails()
    {
        return $this->hasMany(sale_details::class, 'product_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');

    }

    public function stock()
    {
        $cr  = stock::where('product_id', $this->id)->sum('cr');
        $db  = stock::where('product_id', $this->id)->sum('db');
  
    return $cr - $db;
    }

    public function currentBranchStock()
    {
        $branch = auth()->user()->branch_id;
        $cr  = stock::where('product_id', $this->id)->where('branch_id', $branch)->sum('cr');
        $db  = stock::where('product_id', $this->id)->where('branch_id', $branch)->sum('db');
  
    return $cr - $db;
    }

    public function scopeProductionYes($query)
    {
        return $query->where('for_production', 'Yes');
    }

    public function scopeProductionNo($query)
    {
        return $query->where('for_production', 'No');
    }

    public function scopeProduced($query)
    {
        return $query->where('for_production', 'Produced');
    }

    public function scopeCannotProduce($query)
    {
        return $query->where('for_production', '!=', 'Produced');
    }

}
