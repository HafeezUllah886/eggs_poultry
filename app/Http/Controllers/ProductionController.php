<?php

namespace App\Http\Controllers;

use App\Models\production;
use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\expenses;
use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $from = $request->from ?? firstDayOfMonth();
       $to = $request->to ?? date('Y-m-d');
      
       $productions = production::whereBetween('date', [$from, $to])->currentBranch()->get();

       $initial_products = products::productionYes()->get();
        $final_products = products::produced()->get();
        return view('production.index', compact('productions', 'from', 'to', 'initial_products', 'final_products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $initial_products = products::productionYes()->get();
        $final_products = products::produced()->get();
        $accounts = accounts::business()->currentBranch()->get();
        return view('production.create', compact('initial_products', 'final_products', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'initial_product' => 'required',
            'initial_qty' => 'required',
            'final_product' => 'required',
            'date' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $ref = getRef();

           $production = production::create([
                'initial_product_id' => $request->initial_product,
                'final_product_id' => $request->final_product,
                'branch_id' => auth()->user()->branch_id,
                'date' => $request->date,
                'initial_qty' => $request->initial_qty,
                'created_by' => auth()->user()->id,
                'notes' => $request->notes,
                'refID' => $ref,
            ]);

            createStock($request->initial_product, 0, $request->initial_qty, $request->date, 'Used in Production ID ' . $production->id, $ref, auth()->user()->branch_id);

            DB::commit();
            return redirect()->route('production.index')->with('success', 'Production created successfully');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(production $production)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(production $production)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, production $production)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(production $production)
    {
        //
    }

    public function complete(Request $request)
    {
        $production = production::find($request->id);
        try {
            DB::beginTransaction();

        $production->update(
            [
                'final_qty' => $request->qty,
                'production_date' => $request->date,
                'notes' => $request->notes,
                'status' => "Completed"
            ]
        );

        createStock($production->final_product_id, $request->qty, 0, $request->date, 'Produced in Production ID ' . $production->id, $production->refID, auth()->user()->branch_id);
        DB::commit();
        return back()->with("success", "Production Marked as Completed");
        
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with("error", $e->getMessage());
    }   
    }
}
