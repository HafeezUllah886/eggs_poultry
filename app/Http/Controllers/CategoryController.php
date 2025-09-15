<?php

namespace App\Http\Controllers;

use App\Models\auctions;
use App\Models\category;
use App\Models\makers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = category::all();

        return view('setting.categories', compact('categories'));
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
                'name' => 'required|unique:category,name'
            ]
        );

        category::create(
            [
                'name' => $request->name,
            ]
        );

        return back()->with('success', 'Category Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, category $category)
    {
        $request->validate(
            [
                'name' => 'required|unique:category,name,' . $category->id,
            ]   
        );

        $category->update(
            [
                'name' => $request->name,
            ]
        );

        return back()->with('success', "Category Updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(category $category)
    {
        //
    }
}
