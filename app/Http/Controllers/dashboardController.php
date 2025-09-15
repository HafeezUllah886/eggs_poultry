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

        $purchases = purchase::whereBetween('date', [$from, $to]);
        $purchase_amount = $purchases->sum('total');
        $purchase_price_tax = $purchases->sum('ptax');
        $purchase_auction_tax = $purchases->sum('atax');
       
        $export = export::whereBetween('date', [$from, $to]);
        $export_amount = $export->sum('amount');

        $oil_purchases = OilPurchase::whereBetween('date', [$from, $to]);
        $oil_purchase_amount = $oil_purchases->sum('total');
        $oil_tax = $oil_purchases->sum('tax_value');

        $total_tax = $purchase_price_tax + $purchase_auction_tax +  $oil_tax;

        $recycle = purchase::whereBetween('date', [$from, $to])->sum('recycle');

        $products = OilProducts::all();

        $oil_stock = 0;

        foreach ($products as $product) {
            $product->stock = getStock($product->id);
            $oil_stock += $product->stock;
        }

        $tt = ReceiveTT::whereBetween('date', [$from, $to]);
        $tt_amount = $tt->sum('total_yen');

        $car_stock = purchase::where('status', 'Available')->count();
        $car_stock_value = purchase::where('status', 'Available')->sum('total');


        return view('dashboard.index', compact('purchase_amount', 'export_amount', 'oil_purchase_amount', 'from', 'to', 'oil_stock', 'total_tax', 'recycle', 'tt_amount', 'car_stock', 'car_stock_value'));
    }
}
