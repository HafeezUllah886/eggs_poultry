<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class export_oils extends Model
{
    protected $guarded = [];

    public function export()
    {
        return $this->belongsTo(export::class);
    }

    public function product()
    {
        return $this->belongsTo(OilProducts::class);
    }
}
