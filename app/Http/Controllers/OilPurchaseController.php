<?php

namespace App\Http\Controllers;

use App\Models\OilPurchase;
use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\OilProducts;
use App\Models\OilPurchaseDetails;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OilPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start = $request->start ?? date('Y-m-d');
        $end = $request->end ?? date('Y-m-d');
        $purchases = OilPurchase::whereBetween('date', [$start, $end])->get();
        return view('oil.purchase.index', compact('purchases', 'start', 'end'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = accounts::where('type', 'Vendor')->get();
        $products = OilProducts::all();
        return view('oil.purchase.create', compact('vendors', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try
        {
            if($request->isNotFilled('id'))
            {
                throw new Exception('Please Select Atleast One Product');
            }
            DB::beginTransaction();
            $ref = getRef();
            $purchase = OilPurchase::create(
                [
                  'vendorID'        => $request->vendorID,
                  'date'            => $request->date,
                  'note'           => $request->notes,
                  'tax'             => $request->tax,
                  'refID'           => $ref,
                ]
            );

            $ids = $request->id;
            $total = 0;
            foreach($ids as $key => $id)
            {
                if($request->qty[$key] > 0)
                {
                    $qty = $request->qty[$key];
                    $price = $request->price[$key];
                    $amount = $price * $qty;
                    $total += $amount;

                OilPurchaseDetails::create(
                    [
                        'purchaseID'    => $purchase->id,
                        'productID'     => $id,
                        'price'         => $price,
                        'qty'           => $qty,
                        'amount'        => $amount,
                        'date'          => $request->date,
                        'refID'         => $ref,
                    ]
                );
                createStock($id, $qty, 0, $request->date, "Purchased", $ref);

                $product = OilProducts::find($id);
                $product->update(
                    [
                        'pprice'  => $price,
                    ]
                );
                }
            }
            $taxValue = $total * ($request->tax / 100);

            $net = $total + $taxValue;

            $purchase->update(
                [
                    'total'       => $net,
                    'tax_value'   => $taxValue,
                ]
            );

            DB::commit();
            return back()->with('success', "Purchase Created");
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $purchase = OilPurchase::find($id);
        return view('oil.purchase.view', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OilPurchase $oilPurchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OilPurchase $oilPurchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OilPurchase $oilPurchase)
    {
        //
    }

    public function getProduct($id)
    {
        $product = OilProducts::find($id);
        return response()->json($product);
    }

    public function getProductByCode($code)
    {
        $product = OilProducts::where('code', $code)->first();
        if($product)
        {
           return $product->id;
        }
        return "Not Found";
    }
}
