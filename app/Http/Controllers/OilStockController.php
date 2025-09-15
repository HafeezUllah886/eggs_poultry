<?php

namespace App\Http\Controllers;

use App\Models\OilProducts;
use App\Models\stock;
use Illuminate\Http\Request;

class OilStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = OilProducts::all();
        return view('oil.stock.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id, $from, $to)
    {
        $product = OilProducts::find($id);

        $stocks = stock::where('productID', $id)->whereBetween('date', [$from, $to])->get();

            $pre_cr = stock::where('productID', $id)->whereDate('date', '<', $from)->sum('cr');
            $pre_db = stock::where('productID', $id)->whereDate('date', '<', $from)->sum('db');

            $cur_cr = stock::where('productID', $id)->sum('cr');
            $cur_db = stock::where('productID', $id)->sum('db');
     
        $pre_balance = $pre_cr - $pre_db;
        $cur_balance = $cur_cr - $cur_db;

        return view('oil.stock.details', compact('product', 'pre_balance', 'cur_balance', 'stocks', 'from', 'to'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(stock $stock)
    {
        //
    }
}
