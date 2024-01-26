<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Invoice;
use App\Customer;
use App\Medicine;
use App\Purchase;
use Carbon\Carbon;
use App\InvoiceDetails;
use App\PharmacySetting;
use App\PurchaseDetails;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

class InvoiceController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function index(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));

        if(auth()->user()->role_id <= 2) {
            if($request->ajax()) {
                $data = Invoice::orderBy('id', 'DESC')->get(['invoices.*', 
                        DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
                
                    return DataTables::of($data)
                            ->addColumn('sl', function($data) {
                                return $data->rownum;
                            })
                            ->addColumn('invoice date', function($data) {
                                return $data->purchase_date;
                            })
                            ->addColumn('branch', function($data) {
                                return $data->branch->branch_code;
                            })
                            ->addColumn('customer', function($data) {
                                return $data->customer->customer_name ?? null;
                            })
                            ->addColumn('payment type', function($data) {
                                return $data->payment_type;
                            })
                            ->addColumn('total', function($data) {
                                return $data->invoice_total;
                            })
                            ->addColumn('discount', function($data) {
                                return $data->invoice_discount;
                            })
                            ->addColumn('paid', function($data) {
                                return $data->invoice_received;
                            })
                            ->addColumn('net total', function($data) {
                                return $data->invoice_nettotal;
                            })
                            ->addColumn('due', function($data) {
                                return $data->invoice_due;
                            })
    
                            ->addColumn('action', function($data){
                                $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';
    
                                $a .= '<a href="'. route('admin.invoices.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';
    
                                if(Auth::user()->role_id <= 2) { 
                                    $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.invoice.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                                }
                                
                                return $a;
                            })
    
                            ->rawColumns(['action'])
                            ->make(true);
                            // ->toJson();
            }
        }


        if($request->ajax()) {
            $data = Invoice::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->get(['invoices.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            
                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('invoice date', function($data) {
                            return $data->purchase_date;
                        })
                        ->addColumn('branch', function($data) {
                            return $data->branch->branch_code;
                        })
                        ->addColumn('customer', function($data) {
                            return $data->customer->customer_name ?? null;
                        })
                        ->addColumn('payment type', function($data) {
                            return $data->payment_type;
                        })
                        ->addColumn('total', function($data) {
                            return $data->invoice_total;
                        })
                        ->addColumn('discount', function($data) {
                            return $data->invoice_discount;
                        })
                        ->addColumn('paid', function($data) {
                            return $data->invoice_received;
                        })
                        ->addColumn('net total', function($data) {
                            return $data->invoice_nettotal;
                        })
                        ->addColumn('due', function($data) {
                            return $data->invoice_due;
                        })

                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '<a href="'. route('admin.invoices.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="'. route('admin.invoices.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';
                            
                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.invoice.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                           
                            
                            return $a;
                        })

                        ->rawColumns(['action'])
                        ->make(true);
                        // ->toJson();
        }
     
        return view('admin.invoice.index');
    }

    public function sale(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));

        if(auth()->user()->role_id <= 2) {
            if($request->ajax()) {
                $data = Invoice::orderBy('id', 'DESC')->get(['invoices.*', 
                        DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
                
                    return DataTables::of($data)
                            ->addColumn('sl', function($data) {
                                return $data->rownum;
                            })
                            ->addColumn('invoice date', function($data) {
                                return $data->purchase_date;
                            })
                            ->addColumn('branch', function($data) {
                                return $data->branch->branch_code;
                            })
                            ->addColumn('customer', function($data) {
                                return $data->customer->customer_name ?? null;
                            })
                            ->addColumn('payment type', function($data) {
                                return $data->payment_type;
                            })
                            ->addColumn('total', function($data) {
                                return $data->invoice_total;
                            })
                            ->addColumn('discount', function($data) {
                                return $data->invoice_discount;
                            })
                            ->addColumn('paid', function($data) {
                                return $data->invoice_received;
                            })
                            ->addColumn('net total', function($data) {
                                return $data->invoice_nettotal;
                            })
                            ->addColumn('due', function($data) {
                                return $data->invoice_due;
                            })
    
                            ->addColumn('action', function($data){
                                $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';
    
                                $a .= '<a href="'. route('admin.invoices.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';
    
                                if(Auth::user()->role_id <= 2) { 
                                    $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.invoice.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                                }
                                
                                return $a;
                            })
    
                            ->rawColumns(['action'])
                            ->make(true);
                            // ->toJson();
            }
        }

        if($request->ajax()) {
            $data = Invoice::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->get(['invoices.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            
                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('invoice date', function($data) {
                            return $data->purchase_date;
                        })
                        ->addColumn('branch', function($data) {
                            return $data->branch->branch_code;
                        })
                        ->addColumn('customer', function($data) {
                            return $data->customer->customer_name ?? null;
                        })
                        ->addColumn('payment type', function($data) {
                            return $data->payment_type;
                        })
                        ->addColumn('total', function($data) {
                            return $data->invoice_total;
                        })
                        ->addColumn('discount', function($data) {
                            return $data->invoice_discount;
                        })
                        ->addColumn('paid', function($data) {
                            return $data->invoice_received;
                        })
                        ->addColumn('net total', function($data) {
                            return $data->invoice_nettotal;
                        })
                        ->addColumn('due', function($data) {
                            return $data->invoice_due;
                        })

                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '<a href="'. route('admin.invoices.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="'. route('admin.invoices.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';
                            
                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.invoice.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                           
                            
                            return $a;
                        })

                        ->rawColumns(['action'])
                        ->make(true);
                        // ->toJson();
        }
     
        return view('admin.invoice.sale');
    }

    public function received(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));
    
        if(auth()->user()->role_id <= 2) {
            if($request->ajax()) {
                $data = Invoice::orderBy('id', 'DESC')->where('invoice_due', 0)->where('invoice_received', '!=', 0)->get(['invoices.*', 
                        DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
                
                    return DataTables::of($data)
                            ->addColumn('sl', function($data) {
                                return $data->rownum;
                            })
                            ->addColumn('invoice date', function($data) {
                                return $data->purchase_date;
                            })
                            ->addColumn('branch', function($data) {
                                return $data->branch->branch_code;
                            })
                            ->addColumn('customer', function($data) {
                                return $data->customer->customer_name ?? null;
                            })
                            ->addColumn('payment type', function($data) {
                                return $data->payment_type;
                            })
                            ->addColumn('total', function($data) {
                                return $data->invoice_total;
                            })
                            ->addColumn('discount', function($data) {
                                return $data->invoice_discount;
                            })
                            ->addColumn('paid', function($data) {
                                return $data->invoice_received;
                            })
                            ->addColumn('net total', function($data) {
                                return $data->invoice_nettotal;
                            })
                            ->addColumn('due', function($data) {
                                return $data->invoice_due;
                            })
    
                            ->addColumn('action', function($data){
                                $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';
    
                                $a .= '<a href="'. route('admin.invoices.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';
    
                                if(Auth::user()->role_id <= 2) { 
                                    $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.invoice.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                                }
                                
                                return $a;
                            })
    
                            ->rawColumns(['action'])
                            ->make(true);
                            // ->toJson();
            }
        }


        if($request->ajax()) {
            $data = Invoice::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->where('invoice_due', 0)->where('invoice_received', '!=', 0)->get(['invoices.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            
                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('invoice date', function($data) {
                            return $data->purchase_date;
                        })
                        ->addColumn('branch', function($data) {
                            return $data->branch->branch_code;
                        })
                        ->addColumn('customer', function($data) {
                            return $data->customer->customer_name ?? null;
                        })
                        ->addColumn('payment type', function($data) {
                            return $data->payment_type;
                        })
                        ->addColumn('total', function($data) {
                            return $data->invoice_total;
                        })
                        ->addColumn('discount', function($data) {
                            return $data->invoice_discount;
                        })
                        ->addColumn('paid', function($data) {
                            return $data->invoice_received;
                        })
                        ->addColumn('net total', function($data) {
                            return $data->invoice_nettotal;
                        })
                        ->addColumn('due', function($data) {
                            return $data->invoice_due;
                        })

                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '<a href="'. route('admin.invoices.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="'. route('admin.invoices.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';
                            
                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.invoice.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                           
                            
                            return $a;
                        })

                        ->rawColumns(['action'])
                        ->make(true);
                        // ->toJson();
        }
     
        return view('admin.invoice.received');
    }

    public function due(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));

        if(auth()->user()->role_id <= 2) {
            if($request->ajax()) {
                $data = Invoice::orderBy('id', 'DESC')->where('invoice_due', '>', 0)->get(['invoices.*', 
                        DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
                
                    return DataTables::of($data)
                            ->addColumn('sl', function($data) {
                                return $data->rownum;
                            })
                            ->addColumn('invoice date', function($data) {
                                return $data->purchase_date;
                            })
                            ->addColumn('branch', function($data) {
                                return $data->branch->branch_code;
                            })
                            ->addColumn('customer', function($data) {
                                return $data->customer->customer_name ?? null;
                            })
                            ->addColumn('payment type', function($data) {
                                return $data->payment_type;
                            })
                            ->addColumn('total', function($data) {
                                return $data->invoice_total;
                            })
                            ->addColumn('discount', function($data) {
                                return $data->invoice_discount;
                            })
                            ->addColumn('paid', function($data) {
                                return $data->invoice_received;
                            })
                            ->addColumn('net total', function($data) {
                                return $data->invoice_nettotal;
                            })
                            ->addColumn('due', function($data) {
                                return $data->invoice_due;
                            })
    
                            ->addColumn('action', function($data){
                                $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';
    
                                $a .= '<a href="'. route('admin.invoices.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';
    
                                if(Auth::user()->role_id <= 2) { 
                                    $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.invoice.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                                }
                                
                                return $a;
                            })
    
                            ->rawColumns(['action'])
                            ->make(true);
                            // ->toJson();
            }
        }


        if($request->ajax()) {
            $data = Invoice::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->where('invoice_due', '>', 0)->get(['invoices.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            
                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('invoice date', function($data) {
                            return $data->purchase_date;
                        })
                        ->addColumn('branch', function($data) {
                            return $data->branch->branch_code;
                        })
                        ->addColumn('customer', function($data) {
                            return $data->customer->customer_name ?? null;
                        })
                        ->addColumn('payment type', function($data) {
                            return $data->payment_type;
                        })
                        ->addColumn('total', function($data) {
                            return $data->invoice_total;
                        })
                        ->addColumn('discount', function($data) {
                            return $data->invoice_discount;
                        })
                        ->addColumn('paid', function($data) {
                            return $data->invoice_received;
                        })
                        ->addColumn('net total', function($data) {
                            return $data->invoice_nettotal;
                        })
                        ->addColumn('due', function($data) {
                            return $data->invoice_due;
                        })

                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '<a href="'. route('admin.invoices.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="'. route('admin.invoices.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';
                            
                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.invoice.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                           
                            
                            return $a;
                        })

                        ->rawColumns(['action'])
                        ->make(true);
                        // ->toJson();
        }
     
        return view('admin.invoice.due');
    }

  
    public function create() {
        if(auth()->user()->role_id <= 2) {
            abort('403', 'You can not add any invoice if you are not a dispenser!');
        }
        return view('admin.invoice.create');
    }

 
    public function store(Request $request) {
        if(auth()->user()->role_id <= 2) {
            abort('403', 'Permission Denied!');
        }

        if($request->filled('customer_name')) {
            $request->validate([
                'customer_name' => 'required|string|max:255',
                'invoice_date' => 'required|date',
                'invoice_total' => 'required',
                'invoice_received' => 'required',
                'invoice_due' => 'required',
                'payment_type' => 'required|string',
            ]);
    
            $createCustomer = Customer::create([
                'customer_name' => $request['customer_name'],
                'user_id' => auth()->user()->id,
                'customer_phone' => $request['customer_phone'],
                'customer_email' => $request['customer_email'],
                'customer_address' => $request['customer_address'],
                'created_at' => Carbon::now()->toDateTimeString(),
            ]);

            $invoice = new Invoice();
            $input = $request->all();
            $invoice->branch_id = auth()->user()->branch->id;
            $invoice->user_id = auth()->user()->id;
            $invoice->customer_id = $createCustomer->id;
            $invoice->invoice_date = $request['invoice_date'];
            $invoice->invoice_total = $request['invoice_total'];
            $invoice->invoice_discount = $request['invoice_discount'];
            $invoice->invoice_received = $request['invoice_received'];
            $invoice->invoice_nettotal = $request['invoice_nettotal'];
            $invoice->invoice_due = $request['invoice_due'];
            $invoice->payment_type = $request['payment_type'];
            $invoice->invoice_note = $request['invoice_note'];
            $invoice->created_at = Carbon::now()->toDateTimeString();
            $invoice->save();

            $invoiceID = $invoice->id;

        }else {
            $request->validate([
                'invoice_date' => 'required|date',
                'customer_id' => 'required',
                'invoice_total' => 'required',
                'invoice_received' => 'required',
                'invoice_due' => 'required',
                'payment_type' => 'required|string',
            ]);
            
            $invoice = new Invoice();
            $input = $request->all();            
            $invoice->branch_id = auth()->user()->branch->id;
            $invoice->user_id = auth()->user()->id;
            $invoice->customer_id = $request['customer_id'];
            $invoice->invoice_date = $request['invoice_date'];
            $invoice->invoice_total = $request['invoice_total'];
            $invoice->invoice_discount = $request['invoice_discount'];
            $invoice->invoice_received = $request['invoice_received'];
            $invoice->invoice_nettotal = $request['invoice_nettotal'];
            $invoice->invoice_due = $request['invoice_due'];
            $invoice->payment_type = $request['payment_type'];
            $invoice->invoice_note = $request['invoice_note'];
            $invoice->created_at = Carbon::now()->toDateTimeString();
            $invoice->save();

            $invoiceID = $invoice->id;
        }
            

        $purchases = Purchase::where('branch_id', auth()->user()->branch->id)->get();
        $now = Carbon::parse(Carbon::today())->format('y-m-d');


        if($invoiceID > 0) {
            for($id = 0; $id < count($input['medicine_id']); $id++) {
                $invoicedetails = new InvoiceDetails();
                $invoicedetails->invoice_id      = $invoiceID;
                $invoicedetails->medicine_id     = $input['medicine_id'][$id];
                $medicine = Medicine::where('id', $input['medicine_id'][$id])->firstOrFail();
                $invoicedetails->medicine_name   = $medicine->med_name;
                $invoicedetails->sell_price      = $input['sell_price'][$id];
                $invoicedetails->qty             = $input['qty'][$id];
                $invoicedetails->total_price     = $input['total_price'][$id];
                $invoicedetails->save();

                $a = 0;
                $j = 0; 
                $data = [];
                foreach($purchases as $key=>$purchase) {
                    $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                                    ->where('expiry_date', '>' , $now)
                                                    ->where('stock', '>=', 1)
                                                    ->where('medicine_id', $invoicedetails->medicine_id)
                                                    ->get();
                    for($i = 0; $i < count($array[$a]); $i++) {
                        $data[$j] = $array[$a][$i];
                        $j = $j + 1;
                    }            
                }
        
                $count = count($data);
        
                $stockQuantity = 0;
                for($i = 0; $i < $count; $i++) {
                    $stockQuantity += $data[$i]->stock;
                }
        
                $qty = $invoicedetails->qty;
                for($i = 0; $i < $count; $i++) {
                    $stockUpdateI = new PurchaseDetails();
                    $stockUpdateI = PurchaseDetails::where('id', $data[$i]->id)
                                                    ->where('medicine_id', $data[$i]->medicine_id)
                                                    ->firstOrFail();
                    
                    $setStock = $data[$i]->stock;
                    $setStockSave = $setStock; 
        
                    $setStock = $setStock - $qty ; 
        
                    if($setStockSave > $qty) {
                        $stockUpdateI->stock = $setStock ;
                        $stockUpdateI->save();
                        break;
        
                    }elseif($setStockSave == $qty) {
                        $stockUpdateI->stock = 0;
                        $stockUpdateI->save();
                        break;
        
                    }elseif($setStockSave < $qty) {
                        $negativeStockAdd = ($setStock-$setStock) + (-$setStock); // 10
                        $stockUpdateI->stock = 0;
                        $qty = $negativeStockAdd;
                        $stockUpdateI->save();
                        if($qty > 0) {
                            continue;
                        }else {
                            break;
                        }
                    }
                }
                
            }
        }

        if($invoicedetails) {
            Session::flash('success', 'Invoice added to the list');
            return redirect()->route('admin.invoices.show', $invoiceID);
        }else {
            return back()->with('error', 'Opps! An error occurred!');
        }

    }

  
    public function show(Invoice $invoice) {
        $settings = PharmacySetting::active()->where('id', 1)->firstOrFail();
        $branch = auth()->user()->branch;

        if(auth()->user()->role_id <= 2) {
            return view('admin.invoice.show', compact('invoice', 'settings'));    
        }
        if($invoice->branch_id != auth()->user()->branch->id) {
            abort('403', 'Permission Denied!');
        }
        return view('admin.invoice.show', compact('invoice', 'settings'));
    }

  
    public function edit(Invoice $invoice) {
        if(auth()->user()->role_id <= 2) {
            abort('403', 'You can not update any invoice if you did not add it!');
        }
        if($invoice->branch_id != auth()->user()->branch->id) {
            abort('403', 'Permission Denied!');
        }

        $purchases = Purchase::where('branch_id', auth()->user()->branch->id)->get();
        $a = 0;
        $j = 0; 
        $data = [];
        $stocks = 0;
        $now = Carbon::parse(Carbon::today())->format('y-m-d');
        foreach($purchases as $key=>$purchase) {
            $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                        ->where('expiry_date', '>' , $now)
                                        ->where('stock', '>=', 1)
                                        ->get();
            
            for($i = 0; $i < count($array[$a]); $i++) {
                $data[$j] = $array[$a][$i];
                $j = $j + 1;
            }  
        } 

        $wet = [];
        foreach($invoice->invoiceDetails as $key=>$invoicedetail) {
            $wet[$key] = $invoicedetail->medicine_id;
        }

        $count = count($data);

        $stocks = [];
        for($i = 0; $i < $count; $i++) {
            $stock = $data[$i]->stock;
            $stockOne = $data[$i]->stock;
            for($j = $i + 1; $j < $count; $j++) {
                if($data[$i]->medicine_id == $data[$j]->medicine_id) {
                    $stock += $data[$j]->stock;            
                    $stocks[$data[$i]->medicine_id] = $stock;               
                    $data[$j]->stock = $stock;   
                    if (($key = array_search($data[$i], $data)) !== false) {
                        unset($data[$key]);
                        break;
                    }         
                }   
            }            
        }

        $data2 = array_values($data);
        
        $count = count($data2);
        $countWet = count($wet);

        $data = [];

        for($i = 0; $i < $countWet; $i++) {
            for($j = 0; $j < $count; $j++) {
                if($wet[$i] == $data2[$j]->medicine_id) {
                    $data[] = $data2[$j]->stock;
                } 
            }
        }

        return view('admin.invoice.edit', compact('invoice', 'data'));
    }

  
    public function update(Request $request, Invoice $invoice) {
        if(auth()->user()->role_id <= 2) {
            abort('403', 'Permission Denied!');
        }

        $request->validate([
            'invoice_date' => 'required|date',
            'customer_id' => 'required',
            'invoice_total' => 'required',
            'payment_type' => 'required|string',
        ]);

    
        $invoice->customer_id = $request['customer_id'];
        $invoice->invoice_date = $request['invoice_date'];
        $invoice->invoice_total = $request['invoice_total'];
        $invoice->invoice_discount = $request['invoice_discount'];
        $invoice->invoice_received = $request['invoice_received'];
        $invoice->invoice_nettotal = $request['invoice_nettotal'];
        $invoice->invoice_due = $request['invoice_due'];
        $invoice->payment_type = $request['payment_type'];
        $invoice->invoice_note = $request['invoice_note'];
        $invoice->updated_at = Carbon::now()->toDateTimeString();
        $invoice->save();

        $saveInvoiceID = $invoice->id;
        $invoiceID = InvoiceDetails::where('invoice_id', $invoice->id)->get();

        $purchases = Purchase::where('branch_id', auth()->user()->branch->id)->get();
        $now = Carbon::parse(Carbon::today())->format('y-m-d');

        foreach($invoiceID as $key=>$invoiceItem) {
            $medicineIdPrevious = $invoiceItem->medicine_id;
            $invoiceItem->medicine_id = $request['medicine_id'][$key];
            $medicine = Medicine::where('id', $request['medicine_id'][$key])->firstOrFail();
            $invoiceItem->medicine_name = $medicine->med_name;
            $invoiceItem->sell_price = $request['sell_price'][$key];
            $qunatityPrevious = $invoiceItem->qty;
            $invoiceItem->qty = $request['qty'][$key];
            $invoiceItem->total_price = $request['total_price'][$key];
            $invoiceItem->updated_at = Carbon::now()->toDateTimeString();
            $invoiceItem->save();

            if($invoiceItem->medicine_id == $medicineIdPrevious) {
                $a = 0;
                $j = 0; 
                $data = [];
                $now = Carbon::parse(Carbon::today())->format('y-m-d');
                foreach($purchases as $key=>$purchase) {
                    $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                                ->where('expiry_date', '>' , $now)
                                                ->where('stock', '>=', 1)
                                                ->where('medicine_id', $invoiceItem->medicine_id)
                                                ->get();
                    
                    for($i = 0; $i < count($array[$a]); $i++) {
                        $data[$j] = $array[$a][$i];
                        $j = $j + 1;
                    }  
                } 
                // return $data;
                // return $data[0]->stock;
                $count = count($data);

                $setStock = 0;
                $negativeStockAdd = 0;

                if($qunatityPrevious < $invoiceItem->qty) {
                    $qty = $invoiceItem->qty - $qunatityPrevious;
                }elseif($qunatityPrevious > $invoiceItem->qty) {
                    $qty = $qunatityPrevious - $invoiceItem->qty;
                }else {
                    $qty = 0;
                }

                $countData = count($data);

                
                if($qunatityPrevious < $invoiceItem->qty) {
                    for($i = 0; $i < $count; $i++) {
                        $stockUpdateI = new PurchaseDetails();
                        $stockUpdateI = PurchaseDetails::orderBy('id', 'ASC')
                                                        ->where('purchase_id', $data[$i]->purchase_id)  
                                                        ->where('medicine_id', $data[$i]->medicine_id)
                                                        ->where('stock', '>=', 1)
                                                        ->firstOrFail();
                        
                        $setStock = $data[$i]->stock;
                        $setStockSave = $setStock;

                        if($setStockSave > $qty) {
                            $stockUpdateI->stock = $data[$i]->stock - $qty ;
                            $stockUpdateI->save();
                            break;
                
                        }elseif($setStockSave == $qty) {
                            $stockUpdateI->stock = 0;
                            $stockUpdateI->save();
                            break;
                
                        }elseif($setStockSave < $qty) {
                            $negativeStockAdd = $qty - $data[$i]->stock;
                            $qty = $negativeStockAdd;
                            $setStock = 0;
                            $stockUpdateI->stock = 0;
                            $stockUpdateI->save();
                            if($qty > 0) {
                                continue;
                            }else {
                                break;
                            }
                        }
                    }
                }elseif($qunatityPrevious > $invoiceItem->qty) {
                    $stockUpdateI = new PurchaseDetails();
                    $stockUpdateI = PurchaseDetails::where('id', $data[$count-1]->id)
                                                    ->where('purchase_id', $data[$count-1]->purchase_id)  
                                                    ->where('medicine_id', $data[$count-1]->medicine_id)
                                                    ->where('stock', '>=', 1)
                                                    ->firstOrFail();

                    $stockUpdateI->stock =  $data[$count-1]->stock + $qty;
                    $stockUpdateI->save();
                }
            }else {
                $a = 0;
                $j = 0; 
                $data = [];
                foreach($purchases as $key=>$purchase) {
                    $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                                    ->where('expiry_date', '>' , $now)
                                                    ->where('stock', '>=', 1)
                                                    ->where('medicine_id', $medicineIdPrevious)
                                                    ->get();
                    for($i = 0; $i < count($array[$a]); $i++) {
                        $data[$j] = $array[$a][$i];
                        $j = $j + 1;
                    }            
                }
        
                $count = count($data);

        
                $qty = $qunatityPrevious;
                $stockUpdateI = new PurchaseDetails();
                $stockUpdateI = PurchaseDetails::where('id', $data[$count-1]->id)
                                                ->where('purchase_id', $data[$count-1]->purchase_id)  
                                                ->where('medicine_id', $data[$count-1]->medicine_id)
                                                ->firstOrFail();

                $stockUpdateI->stock =  $data[$count-1]->stock + $qty;
                $stockUpdateI->save();


                // ********************************
                $a = 0;
                $j = 0; 
                $data = [];
                foreach($purchases as $key=>$purchase) {
                    $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                                ->where('expiry_date', '>' , $now)
                                                ->where('stock', '>=', 1)
                                                ->where('medicine_id', $invoiceItem->medicine_id)
                                                ->get();
                    
                    for($i = 0; $i < count($array[$a]); $i++) {
                        $data[$j] = $array[$a][$i];
                        $j = $j + 1;
                    }  
                } 
                // return $data;
                $count = count($data);

                $setStock = 0;
                $negativeStockAdd = 0;

                $qty = $invoiceItem->qty;

                $countData = count($data);

                
                for($i = 0; $i < $count; $i++) {
                    $stockUpdateI = new PurchaseDetails();
                    $stockUpdateI = PurchaseDetails::where('id', $data[$i]->id)
                                                    ->where('medicine_id', $data[$i]->medicine_id)
                                                    ->firstOrFail();
                    
                    $setStock = $data[$i]->stock;
                    $setStockSave = $setStock; 
        
                    $setStock = $setStock - $qty ; 
        
                    if($setStockSave > $qty) {
                        $stockUpdateI->stock = $setStock ;
                        $stockUpdateI->save();
                        break;
        
                    }elseif($setStockSave == $qty) {
                        $stockUpdateI->stock = 0;
                        $stockUpdateI->save();
                        break;
        
                    }elseif($setStockSave < $qty) {
                        $negativeStockAdd = ($setStock-$setStock) + (-$setStock); // 10
                        $stockUpdateI->stock = 0;
                        $qty = $negativeStockAdd;
                        $stockUpdateI->save();
                        if($qty > 0) {
                            continue;
                        }else {
                            break;
                        }
                    }
                }

               
            }

        }

        if($invoiceItem) {
            Session::flash('success', 'Invoice updated successfully');
            return redirect()->route('admin.invoices.show', $saveInvoiceID);
        }else {
            return back()->with('error', 'Opps! An error occurred!');
        }
    }

  
    public function destroy(Request $request) {
        if(auth()->user()->role_id <= 2) {
            $destroy = Invoice::destroy($request->id);

            if($destroy){
                return response()->json(['success' => 'Purchase(s) successfully deleted!']);
            }else{
                return response()->json(['error' => 'An error occurred! Please try again!']);
            }
        }

        $invoices = Invoice::whereIn('id', $request->id)->get();
        foreach($invoices as $key=>$invoice) {
            if($invoice->branch_id != auth()->user()->branch->id) {
                abort('403', 'Permission Denied!');
            }
        }

        $destroy = Invoice::destroy($request->id);            

        if($destroy){
            return response()->json(['success' => 'Invoice(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

    public function restore(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }

        $restore = Invoice::whereIn('id', $request->id)->restore();        

        if($restore){
            return response()->json(['success' => 'Invoice(s) successfully restored!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

    public function forceDelete(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }
        
        $forceDelete = Invoice::whereIn('id', $request->id)->forceDelete(); 

        if($forceDelete){
            return response()->json(['success' => 'Invoice(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }


    public function customerautosearch(Request $request) {
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            
            $data = Customer::select("id", "customer_name", "customer_phone", "customer_email")
                          ->orderBy('id', 'DESC')
                          ->where('customer_status', 1)
                          ->where('customer_name', 'LIKE', "%$search%")
                          ->orWhere('customer_phone', 'LIKE', "%$search%")
                          ->orWhere('customer_email', 'LIKE', "%$search%")
                          ->get();
        }

        return response()->json($data);
        
    }

    public function medicineautosearch(Request $request) {
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            $purchases = Purchase::where('branch_id', auth()->user()->branch->id)->get();
            $a = 0;
            $j = 0; 
            $now = Carbon::parse(Carbon::today())->format('y-m-d');
            foreach($purchases as $key=>$purchase) {
                $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                                ->where('stock', '!=', 0)
                                                ->where('medicine_name', 'LIKE', "%$search%")
                                                ->where('expiry_date', '>' , $now)
                                                ->get();
                for($i = 0; $i < count($array[$a]); $i++) {
                    $data[$j] = $array[$a][$i];
                    $j = $j + 1;
                }            
            }      
        }
        return response()->json($data);
        
    }


    public function medicineinfo(Request $request) {
        $purchases = Purchase::where('branch_id', auth()->user()->branch->id)->get();
        $a = 0;
        $j = 0; 
        $now = Carbon::parse(Carbon::today())->format('y-m-d');
        foreach($purchases as $key=>$purchase) {
            $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                            ->where('stock', '!=', 0)
                                            ->where('medicine_id', $request->id)
                                            ->where('expiry_date', '>' , $now)
                                            ->get();
            for($i = 0; $i < count($array[$a]); $i++) {
                $data[$j] = $array[$a][$i];
                $j = $j + 1;
            }            
        }

        $count = count($data);

        $a = 0;
        $data2 = [];
        for($i = 0; $i < $count; $i++) {
            $a += $data[$i]->stock;
        }

        $data2[0] = $a;
        $data2[1] = $data[count($data) - 1]->sell_price;

        return response()->json($data2);
    }
}




