<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\ReceiveTT;
use Illuminate\Http\Request;

class ReceiveTTController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start = $request->start ?? firstDayOfMonth();
        $end = $request->end ?? date('Y-m-d');
        $tts = ReceiveTT::orderBy('id', 'desc')->whereBetween('date', [$start, $end])->get();
        $banks = accounts::where('type', 'bank')->get();
        return view('finance.receive_tt.index', compact('tts', 'banks', 'start', 'end'));
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
        receiveTT::create($request->all());
        return redirect()->route('receive_t_t_s.index')->with('success', 'TT Receiving created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tt = ReceiveTT::find($id);
        return view('finance.receive_tt.receipt', compact('tt'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReceiveTT $receiveTT)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReceiveTT $receiveTT)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $receiveTT = ReceiveTT::find($id);
        $receiveTT->delete();
        session()->forget('confirmed_password');
        return redirect()->route('receive_t_t_s.index')->with('success', 'TT Receiving deleted successfully');
    }
}
