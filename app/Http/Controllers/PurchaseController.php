<?php

namespace App\Http\Controllers;

use App\User;
use App\Branch;
use App\Medicine;
use App\Purchase;
use App\Supplier;
use Carbon\Carbon;
use App\PurchaseDetails;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PurchaseController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function index(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));

        if(auth()->user()->role_id <= 2) {
            if($request->ajax()) {
                $data = Purchase::orderBy('id', 'DESC')->get(['purchases.*', 
                        DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
                
                    return DataTables::of($data)
                            ->addColumn('sl', function($data) {
                                return $data->rownum;
                            })
                            ->addColumn('purchase invoice id', function($data) {
                                return $data->purchase_invoice_id;
                            })
                            ->addColumn('purchase date', function($data) {
                                return $data->purchase_date;
                            })
                            ->addColumn('branch', function($data) {
                                return $data->branch->branch_code;
                            })
                            ->addColumn('supplier', function($data) {
                                return $data->supplier->sup_name;
                            })
                            ->addColumn('payment type', function($data) {
                                return $data->payment_type;
                            })
                            ->addColumn('total', function($data) {
                                return $data->purchase_total;
                            })
                            ->addColumn('discount', function($data) {
                                return $data->purchase_discount;
                            })
                            ->addColumn('nettotal', function($data) {
                                return $data->purchase_nettotal;
                            })
                            ->addColumn('paid', function($data) {
                                return $data->purchase_paid;
                            })
                            ->addColumn('due', function($data) {
                                return $data->purchase_due;
                            })
    
                            ->addColumn('action', function($data){
                                $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';
    
                                $a .= '<a href="'. route('admin.purchases.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';
    
                                if(Auth::user()->role_id <= 2) { 
                                    $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.purchase.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                                }
                                
                                return $a;
                            })
    
                            ->rawColumns(['action'])
                            ->make(true);
                            // ->toJson();
            }
        }


        if($request->ajax()) {
            $data = Purchase::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->get(['purchases.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            
                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('purchase invoice id', function($data) {
                            return $data->purchase_invoice_id;
                        })
                        ->addColumn('purchase date', function($data) {
                            return $data->purchase_date;
                        })
                        ->addColumn('branch', function($data) {
                            return $data->branch->branch_code;
                        })
                        ->addColumn('supplier', function($data) {
                            return $data->supplier->sup_name;
                        })
                        ->addColumn('payment type', function($data) {
                            return $data->payment_type;
                        })
                        ->addColumn('total', function($data) {
                            return $data->purchase_total;
                        })
                        ->addColumn('discount', function($data) {
                            return $data->purchase_discount;
                        })
                        ->addColumn('nettotal', function($data) {
                            return $data->purchase_nettotal;
                        })
                        ->addColumn('paid', function($data) {
                            return $data->purchase_paid;
                        })
                        ->addColumn('due', function($data) {
                            return $data->purchase_due;
                        })

                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '<a href="'. route('admin.purchases.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="'. route('admin.purchases.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';
                            
                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.purchase.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                           
                            
                            return $a;
                        })

                        ->rawColumns(['action'])
                        ->make(true);
                        // ->toJson();
        }
     
        return view('admin.purchase.index');
    }


    public function due(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));

        if(auth()->user()->role_id <= 2) {
            if($request->ajax()) {
                $data = Purchase::orderBy('id', 'DESC')->where('purchase_due', '!=', 0.00)->get(['purchases.*', 
                        DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
                
                    return DataTables::of($data)
                            ->addColumn('sl', function($data) {
                                return $data->rownum;
                            })
                            ->addColumn('purchase invoice id', function($data) {
                                return $data->purchase_invoice_id;
                            })
                            ->addColumn('purchase date', function($data) {
                                return $data->purchase_date;
                            })
                            ->addColumn('branch', function($data) {
                                return $data->branch->branch_code;
                            })
                            ->addColumn('supplier', function($data) {
                                return $data->supplier->sup_name;
                            })
                            ->addColumn('payment type', function($data) {
                                return $data->payment_type;
                            })
                            ->addColumn('total', function($data) {
                                return $data->purchase_total;
                            })
                            ->addColumn('discount', function($data) {
                                return $data->purchase_discount;
                            })
                            ->addColumn('nettotal', function($data) {
                                return $data->purchase_nettotal;
                            })
                            ->addColumn('paid', function($data) {
                                return $data->purchase_paid;
                            })
                            ->addColumn('due', function($data) {
                                return $data->purchase_due;
                            })
    
                            ->addColumn('action', function($data){
                                $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';
    
                                $a .= '<a href="'. route('admin.purchases.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';
    
                                if(Auth::user()->role_id <= 2) { 
                                    $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.purchase.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                                }
                                
                                return $a;
                            })
    
                            ->rawColumns(['action'])
                            ->make(true);
                            // ->toJson();
            }
        }


        if($request->ajax()) {
            $data = Purchase::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->where('purchase_due', '!=', 0.00)->get(['purchases.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            
                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('purchase invoice id', function($data) {
                            return $data->purchase_invoice_id;
                        })
                        ->addColumn('purchase date', function($data) {
                            return $data->purchase_date;
                        })
                        ->addColumn('branch', function($data) {
                            return $data->branch->branch_code;
                        })
                        ->addColumn('supplier', function($data) {
                            return $data->supplier->sup_name;
                        })
                        ->addColumn('payment type', function($data) {
                            return $data->payment_type;
                        })
                        ->addColumn('total', function($data) {
                            return $data->purchase_total;
                        })
                        ->addColumn('discount', function($data) {
                            return $data->purchase_discount;
                        })
                        ->addColumn('nettotal', function($data) {
                            return $data->purchase_nettotal;
                        })
                        ->addColumn('paid', function($data) {
                            return $data->purchase_paid;
                        })
                        ->addColumn('due', function($data) {
                            return $data->purchase_due;
                        })

                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '<a href="'. route('admin.purchases.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="'. route('admin.purchases.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';
                            
                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.purchase.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                           
                            
                            return $a;
                        })

                        ->rawColumns(['action'])
                        ->make(true);
                        // ->toJson();
        }
        return view('admin.purchase.due');
    }


    public function create() {
        if(auth()->user()->role_id <= 2) {
            abort('403', 'You can not add any purchase if you are not a dispenser!');
        }
        return view('admin.purchase.create');
    }

 
    public function store(Request $request) {
        if(auth()->user()->role_id <= 2) {
            abort('403', 'Permission Denied!');
        }

        $request->validate([
            'purchase_date' => 'required|date',
            'supplier_id' => 'required',
            'purchase_total' => 'required',
            'purchase_discount' => 'required',
            'purchase_paid' => 'required',
            'purchase_nettotal' => 'required',
            'payment_type' => 'required|string',
        ]);
        
        
        $purchase = new Purchase();
        $input = $request->all();
        $purchase->purchase_invoice_id = $request['purchase_invoice_id'];
        $purchase->purchase_date = $request['purchase_date'];
        $purchase->user_id = auth()->user()->id;
        $purchase->branch_id = auth()->user()->branch->id;
        $purchase->supplier_id = $request['supplier_id'];
        $purchase->purchase_total = $request['purchase_total'];
        $purchase->purchase_discount = $request['purchase_discount'];
        $purchase->purchase_paid = $request['purchase_paid'];
        $purchase->purchase_nettotal = $request['purchase_nettotal'];
        $purchase->purchase_due = $request['purchase_due'];
        $purchase->payment_type = $request['payment_type'];
        $purchase->purchase_note = $request['purchase_note'];
        $purchase->created_at = Carbon::now()->toDateTimeString();
        $purchase->save();

        $j = $purchase->id;

        if($j > 0) {
            for($id = 0; $id < count($input['medicine_id']); $id++) {
                $purchasedetails = new PurchaseDetails();
                $purchasedetails->purchase_id     = $j;
                $purchasedetails->medicine_id     = $input['medicine_id'][$id];
                $medicine = Medicine::where('id', $input['medicine_id'][$id])->firstOrFail();
                $purchasedetails->medicine_name   = $medicine->med_name;
                $purchasedetails->darr_no         = $input['darr_no'][$id];
                $purchasedetails->sell_price      = $input['sell_price'][$id];
                $purchasedetails->medicine_price  = $input['medicine_price'][$id];
                $purchasedetails->stock           = $input['stock'][$id];
                $purchasedetails->stock_original  = $input['stock'][$id];
                $purchasedetails->total_price     = $input['total_price'][$id];
                $purchasedetails->expiry_date     = $input['expiry_date'][$id];
                $purchasedetails->save();
            }
        }

        if($purchasedetails) {
            Session::flash('success', 'Purchase added to the list');
            return redirect()->route('admin.purchases.index');
        }else {
            return back()->with('error', 'Opps! An error occurred!');
        }

    }


    public function show(Purchase $purchase) {
        if(auth()->user()->role_id <= 2) {
            return view('admin.purchase.show', compact('purchase'));    
        }
        if($purchase->branch_id != auth()->user()->branch->id) {
            abort('403', 'Permission Denied!');
        }
        return view('admin.purchase.show', compact('purchase'));
    }


    public function edit(Purchase $purchase) {
        if(auth()->user()->role_id <= 2) {
            abort('403', 'You can not update any purchase if you did not add it!');    
        }

        if($purchase->branch_id != auth()->user()->branch->id) {
            abort('403', 'Permission Denied!');
        }
        return view('admin.purchase.edit', compact('purchase'));
    }

 
    public function update(Request $request, Purchase $purchase) {
        if(auth()->user()->role_id <= 2) {
            abort('403', 'Permission Denied!');
        }
        // return $request->all();
        $request->validate([
            'purchase_date' => 'required|date',
            'supplier_id' => 'required',
            'purchase_total' => 'required',
            'purchase_discount' => 'required',
            'purchase_paid' => 'required',
            'purchase_nettotal' => 'required',
            'payment_type' => 'required|string',
        ]);

        $purchase->purchase_invoice_id = $request['purchase_invoice_id'];
        $purchase->purchase_date = $request['purchase_date'];
        $purchase->supplier_id = $request['supplier_id'];
        $purchase->purchase_total = $request['purchase_total'];
        $purchase->purchase_discount = $request['purchase_discount'];
        $purchase->purchase_paid = $request['purchase_paid'];
        $purchase->purchase_nettotal = $request['purchase_nettotal'];
        $purchase->purchase_due = $request['purchase_due'];
        $purchase->payment_type = $request['payment_type'];
        $purchase->purchase_note = $request['purchase_note'];
        $purchase->updated_at = Carbon::now()->toDateTimeString();
        $purchase->save();

        $j = $purchase->id;
        // $countID = PurchaseDetails::where('purchase_id', $purchase->id)->count();
        $purchaseID = PurchaseDetails::where('purchase_id', $purchase->id)->get();
     
        foreach($purchaseID as $key=>$purchaseItem) {
            $purchaseItem->medicine_id = $request['medicine_id'][$key];
            $medicine = Medicine::where('id', $request['medicine_id'][$key])->firstOrFail();
            $purchaseItem->medicine_name = $medicine->med_name;
            $purchaseItem->darr_no = $request['darr_no'][$key];
            $purchaseItem->sell_price = $request['sell_price'][$key];
            $purchaseItem->medicine_price = $request['medicine_price'][$key];
            $purchaseItem->stock           = $request['stock'][$key];
            $purchaseItem->stock_original  = $request['stock'][$key];
            $purchaseItem->total_price = $request['total_price'][$key];
            $purchaseItem->expiry_date = $request['expiry_date'][$key];
            $purchaseItem->updated_at = Carbon::now()->toDateTimeString();
            $purchaseItem->save();
        }
      

        if($purchaseItem) {
            Session::flash('success', 'Purchase updated successfully');
            return redirect()->route('admin.purchases.index');
        }else {
            return back()->with('error', 'Opps! An error occurred!');
        }
    }

 
    public function destroy(Request $request) {
        if(auth()->user()->role_id <= 2) {
            $destroy = Purchase::destroy($request->id);

            if($destroy){
                return response()->json(['success' => 'Purchase(s) successfully deleted!']);
            }else{
                return response()->json(['error' => 'An error occurred! Please try again!']);
            }
        }

        $purchases = Purchase::whereIn('id', $request->id)->get();
        foreach($purchases as $key=>$purchase) {
            if($purchase->branch_id != auth()->user()->branch->id) {
                abort('403', 'Permission Denied!');
            }
        }
        
        $destroy = Purchase::destroy($request->id);            
        

        if($destroy){
            return response()->json(['success' => 'Purchase(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
        

    }


    public function restore(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }

        $restore = Purchase::whereIn('id', $request->id)->restore();        

        if($restore){
            return response()->json(['success' => 'Purchase(s) successfully restored!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

    public function forceDelete(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }
        
        $forceDelete = Purchase::whereIn('id', $request->id)->forceDelete(); 

        if($forceDelete){
            return response()->json(['success' => 'Purchase(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

    public function supplierautosearch(Request $request) {
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            
            $data = Supplier::select("id", "sup_name", "sup_phone")
                          ->orderBy('id', 'DESC')
                          ->where('sup_status', 1)
                          ->where('sup_name', 'LIKE', "%$search%")
                          ->orWhere('sup_phone', 'LIKE', "%$search%")
                          ->get();
        }

        return response()->json($data);
        
    }

    public function medicineautosearch(Request $request) {
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            
            $data = Medicine::select("id", "med_name")
                          ->orderBy('id', 'DESC')
                          ->where('med_status', 1)
                          ->where('med_name', 'LIKE', "%$search%")
                          ->get();
        }

        return response()->json($data);
        
    }
}



// $a .= '&nbsp;&nbsp;<a href="'. route('admin.purchases.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';