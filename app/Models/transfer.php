<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transfer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function fromAccount()
    {
        return $this->belongsTo(accounts::class, 'from');
    }
    public function toAccount()
    {
        return $this->belongsTo(accounts::class, 'to');
    }

    public function branch()
    {
        return $this->belongsTo(branches::class, 'branch_id');
    }

    public function ScopeCurrentBranch($query)
    {
        return $query->where('branch_id', auth()->user()->branch_id);
    }

}
