<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stock extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(products::class);
    }

    public function ScopeCurrentBranch($query)
    {
        return $query->where('branch_id', auth()->user()->branch_id);
    }
}
