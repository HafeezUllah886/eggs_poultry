<?php

namespace App\Http\Controllers;

use App\Models\OilProducts;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OilProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = OilProducts::all();
        return view('setting.oils', compact('items'));
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
        $request->validate([
            'code' => 'required|unique:oil_products,code',
            'name' => 'required',
            'packing' => 'required',
            'pprice' => 'required',
        ]);
        OilProducts::create($request->all());
        return redirect()->route('oil_products.index')->with('success', 'Oil Product Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(OilProducts $oilProducts)
    {
        $item = OilProducts::findOrFail($oilProducts->id);
        return view('setting.oil.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OilProducts $oilProducts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|unique:oil_products,code,' . $id,
            'name' => 'required',
            'packing' => 'required',
            'pprice' => 'required',
        ]);
        OilProducts::findOrFail($id)->update($request->all());
        return redirect()->route('oil_products.index')->with('success', 'Oil Product Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OilProducts $oilProducts)
    {
        //
    }
}
