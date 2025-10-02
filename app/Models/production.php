<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class production extends Model
{
    protected $guarded = [];

    public function ScopeCurrentBranch($query)
    {
        return $query->where('branch_id', auth()->user()->branch_id);
    }

    public function initial_product()
    {
        return $this->belongsTo(products::class, 'initial_product_id', 'id');
    }

    public function final_product()
    {
        return $this->belongsTo(products::class, 'final_product_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(branches::class, 'branch_id', 'id');
    }

    public function account()
    {
        return $this->belongsTo(accounts::class, 'account_id', 'id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
