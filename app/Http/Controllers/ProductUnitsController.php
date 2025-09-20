<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\product_units;
use App\Models\products;
use App\Models\units;
use Illuminate\Http\Request;

class ProductUnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
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
        $request->validate(
            [
                'name' => "unique:product_units,name",
            ],
            [
            'name.unique' => "Unit already Existing",
            ]
        );
        product_units::create(
            [
                'product_id' => $request->product_id,
                'name' => $request->name,
                'value' => $request->value,
            ]
        );

        return redirect()->back()->with('success', 'Unit Added');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product_units = product_units::where('product_id', $id)->get();
        $product = products::find($id);

        return view('products.product_units', compact('product_units', 'product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        product_units::where('id', $id)->update(
            [
                'name' => $request->name,
                'value' => $request->value,
            ]
        );

        return redirect()->back()->with('success', 'Unit Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
