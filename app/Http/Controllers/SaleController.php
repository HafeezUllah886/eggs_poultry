<?php

namespace App\Http\Controllers;

use App\Http\Middleware\confirmPassword;
use App\Models\accounts;
use App\Models\categories;
use App\Models\products;
use App\Models\purchase;
use App\Models\purchase_details;
use App\Models\purchase_payments;
use App\Models\product_units;
use App\Models\sale;
use App\Models\sale_details;
use App\Models\stock;
use App\Models\transactions;
use App\Models\units;
use App\Models\warehouses;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class SaleController extends Controller
{
   
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $from = $request->from ?? firstDayofMonth();
        $to = $request->to ?? date("Y-m-d");
        $sales = sale::whereBetween('date', [$from, $to])->orderby('id', 'desc')->get();
        return view('sale.index', compact('sales', 'from', 'to'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = products::orderby('name', 'asc')->get();
        $customers = accounts::customer()->get();
        $accounts = accounts::business()->get();
        return view('sale.create', compact('products', 'customers', 'accounts'));
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
            $sale = sale::create(
                [
                  'customer_id'        => $request->customerID,
                  'date'               => $request->date,
                  'notes'              => $request->notes,
                  'rate'               => $request->rate,
                  'rate_type'          => $request->rate_type,
                  'customer_name'      => $request->customer_name,
                  'inv'                => $request->inv,
                  'refID'              => $ref,
                ]
            );

            $ids = $request->id;
            $total = 0;
            $total_pkr = 0;
            foreach($ids as $key => $id)
            {
                if($request->amount[$key] > 0)
                {
                    $qty = $request->qty[$key];
                    $loose = $request->loose[$key];
                    $bonus = $request->bonus[$key];
                    $price = $request->price[$key];
                    $price_pkr = $request->price_pkr[$key];

                    $unit = product_units::find($request->unit[$key]);

                    $pc = ($qty * $unit->value) + $loose;
                    $amount = $price * $qty;
                    $total += $amount;
                    $amount_pkr = $price_pkr * $qty;
                    $total_pkr += $amount_pkr;


                sale_details::create(
                    [
                        'sale_id'   => $sale->id,
                        'product_id'    => $id,
                        'price'         => $request->price[$key],
                        'price_pkr'     => $request->price_pkr[$key],
                        'qty'           => $qty,
                        'loose'         => $loose,
                        'bonus'         => $bonus,
                        'pc'            => $pc,
                        'amount'        => $amount,
                        'amount_pkr'    => $amount_pkr,
                        'unit_name'     => $unit->name,
                        'unit_value'    => $unit->value,
                        'date'          => $request->date,
                        'refID'         => $ref,
                    ]
                );

                createStock($id, 0, $pc, $request->date, "Sale", $ref, auth()->user()->branch_id);
            }

          
        }
        $sale->update(
            [
                'total'           => $total,
                'total_pkr'       => $total_pkr,
            ]
        );
        createTransaction($request->customerID, $request->date, 0, $total, "Pending Amount of Sale No. $sale->id", $ref);
        DB::commit();
        return back()->with('success', "Sale Created");
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
    public function show(sale $sale)
    {
        return view('sale.view', compact('sale'));
    }

   

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(sale $sale)
    {
        $products = products::orderby('name', 'asc')->get();
        $warehouses = warehouses::all();
        $vendors = accounts::vendor()->get();
        $accounts = accounts::business()->where('id', '!=', 1)->get();
        $cats = categories::orderBy('name', 'asc')->get();

        return view('purchase.edit', compact('products', 'warehouses', 'vendors', 'accounts', 'purchase', 'cats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, purchase $purchase)
    {
        try
        {
            if($request->isNotFilled('id'))
            {
                throw new Exception('Please Select Atleast One Product');
            }
            DB::beginTransaction();
            foreach($purchase->payments as $payment)
            {
                transactions::where('refID', $payment->refID)->delete();
                $payment->delete();
            }
            foreach($purchase->details as $product)
            {
                stock::where('refID', $product->refID)->delete();
                $product->delete();
            }
            transactions::where('refID', $purchase->refID)->delete();

            $purchase->update(
                [
                'vendorID'        => $request->vendorID,
                  'date'            => $request->date,
                  'notes'           => $request->notes,
                  'discount'        => $request->discount,
                  'dc'              => $request->dc,
                  'tax'             => $request->tax,
                  'vendorName'      => $request->vendorName,
                  'inv'             => $request->inv,
                  ]
            );

            $ids = $request->id;
            $ref = $purchase->refID;

            $total = 0;
            foreach($ids as $key => $id)
            {
                if($request->qty[$key] > 0)
                {
                    $qty = $request->qty[$key];
                $pprice = $request->pprice[$key];
                $price = $request->price[$key];
                $amount = $pprice * $qty;
                $total += $amount;

                purchase_details::create(
                    [
                        'purchaseID'    => $purchase->id,
                        'productID'     => $id,
                        'pprice'        => $pprice,
                        'price'         => $price,
                        'qty'           => $qty,
                        'amount'        => $amount,
                        'date'          => $request->date,
                        'batch'         => $request->batch[$key],
                        'expiry'        => $request->expiry[$key],
                        'refID'         => $ref,
                        /* 'warehouseID'   => $request->warehouse[$key], */
                    ]
                );
                createStock($id, $qty, 0, $request->date, "Purchased", $ref, /* $request->warehouse[$key], */ $request->batch[$key], $request->expiry[$key]);

                $product = products::find($id);
                $product->update(
                    [
                        'pprice' => $pprice,
                        'price'  => $price,
                    ]
                );
                }
            }

            $net = ($total + $request->dc + $request->tax) - $request->discount;

            $purchase->update(
                [

                    'total'       => $net,
                ]
            );

            if($request->status == 'paid')
            {
                purchase_payments::create(
                    [
                        'purchaseID'    => $purchase->id,
                        'accountID'     => $request->accountID,
                        'date'          => $request->date,
                        'amount'        => $net,
                        'notes'         => "Full Paid",
                        'refID'         => $ref,
                    ]
                );
                createTransaction($request->accountID, $request->date, 0, $net, "Payment of Purchase No. $purchase->id", $ref);
                createTransaction($request->vendorID, $request->date, $net, $net, "Payment of Purchase No. $purchase->id", $ref);
            }
            elseif($request->status == 'advanced')
            {
                $balance = getAccountBalance($request->vendorID);
                if($net > $balance)
                {
                    createTransaction($request->vendorID, $request->date, 0, $net, "Pending Amount of Purchase No. $purchase->id", $ref);
                    DB::commit();
                    return back()->with('success', "Purchase Created: Balance was not enough moved to unpaid / pending");
                }
                purchase_payments::create(
                    [
                        'purchaseID'    => $purchase->id,
                        'accountID'     => $request->accountID,
                        'date'          => $request->date,
                        'amount'        => $net,
                        'notes'         => "Full Paid",
                        'refID'         => $ref,
                    ]
                );
                createTransaction($request->vendorID, $request->date, 0, $net, "Purchase No. $purchase->id", $ref);
            }
            else
            {
                createTransaction($request->vendorID, $request->date, 0, $net, "Pending Amount of Purchase No. $purchase->id", $ref);
            }
            DB::commit();
            session()->forget('confirmed_password');
            return to_route('purchase.index')->with('success', "Purchase Updated");
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        try
        {
            DB::beginTransaction();
            $purchase = purchase::find($id);
            foreach($purchase->payments as $payment)
            {
                transactions::where('refID', $payment->refID)->delete();
                $payment->delete();
            }
            foreach($purchase->details as $product)
            {
                stock::where('refID', $product->refID)->delete();
                $product->delete();
            }
            transactions::where('refID', $purchase->refID)->delete();
            $purchase->delete();
            DB::commit();
            session()->forget('confirmed_password');
            return redirect()->route('purchase.index')->with('success', "Purchase Deleted");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            session()->forget('confirmed_password');
            return redirect()->route('purchase.index')->with('error', $e->getMessage());
        }
    }

    public function getSignleProduct($id)
    {
        $product = products::with('units')->find($id);
        return $product;
    }
}
