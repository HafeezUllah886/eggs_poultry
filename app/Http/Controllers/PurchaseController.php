<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\products;
use App\Models\purchase;
use App\Models\purchase_details;
use App\Models\purchase_payments;
use App\Models\stock;
use App\Models\transactions;
use App\Models\units;
use App\Models\warehouses;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\PurchasesImport;
use App\Models\auctions;
use App\Models\categories;
use App\Models\category;
use App\Models\makers;
use App\Models\yards;
use Maatwebsite\Excel\Facades\Excel;


class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start = $request->start ?? firstDayOfMonth();
        $end = $request->end ?? lastDayOfMonth();

        $purchases = purchase::whereBetween("date", [$start, $end])->orderby('id', 'desc')->get();

        return view('purchase.index', compact('purchases', 'start', 'end'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $yards = yards::all();
        $auctions = auctions::all();
        $makers = makers::all();
        $categories = category::all();

        $lastpurchase = purchase::orderby('id', 'desc')->first();

        $transporters = accounts::where('type', 'Transporter')->get();

        return view('purchase.create', compact('auctions', 'yards', 'lastpurchase', 'transporters', 'makers', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       try
        {
            $request->validate(
                [
                    'chassis'   =>  'required|unique:purchases,chassis',
                ],
                [
                    'chassis.unique' => 'Chassis No. Already Exist',
                ]
            );
            DB::beginTransaction();
            $ref = getRef();
            $purchase = purchase::create(
                [
                    "transporter_id"        =>  $request->transporter,
                    "year"                  =>  $request->year,
                    "category"              =>  $request->category,
                    "maker"                 =>  $request->maker,
                    "model"                 =>  $request->model,
                    "chassis"               =>  $request->chassis,
                    "loot"                  =>  $request->loot,
                    "yard"                  =>  $request->yard,
                    "date"                  =>  $request->date,
                    "auction"               =>  $request->auction,
                    "price"                 =>  $request->price,
                    "ptax"                  =>  $request->ptax,
                    "afee"                  =>  $request->afee,
                    "atax"                  =>  $request->atax,
                    "transport_charges"     =>  $request->transport_charges,
                    "total"                 =>  $request->total,
                    "recycle"               =>  $request->recycle,
                    "adate"                 =>  $request->adate,
                    "ddate"                 =>  $request->ddate,
                    "number_plate"          =>  $request->number_plate,
                    "nvalidity"             =>  $request->nvalidity,
                    "notes"                 =>  $request->notes,
                    "refID"                 =>  $ref,
                ]
            );

            DB::commit();
            return to_route('purchase.show', $purchase->id)->with('success', "Purchase Created");
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
    public function show(purchase $purchase)
    {
        return view('purchase.view', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $purchase = purchase::find($id);
        $yards = yards::all();
        $auctions = auctions::all();

        $transporters = accounts::where('type', 'Transporter')->get();

        return view('purchase.edit', compact('purchase', 'yards', 'auctions', 'transporters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try
        {
            $request->validate(
                [
                    'chassis'   =>  'required|unique:purchases,chassis,'.$id,
                ],
                [
                    'chassis.unique' => 'Chassis No. Already Exist',
                ]
            );
            DB::beginTransaction();
           
            $purchase = purchase::find($id);
            $purchase->update(
                [
                    "transporter_id"        =>  $request->transporter,
                    "year"                  =>  $request->year,
                    "maker"                 =>  $request->maker,
                    "model"                 =>  $request->model,
                    "chassis"               =>  $request->chassis,
                    "loot"                  =>  $request->loot,
                    "yard"                  =>  $request->yard,
                    "date"                  =>  $request->date,
                    "auction"               =>  $request->auction,
                    "price"                 =>  $request->price,
                    "ptax"                  =>  $request->ptax,
                    "afee"                  =>  $request->afee,
                    "atax"                  =>  $request->atax,
                    "transport_charges"     =>  $request->transport_charges,
                    "total"                 =>  $request->total,
                    "recycle"               =>  $request->recycle,
                    "adate"                 =>  $request->adate,
                    "ddate"                 =>  $request->ddate,
                    "number_plate"          =>  $request->number_plate,
                    "nvalidity"             =>  $request->nvalidity,
                    "notes"                 =>  $request->notes,
                ]
            );

            DB::commit();
            return to_route('purchase.show', $purchase->id)->with('success', "Purchase Updated");
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


    public function import(Request $request)
    {
        $request->validate([
            'excel' => [
                'required',
                'file',
                'mimetypes:text/plain,text/csv,application/csv,text/comma-separated-values,application/excel,application/vnd.ms-excel,application/vnd.msexcel,application/octet-stream,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ],
        ]);
        
        try {
            $file = $request->file('excel');
            $extension = strtolower($file->getClientOriginalExtension());
            
            if (in_array($extension, ['xlsx', 'csv', 'txt'])) {
                $import = new PurchasesImport();
                
                // Import the file
                Excel::import($import, $file);
                
                // Commit the transaction if no errors
                $import->commit();
                
                // Get any errors that occurred during import
                $errors = $import->getErrors();
                $errorCount = $import->getErrorCount();
                
                if ($errorCount > 0) {
                    $errorBag = [];
                    
                    foreach ($errors as $index => $error) {
                        $field = 'row_' . $error['row'];
                        $errorBag[$field] = [
                            'row' => $error['row'],
                            'chassis' => $error['chassis'],
                            'message' => $error['error']
                        ];
                    }
                    
                    return back()
                        ->with('import_errors', $errorBag)
                        ->with('error', "Import completed with $errorCount error(s). Please check the errors below.");
                }
                
                return back()->with("success", "Successfully imported all records");
            } else {
                return back()->with("error", "Invalid file extension");
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
