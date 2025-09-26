<?php

use App\Models\material_stock;
use App\Models\OilPurchaseDetails;
use App\Models\products;
use App\Models\purchase_details;
use App\Models\ref;
use App\Models\sale_details;
use App\Models\stock;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

function createStock($id, $cr, $db, $date, $notes, $ref, $branch)
{
    stock::create(
        [
            'product_id'     => $id,
            'cr'            => $cr,
            'db'            => $db,
            'date'          => $date,
            'notes'         => $notes,
            'refID'         => $ref,
            'branch_id'     => $branch,
        ]
    );
}
function getStock($id){
   
        $cr  = stock::where('productID', $id)->sum('cr');
        $db  = stock::where('productID', $id)->sum('db');
  
    return $cr - $db;
}

