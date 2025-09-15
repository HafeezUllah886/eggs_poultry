<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\issue_payment;
use App\Models\export_oils;
use App\Models\issue_payments;
use App\Models\OilProducts;
use App\Models\OilPurchaseDetails;
use App\Models\payment_categories;
use Illuminate\Http\Request;

class OilReportController extends Controller
{
    public function index()
    {
        $categories = payment_categories::payment()->get();
        return view('reports.oil_product.index', compact('categories'));
    }

    public function data(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $category = $request->category;

        $products = OilProducts::all();

        foreach ($products as $product) {

            $purchase = OilPurchaseDetails::where('productID', $product->id)->whereBetween('date', [$from, $to]);

            $product->purchase_qty = $purchase->sum('qty');
            $product->purchase_amount = $purchase->sum('amount');

            $export = export_oils::where('product_id', $product->id)->whereBetween('date', [$from, $to]);

            $product->export_qty = $export->sum('qty');
            $product->export_amount = $export->sum('amount');

            $product->stock = getStock($product->id);
            $product->stock_value = productStockValue($product->id);
            
        }

        $tt = issue_payments::where('category_id', $category)->whereBetween('date', [$from, $to])->sum('amount');


        return view('reports.oil_product.details', compact('products', 'tt', 'from', 'to'));
    }
}
