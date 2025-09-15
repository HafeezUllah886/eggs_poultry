<?php

namespace App\Http\Controllers;

use App\Models\auctions;
use App\Models\makers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MakersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $makers = makers::all();

        return view('setting.makers', compact('makers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        
        $request->validate(
            [
                'name' => 'required|unique:makers,name'
            ]
        );

        makers::create(
            [
                'name' => $request->name,
            ]
        );

        return back()->with('success', 'Maker Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(makers $maker)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(makers $maker)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, makers $maker)
    {
        $request->validate(
            [
                'name' => 'required|unique:makers,name,' . $maker->id,
            ]
        );

        $maker->update(
            [
                'name' => $request->name,
            ]
        );

        return back()->with('success', "Maker Updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(makers $maker)
    {
        //
    }
}
