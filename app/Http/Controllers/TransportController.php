<?php

namespace App\Http\Controllers;

use App\Models\Transport;
use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $from = $request->from ?? firstDayOfMonth();
        $to = $request->to ?? date('Y-m-d');

        $transports = Transport::whereBetween('date', [$from, $to])->currentBranch()->get();

       

        return view('finance.transport.index', compact('transports', 'from', 'to'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $transporters = accounts::transporter()->currentBranch()->get();
        return view('finance.transport.create', compact('transporters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            DB::beginTransaction();

            $ref = getRef();
            Transport::create([
                'transporter_id' => $request->transporter_id,
                'user_id' => auth()->user()->id,
                'branch_id' => auth()->user()->branch_id,
                'customer_name' => $request->customer_name,
                'driver_name' => $request->driver_name,
                'vehicle_no' => $request->vehicle_no,
                'from' => $request->from,
                'to' => $request->to,
                'fare' => $request->fare,
                'expense' => $request->expense,
                'profit' => $request->profit,
                'date' => $request->date,
                'notes' => $request->notes,
                'refID' => $ref,
            ]);

            createTransaction($request->transporter_id, $request->date,$request->fare, $request->expense, "Payment of Transport Charges Customer $request->customer_name, Driver $request->driver_name, Vehicle No $request->vehicle_no, Notes $request->notes", $ref);

            DB::commit();

            return redirect()->back()->with('success', 'Transport created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $transport = Transport::find($id);
        return view('finance.transport.receipt', compact('transport'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transport $transport)
    {
        $transporters = accounts::transporter()->currentBranch()->get();
        return view('finance.transport.edit', compact('transporters', 'transport'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transport $transport)
    {
        try {

            DB::beginTransaction();

            $transport->update([
                'transporter_id' => $request->transporter_id,
                'user_id' => auth()->user()->id,
                'branch_id' => auth()->user()->branch_id,
                'customer_name' => $request->customer_name,
                'driver_name' => $request->driver_name,
                'vehicle_no' => $request->vehicle_no,
                'from' => $request->from,
                'to' => $request->to,
                'fare' => $request->fare,
                'expense' => $request->expense,
                'profit' => $request->profit,
                'date' => $request->date,
                'notes' => $request->notes,
            ]);

            transactions::where('refID', $transport->refID)->delete();
             createTransaction($request->transporter_id, $request->date,$request->fare, $request->expense, "Payment of Transport Charges Customer $request->customer_name, Driver $request->driver_name, Vehicle No $request->vehicle_no, Notes $request->notes", $transport->refID);
            DB::commit();

            return redirect()->back()->with('success', 'Transport updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($refID)
    {
        try {

            DB::beginTransaction();

            $transport = Transport::where('refID', $refID)->first();
            transactions::where('refID', $refID)->delete();
            $transport->delete();
            DB::commit();
           session()->forget('confirmed_password');
            return to_route('transport.index')->with('success', 'Transport deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');
            return to_route('transport.index')->with('error', $e->getMessage());
        }
    }
}
