<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    protected $guarded = [];
    
    public function scopeCurrentBranch($query)
    {
        return $query->where('branch_id', auth()->user()->branch_id);
    }
    
    public function transporter()
    {
        return $this->belongsTo(accounts::class, 'transporter_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function branch()
    {
        return $this->belongsTo(branches::class, 'branch_id');
    }
}
