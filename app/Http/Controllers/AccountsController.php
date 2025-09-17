<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\branches;
use App\Models\method_transactions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountsController extends Controller
{
    public function index($filter)
    {
        if(Auth()->user()->role == "Admin")
        {
            $accounts = accounts::where('type', $filter)->orderBy('title', 'asc')->get();
        }
        else
        {
            $accounts = accounts::where('type', $filter)->where('branchID', Auth()->user()->branchID)->orderBy('title', 'asc')->get();
        }
        if($filter == "Other")
        {
            $accounts = accounts::Other()->get();
        }

        return view('finance.accounts.index', compact('accounts', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if(Auth()->user()->role == "Admin")
        {
            $branches = branches::all();
        }
        else
        {
            $branches = branches::where('id', Auth()->user()->branchID)->get();
        }

        return view('finance.accounts.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required',
            ],
            [
                'title.required' => "Please Enter Account Title",
            ]
        );

        try
        {
            DB::beginTransaction();

            $account = accounts::create(
                [
                    'title' => $request->title,
                    'type' => $request->type ?? "Cash",
                    'category' => $request->category,
                    'contact' => $request->contact,
                    'address' => $request->address,
                    'currency' => $request->currency,
                    'branch_id'  => $request->branch ?? Auth()->user()->branchID,
                ]
            );
               
           DB::commit();
           return back()->with('success', "Account Created Successfully");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id, $from, $to, $orderbooker = 0)
    {
        $orderbooker = $orderbooker ?? 0;
        $account = accounts::find($id);

        $transactions = transactions::where('accountID', $id)->whereBetween('date', [$from, $to]);
        if($orderbooker != 0)
        {
            $transactions = $transactions->where('orderbookerID', $orderbooker);
        }
        $transactions = $transactions->orderBy('date', 'asc')->orderBy('refID', 'asc')->get();

        $pre_cr = transactions::where('accountID', $id)->whereDate('date', '<', $from);
        if($orderbooker != 0)
        {
            $pre_cr = $pre_cr->where('orderbookerID', $orderbooker);
        }
        $pre_cr = $pre_cr->sum('cr');

        $pre_db = transactions::where('accountID', $id)->whereDate('date', '<', $from);
        if($orderbooker != 0)
        {
            $pre_db = $pre_db->where('orderbookerID', $orderbooker);
        }
        $pre_db = $pre_db->sum('db');

        $pre_balance = $pre_cr - $pre_db;

        
        if($orderbooker != 0)
        {
           $cur_balance = getAccountBalanceOrderbookerWise($id, $orderbooker);
        }
        else
        {
            $cur_balance = getAccountBalance($id);
        }

        return view('Finance.accounts.statment', compact('account', 'transactions', 'pre_balance', 'cur_balance', 'from', 'to', 'orderbooker'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(accounts $account)
    {
        $areas = area::where('branchID', Auth()->user()->branchID)->get();
        return view('Finance.accounts.edit', compact('account', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, accounts $account)
    {
        $request->validate(
            [
                'title' => "required",
            ],
            [
                'title.required' => "Please Enter Account Title",
            ]
        );
        $account = accounts::find($request->accountID)->update(
            [
                'title' => $request->title,
                'title_urdu' => $request->title_urdu,
                'category' => $request->category,
                'contact' => $request->contact ?? null,
                'c_type' => $request->c_type,
                'areaID' => $request->area ?? 1,
                'address' => $request->address ?? null,
                'address_urdu' => $request->address_urdu ?? null,
                'credit_limit' => $request->limit ?? 0
            ]
        );

        return redirect()->route('accountsList', $request->type)->with('success', "Account Updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(accounts $accounts)
    {
        //
    }

    public function status($id)
    {
        $account = accounts::find($id);
        if($account->status == "Active")
        {
           $status = "Inactive";
        }
        else
        {
            $status = "Active";
        }

        $account->update(
            [
                'status' => $status,
            ]
        );

        return back()->with('success', "Status Updated");
    }

    public function methodStatement($user, $method, $from, $to)
    {

        $transactions = method_transactions::where('userID', $user)->where('method', $method)->whereBetween('date', [$from, $to])->orderBy('date', 'asc')->orderBy('refID', 'asc')->get();
        $pre_cr = method_transactions::where('userID', $user)->where('method', $method)->whereDate('date', '<', $from)->sum('cr');
        $pre_db = method_transactions::where('userID', $user)->where('method', $method)->whereDate('date', '<', $from)->sum('db');
        $pre_balance = $pre_cr - $pre_db;

        $cur_cr = method_transactions::where('userID', $user)->where('method', $method)->sum('cr');
        $cur_db = method_transactions::where('userID', $user)->where('method', $method)->sum('db');

        $cur_balance = $cur_cr - $cur_db;
        $user = User::find($user);

        return view('Finance.my_balance.method_statment', compact('method', 'transactions', 'pre_balance', 'cur_balance', 'from', 'to', 'user'));
    }
}
