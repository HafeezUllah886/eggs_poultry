<?php

namespace App\Http\Controllers;

use App\Models\export;
use App\Models\purchase;
use App\Models\OilPurchase;
use App\Models\OilProducts;
use App\Models\ReceiveTT;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from ?? firstDayOfMonth();
        $to = $request->to ?? date('Y-m-d');

        return view('dashboard.index', compact('from', 'to'));
    }
}
