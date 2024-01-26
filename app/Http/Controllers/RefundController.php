<?php

namespace App\Http\Controllers;

use App\Refund;
use App\Invoice;
use App\Medicine;
use App\Purchase;
use Carbon\Carbon;
use App\RefundDetails;
use App\PharmacySetting;
use App\PurchaseDetails;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RefundController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));

        if(auth()->user()->role_id <= 2) {
            if($request->ajax()) {
                $data = Refund::orderBy('id', 'DESC')->get(['refunds.*', 
                        DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
                
                    return DataTables::of($data)
                            ->addColumn('sl', function($data) {
                                return $data->rownum;
                            })
                            ->addColumn('refund date', function($data) {
                                return $data->refund_date;
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
                                return $data->refund_total;
                            })
                            ->addColumn('cut', function($data) {
                                return $data->refund_cut;
                            })
                            ->addColumn('net total', function($data) {
                                return $data->refund_nettotal;
                            })
                            ->addColumn('paid', function($data) {
                                return $data->refund_paid;
                            })
    
                            ->addColumn('action', function($data){
                                $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';
    
                                $a .= '<a href="'. route('admin.refunds.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';
    
                                if(Auth::user()->role_id <= 2) { 
                                    $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.refund.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                                }
                                
                                return $a;
                            })
    
                            ->rawColumns(['action'])
                            ->make(true);
                            // ->toJson();
            }
        }


        if($request->ajax()) {
            $data = Refund::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->get(['refunds.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            
                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('refund date', function($data) {
                            return $data->refund_date;
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
                            return $data->refund_total;
                        })
                        ->addColumn('cut', function($data) {
                            return $data->refund_cut;
                        })
                        ->addColumn('net total', function($data) {
                            return $data->refund_nettotal;
                        })
                        ->addColumn('paid', function($data) {
                            return $data->refund_paid;
                        })

                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '<a href="'. route('admin.refunds.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="'. route('admin.refunds.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';
                            
                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.refund.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                           
                            
                            return $a;
                        })

                        ->rawColumns(['action'])
                        ->make(true);
                        // ->toJson();
        }
     
        return view('admin.refund.index');
    }

    public function add() {
        if(auth()->user()->role_id <= 2) {
            abort('403', 'You can not add any refund if you are not a dispenser!');
        }
        return view('admin.refund.add');
    }

    public function create(Request $request) {
        if(auth()->user()->role_id <= 2) {
            abort('403', 'Permission Denied!');
        }

        $request->validate([
            'invoice_id' => 'required',
        ]);
        
        $search = $request->invoice_id;
        $invoice = Invoice::orderBy('id', 'DESC')
                            ->where('invoice_status', 1)
                            ->where('id', $search)
                            ->firstOrFail();

        $invoiceDetails = $invoice->invoiceDetails;


        if(auth()->user()->role_id <= 2) {
            return view('admin.refund.create', compact('invoice', 'invoiceDetails'));
        }

        if($invoice->branch_id != auth()->user()->branch->id) {
            abort(403, 'Permission Denied!');
        }


        return view('admin.refund.create', compact('invoice', 'invoiceDetails'));

    }


    public function store(Request $request) {
        if(auth()->user()->role_id <= 2) {
            abort('403', 'Permission Denied!');
        }

        $request->validate([
            'refund_date' => 'required|date',
            'invoice_id' => 'required',
            'customer_id' => 'required',
            'payment_type' => 'required|string',
            'refund_total' => 'required',
            'refund_cut' => 'required',
            'refund_paid' => 'required',
            'refund_nettotal' => 'required',
            'medicine_id' => 'required',
            'medicine_name' => 'required',
            'sold_qty' => 'required',
            'refund_qty' => 'required',
            'sell_price' => 'required',
            'total_price' => 'required',
        ]);

        $refund = new Refund();
        $input = $request->all();            
        $refund->invoice_id = $request['invoice_id'];
        $refund->user_id = auth()->user()->id;
        $refund->branch_id = auth()->user()->branch->id;
        $refund->customer_id = $request['customer_id'];
        $refund->refund_date = $request['refund_date'];
        $refund->payment_type = $request['payment_type'];
        $refund->refund_total = $request['refund_total'];
        $refund->refund_cut = $request['refund_cut'];
        $refund->refund_paid = $request['refund_paid'];
        $refund->refund_nettotal = $request['refund_nettotal'];
        $refund->refund_note = $request['refund_note'];
        $refund->created_at = Carbon::now()->toDateTimeString();
        $refund->save();

        $refundID = $refund->id;

        $purchases = Purchase::where('branch_id', auth()->user()->branch->id)->get();
        
        if($refundID > 0) {
            for($id = 0; $id < count($input['medicine_id']); $id++) {
                if($input['refund_qty'][$id] == 0 || $input['total_price'][$id] == 0) {
                    continue;
                }else {
                    $refunddetails = new RefundDetails();
                    $refunddetails->refund_id         = $refundID;
                    $refunddetails->medicine_id       = $input['medicine_id'][$id];
                    $refunddetails->medicine_name     = $input['medicine_name'][$id];
                    $refunddetails->sold_qty          = $input['sold_qty'][$id];
                    $refunddetails->refund_qty        = $input['refund_qty'][$id];
                    $refunddetails->sell_price        = $input['sell_price'][$id];
                    $refunddetails->total_price       = $input['total_price'][$id];
                    $refunddetails->save();

                    $a = 0;
                    $j = 0; 
                    $data = [];
                    foreach($purchases as $key=>$purchase) {
                        $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                                ->where('medicine_id', $refunddetails->medicine_id)
                                                ->get();
                        
                        for($i = 0; $i < count($array[$a]); $i++) {
                            $data[$j] = $array[$a][$i];
                            $j = $j + 1;
                        }  
                    } 

                    $count = count($data);

                    $qty = $refunddetails->refund_qty;

                    $stockUpdateI = new PurchaseDetails();
                    $stockUpdateI = 
                    PurchaseDetails::where('id', $data[$count - 1]->id)
                                    ->where('purchase_id', $data[$count - 1]->purchase_id)  
                                    ->where('medicine_id', $refunddetails->medicine_id)
                                    ->firstOrFail();

                    $stockUpdateI->stock =  $data[$count - 1]->stock + $qty;
                    $stockUpdateI->save();
                }
            }
        }

        if($refunddetails) {
            Session::flash('success', 'Refund added to the list');
            return redirect()->route('admin.refunds.index');
        }else {
            return back()->with('error', 'Opps! An error occurred!');
        }
    }


    public function show(Refund $refund) {
        $settings = PharmacySetting::active()->where('id', 1)->firstOrFail();

        if(auth()->user()->role_id <= 2) {
            return view('admin.refund.show', compact('refund', 'settings'));    
        }
        if($refund->branch_id != auth()->user()->branch->id) {
            abort('403', 'Permission Denied!');
        }
        return view('admin.refund.show', compact('refund', 'settings'));
    }


    public function edit(Refund $refund) {
        if(auth()->user()->role_id <= 2) {
            abort('403', 'You can not update any refund if you did not add it!');
        }

        $refundDetails = $refund->refundDetails;

        if($refund->branch_id != auth()->user()->branch->id) {
            abort(403, 'Permission Denied!');
        }

        return view('admin.refund.edit', compact('refund', 'refundDetails'));
    }


    public function update(Request $request, Refund $refund) {
        if(auth()->user()->role_id <= 2) {
            abort('403', 'Permission Denied!');
        }

        $request->validate([
            'refund_date' => 'required|date',
            'refund_total' => 'required',
            'refund_cut' => 'required',
            'refund_nettotal' => 'required',
            'refund_paid' => 'required',
            'payment_type' => 'required|string',
            'medicine_id' => 'required',
            'medicine_name' => 'required',
            'sold_qty' => 'required',
            'refund_qty' => 'required',
            'sell_price' => 'required',
            'total_price' => 'required',
        ]);

    
        // $refund->customer_id = $request['customer_id'];
        $refund->refund_date = $request['refund_date'];
        $refund->refund_total = $request['refund_total'];
        $refund->refund_cut = $request['refund_cut'];
        $refund->refund_nettotal = $request['refund_nettotal'];
        $refund->refund_paid = $request['refund_paid'];
        $refund->payment_type = $request['payment_type'];
        $refund->refund_note = $request['refund_note'];
        $refund->updated_at = Carbon::now()->toDateTimeString();
        $refund->save();

        $j = $refund->id;
        $countID = RefundDetails::where('refund_id', $refund->id)->count();
        $refundID = RefundDetails::where('refund_id', $refund->id)->get();
     
        $purchases = Purchase::where('branch_id', auth()->user()->branch->id)->get();

        $a = 0;
        foreach($purchases as $key=>$purchase) {
            $array[$a++] = $purchase->purchaseDetails->pluck('purchase_id');
        }
        $count = $a;

        foreach($refundID as $key=>$refundItem) {
            $refundItem->medicine_id    = $request['medicine_id'][$key];
            $refundItem->medicine_name  = $request['medicine_name'][$key];
            $refundItem->sold_qty       = $request['sold_qty'][$key];
            $refundQtyPrevious          = $refundItem->refund_qty;
            $refundItem->refund_qty     = $request['refund_qty'][$key];
            $refundItem->sell_price     = $request['sell_price'][$key];
            $refundItem->total_price    = $request['total_price'][$key];
            $refundItem->save();

            $data = [];
            for($i = 0; $i < $count; $i = $i + 1) {
                for($j = 0; $j < count($array[$i]); $j = $j + 1) {
                    $data[$i] = PurchaseDetails::where('purchase_id', $array[$i][$j])
                                                // ->where('stock', '>=', 1)
                                                ->where('medicine_id', $refundItem->medicine_id)
                                                ->select('id', 'purchase_id', 'medicine_id', 'sell_price', 'stock')
                                                ->get();
                }
            }


            // return $data;
            $stockUpdate = [];
            for($i = 0; $i < $count; $i = $i + 1) {
                for($j = 0; $j < count($data[$i]); $j = $j + 1) {
                    $stockUpdate[$i] = PurchaseDetails::where('purchase_details_status', 1)
                                                    ->where('purchase_id', $data[$i][$j]->purchase_id)  
                                                    ->where('medicine_id', $data[$i][$j]->medicine_id)
                                                    ->where('stock', $data[$i][$j]->stock)
                                                    ->get();
                
                }
        
            }

            $data3 = [];
            $k = 0;
            foreach($stockUpdate as $key=>$stockdetails) {
                $data3[$k] = $stockdetails;
                $k = $k + 1;
            }

            $data4 = [];
            for($i = 0; $i < count($data3); $i = $i + 1) {
                $data4[$i] = $data3[$i][0];
                // $purchaseId[$i] = $data3[$i][0]->purchase_id;
                // $medicineId = $data3[$i][0]->medicine_id;
                // $stockId[$i] = $data3[$i][0]->stock;
                // $purchase_details_status = $data3[$i][0]->purchase_details_status;
            }

            $setStock = 0;
            $negativeStockAdd = 0;
            $countData4 = count($data4);


            if($countData4 < 2) {
                $stockUpdateI = new PurchaseDetails();
                $stockUpdateI = PurchaseDetails::where('purchase_details_status', 1)
                                                ->where('purchase_id', $data4[0]->purchase_id)  
                                                ->where('medicine_id', $data4[0]->medicine_id)
                                                ->where('stock', $data4[0]->stock)
                                                ->firstOrFail();

                $setStock = $data4[0]->stock;
                $setStockSave = $setStock; 

                $setStock = $setStock - $refundQtyPrevious + $refundItem->refund_qty; 
                $stockUpdateI->stock = $setStock;
                $stockUpdateI->save();
            }else {
                for($i = $countData4-1; $i > 0; $i = $i - 1) {
                    $stockUpdateI = new PurchaseDetails();
                    $stockUpdateI = PurchaseDetails::where('purchase_details_status', 1)
                                                        ->where('purchase_id', $data4[$i]->purchase_id)  
                                                        ->where('medicine_id', $data4[$i]->medicine_id)
                                                        ->where('stock', $data4[$i]->stock)
                                                        ->firstOrFail();
                    
                    $setStock = $data4[$i]->stock;
                    $setStockSave = $setStock; 

                    $setStock = $setStock - $refundQtyPrevious + $refundItem->refund_qty; 
                    $stockUpdateI->stock = $setStock;
                    $stockUpdateI->save();

                    if($setStock > 0) {
                        break;
                    }
                            
                }
            }
        }
      
        // $stockUpdate->stock = $stockUpdate->stock - $refundQtyPrevious + $refundItem->refund_qty;
        if($refundItem) {
            Session::flash('success', 'Refund updated successfully');
            return redirect()->route('admin.refunds.index');
        }else {
            return back()->with('error', 'Opps! An error occurred!');
        }
    }


    public function destroy(Request $request) {
        if(auth()->user()->role_id <= 2) {
            $destroy = Refund::destroy($request->id);

            if($destroy){
                return response()->json(['success' => 'Refund(s) successfully deleted!']);
            }else{
                return response()->json(['error' => 'An error occurred! Please try again!']);
            }
        }



        $refunds = Refund::whereIn('id', $request->id)->get();
        foreach($refunds as $key=>$refund) {
            if($refund->branch_id != auth()->user()->branch->id) {
                abort('403', 'Permission Denied!');
            }
        }

        $destroy = Refund::destroy($request->id);            

        if($destroy){
            return response()->json(['success' => 'Refund(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

    public function restore(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }

        $restore = Refund::whereIn('id', $request->id)->restore();        

        if($restore){
            return response()->json(['success' => 'Refund(s) successfully restored!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

    public function forceDelete(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }
        
        $forceDelete = Refund::whereIn('id', $request->id)->forceDelete(); 

        if($forceDelete){
            return response()->json(['success' => 'Refund(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }
}



// $purchases = Purchase::where('branch_id', auth()->user()->branch->id)->get();
        
// $a = 0;
// foreach($purchases as $key=>$purchase) {
//     $array[$a++] = $purchase->purchaseDetails->pluck('purchase_id');
// }
// $count = $a;
// for($i = 0; $i < $count; $i = $i + 1) {
//     for($j = 0; $j < $array[$i]; $j = $j + 1) {
//         $data[$i] = PurchaseDetails::where('purchase_id', $array[$i][$j])
//                                     ->where('stock', '>=', 1)
//                                     ->select('id', 'purchase_id', 'medicine_id', 'sell_price', 'stock')
//                                     ->get();
//     }
// }
// for($i = 0; $i < $count; $i = $i + 1, $j = $j + 1) {
//     for($j = 0; $j < count($data[$i]); $j = $j + 1) {
//         $data[$i] = PurchaseDetails::where('purchase_id', $array[$i][$j])
//                                     ->where('medicine_id', $refunddetails->medicine_id)
//                                     ->where('stock', '>=', 1)
//                                     ->where('purchase_details_status', 1)
//                                     ->select('id', 'purchase_id', 'medicine_id', 'sell_price', 'stock')
//                                     ->get();
//     }
// }
// // static $stockUpdate;
// for($i = 0; $i < $count; $i = $i + 1, $j = $j + 1) {
//     for($j = 0; $j < count($data[$i]); $j = $j + 1) {
//         $stockUpdate = PurchaseDetails::where('purchase_details_status', 1)
//                                         ->where('purchase_id', $data[$i][$j]->purchase_id)  
//                                         ->where('medicine_id', $data[$i][$j]->medicine_id)
//                                         ->where('stock', $data[$i][$j]->stock)
//                                         ->firstOrFail();
//         $stockUpdate->stock = $stockUpdate->stock + $refunddetails->refund_qty;
//         if($stockUpdate->stock > 1) {
//             $stockUpdate->purchase_details_status = 1;
//         }
//         $stockUpdate->save();
//         // dd($stockUpdate->stock);
//     }
// }
















// $purchases = Purchase::where('branch_id', auth()->user()->branch->id)->get();
        
// $a = 0;
// foreach($purchases as $key=>$purchase) {
//     $array[$a++] = $purchase->purchaseDetails->pluck('purchase_id');
// }
// $count = $a;
// for($i = 0; $i < $count; $i = $i + 1) {
//     for($j = 0; $j < count($array[$i]); $j = $j + 1) {
//         $data[$i] = PurchaseDetails::where('purchase_id', $array[$i][$j])
//                                     ->where('stock', '>=', 1)
//                                     ->select('id', 'purchase_id', 'medicine_id', 'sell_price', 'stock')
//                                     ->get();
//     }
// }
// for($i = 0; $i < $count; $i = $i + 1, $j = $j + 1) {
//     for($j = 0; $j < count($data[$i]); $j = $j + 1) {
//         $data[$i] = PurchaseDetails::where('purchase_id', $array[$i][$j])
//                                     ->where('medicine_id', $refunddetails->medicine_id)
//                                     ->where('stock', '>=', 1)
//                                     ->where('purchase_details_status', 1)
//                                     ->select('id', 'purchase_id', 'medicine_id', 'sell_price', 'stock')
//                                     ->get();
//     }
// }
// $stockUpdate = new PurchaseDetails();
// for($i = 0; $i < $count; $i = $i + 1, $j = $j + 1) {
//     for($j = 0; $j < count($data[$i]); $j = $j + 1) {
//         $stockUpdate = PurchaseDetails::where('purchase_details_status', 1)
//                                         ->where('purchase_id', $data[$i][$j]->purchase_id)  
//                                         ->where('medicine_id', $data[$i][$j]->medicine_id)
//                                         ->where('stock', $data[$i][$j]->stock)
//                                         ->firstOrFail();
//         $stockUpdate->stock = $stockUpdate->stock + $refunddetails->refund_qty;
//         if($stockUpdate->stock >= 1) {
//             $stockUpdate->purchase_details_status = 1;
//         }
//         $stockUpdate->save();
//     }
//     if($stockUpdate->stock) {
//         break;
//     }
// }


// refund
// $data = [];
// for($i = 0; $i < $count; $i = $i + 1) {
//     for($j = 0; $j < count($array[$i]); $j = $j + 1) {
//         $data[$i] = PurchaseDetails::where('purchase_id', $array[$i][$j])
//                                     ->where('medicine_id', $refunddetails->medicine_id)
//                                     ->select('id', 'purchase_id', 'medicine_id', 'sell_price', 'stock')
//                                     ->get();
//     }
// }


// // return $data;
// $stockUpdate = [];
// for($i = 0; $i < $count; $i = $i + 1) {
//     for($j = 0; $j < count($data[$i]); $j = $j + 1) {
//         $stockUpdate[$i] = PurchaseDetails::where('purchase_details_status', 1)
//                                         ->where('purchase_id', $data[$i][$j]->purchase_id)  
//                                         ->where('medicine_id', $data[$i][$j]->medicine_id)
//                                         ->where('stock', $data[$i][$j]->stock)
//                                         ->get();
    
//     }

// }

// $data3 = [];
// $k = 0;
// foreach($stockUpdate as $key=>$stockdetails) {
//     $data3[$k] = $stockdetails;
//     $k = $k + 1;
// }

// $data4 = [];
// for($i = 0; $i < count($data3); $i = $i + 1) {
//     $data4[$i] = $data3[$i][0];
// }

// $setStock = 0;
// $negativeStockAdd = 0;
// $countData4 = count($data4);


// if($countData4 < 2) {
//     $stockUpdateI = new PurchaseDetails();
//     $stockUpdateI = PurchaseDetails::where('purchase_details_status', 1)
//                                     ->where('purchase_id', $data4[0]->purchase_id)  
//                                     ->where('medicine_id', $data4[0]->medicine_id)
//                                     ->where('stock', $data4[0]->stock)
//                                     ->firstOrFail();

//     $stockUpdateI->stock = $stockUpdateI->stock + $refunddetails->refund_qty;
//     $stockUpdateI->save();
// }else {
//     for($i = $countData4-1; $i > 0; $i = $i - 1) {
//         $stockUpdateI = new PurchaseDetails();
//         $stockUpdateI = PurchaseDetails::where('purchase_details_status', 1)
//                                             ->where('purchase_id', $data4[$i]->purchase_id)  
//                                             ->where('medicine_id', $data4[$i]->medicine_id)
//                                             ->where('stock', $data4[$i]->stock)
//                                             ->firstOrFail();
        
//         $setStock = $data4[$i]->stock;
//         $setStockSave = $setStock; 

//         $setStock = $setStock + $refunddetails->refund_qty ; 
//         $stockUpdateI->stock = $setStock;
//         $stockUpdateI->save();

//         if($setStock > 0) {
//             break;
//         }
                
//     }
// }