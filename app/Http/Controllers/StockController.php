<?php

namespace App\Http\Controllers;

use PDF;
use App\Branch;
use App\Purchase;
use Carbon\Carbon;
use App\PurchaseDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class StockController extends Controller {
    public function stockReport() {
        if(auth()->user()->role_id <= 2) {
            $branches = Branch::all();
            
            $branch = Branch::where('id', 1)->firstOrFail();
            $purchases = Purchase::where('branch_id', 1)->get();
            $a = 0;
            $j = 0; 
            $now = Carbon::parse(Carbon::today())->format('y-m-d');
            foreach($purchases as $key=>$purchase) {                
                $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                            ->where('stock', '>', 0)
                                            ->where('expiry_date', '>' , $now)
                                            ->get();
                for($i = 0; $i < count($array[$a]); $i++) {
                    $data[$j] = $array[$a][$i];
                    $j = $j + 1;
                }            
            }

            $paginator = $this->paginate($data);
            
            return view('admin.stock.stock', compact('paginator', 'branches', 'branch'));

        }


        $branch = Branch::where('id', auth()->user()->branch->id)->firstOrFail();
        $purchases = Purchase::where('branch_id', auth()->user()->branch->id)->get();
        $a = 0;
        $j = 0; 
        $now = Carbon::parse(Carbon::today())->format('y-m-d');
        foreach($purchases as $key=>$purchase) {
            $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                        ->where('stock', '>', 0)
                                        ->where('expiry_date', '>' , $now)
                                        ->get();
            for($i = 0; $i < count($array[$a]); $i++) {
                $data[$j] = $array[$a][$i];
                $j = $j + 1;
            }            
        }

        $paginator = $this->paginate($data);


        return view('admin.stock.stock', compact('paginator', 'branch'));
    }

    public function stockReportSearch(Request $request) {
        if(auth()->user()->role_id <= 2) {
            $branches = Branch::all();

            switch ($request->input('action')) {
                case 'search':
                    $branch = Branch::where('branch_code', $request['branch'])->firstOrFail();
                    $purchases = Purchase::where('branch_id', $branch->id)->get();
                    $a = 0;
                    $j = 0; 
                    $now = Carbon::parse(Carbon::today())->format('y-m-d');
                    foreach($purchases as $key=>$purchase) {
                        $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                                    ->where('stock', '>', 0)
                                                    ->where('expiry_date', '>' , $now)
                                                    ->get();
                        for($i = 0; $i < count($array[$a]); $i++) {
                            $data[$j] = $array[$a][$i];
                            $j = $j + 1;
                        }            
                    }

                    // $count = count($data);

                    // $stocks = [];
                    // for($i = 0; $i < $count; $i++) {
                    //     $stock = $data[$i]->stock;
                    //     $stockOne = $data[$i]->stock;
                    //     for($j = $i + 1; $j < $count; $j++) {
                    //         if($data[$i]->medicine_id == $data[$j]->medicine_id) {
                    //             $stock += $data[$j]->stock;            
                    //             $stocks[$data[$i]->medicine_id] = $stock;               
                    //             $data[$j]->stock = $stock;   
                    //             if (($key = array_search($data[$i], $data)) !== false) {
                    //                 unset($data[$key]);
                    //                 break;
                    //             }         
                    //         }                
                    //     }            
                    // }

                    // $data2 = [];
                    // $data2 = array_values($data);

                    $paginator = $this->paginate($data);

                    return view('admin.stock.stock', compact('paginator', 'branches', 'branch'));
                    break;
        
                case 'pdf':
                    $branch = Branch::where('branch_code', $request['branch'])->firstOrFail();
                    $purchases = Purchase::where('branch_id', $branch->id)->get();
                    $a = 0;
                    $j = 0; 
                    $now = Carbon::parse(Carbon::today())->format('y-m-d');
                    foreach($purchases as $key=>$purchase) {
                        $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                                    ->where('stock', '>', 0)
                                                    ->where('expiry_date', '>' , $now)
                                                    ->get();
                        for($i = 0; $i < count($array[$a]); $i++) {
                            $data[$j] = $array[$a][$i];
                            $j = $j + 1;
                        }            
                    }

                    // $count = count($data);

                    // $stocks = [];
                    // for($i = 0; $i < $count; $i++) {
                    //     $stock = $data[$i]->stock;
                    //     $stockOne = $data[$i]->stock;
                    //     for($j = $i + 1; $j < $count; $j++) {
                    //         if($data[$i]->medicine_id == $data[$j]->medicine_id) {
                    //             $stock += $data[$j]->stock;            
                    //             $stocks[$data[$i]->medicine_id] = $stock;               
                    //             $data[$j]->stock = $stock;   
                    //             if (($key = array_search($data[$i], $data)) !== false) {
                    //                 unset($data[$key]);
                    //                 break;
                    //             }         
                    //         }                
                    //     }            
                    // }

                    // $data2 = [];
                    // $data2 = array_values($data);
                    

                    $dispenser = $branch->user->name;

                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($this->convert_all_stock_data_to_html($data, $branch, $dispenser));
                    return $pdf->stream();
                    break;
            }
        }


        $branch = Branch::where('id', auth()->user()->branch->id)->firstOrFail();
        $purchases = Purchase::where('branch_id', auth()->user()->branch->id)->get();
        $a = 0;
        $j = 0; 
        $now = Carbon::parse(Carbon::today())->format('y-m-d');
        foreach($purchases as $key=>$purchase) {
            $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                        ->where('stock', '>', 0)
                                        ->where('expiry_date', '>' , $now)
                                        ->get();
            for($i = 0; $i < count($array[$a]); $i++) {
                $data[$j] = $array[$a][$i];
                $j = $j + 1;
            }            
        }

        // $count = count($data);

        // $stocks = [];
        // for($i = 0; $i < $count; $i++) {
        //     $stock = $data[$i]->stock;
        //     $stockOne = $data[$i]->stock;
        //     for($j = $i + 1; $j < $count; $j++) {
        //         if($data[$i]->medicine_id == $data[$j]->medicine_id) {
        //             $stock += $data[$j]->stock;            
        //             $stocks[$data[$i]->medicine_id] = $stock;               
        //             $data[$j]->stock = $stock;   
        //             if (($key = array_search($data[$i], $data)) !== false) {
        //                 unset($data[$key]);
        //                 break;
        //             }         
        //         }                
        //     }            
        // }

        // $data2 = [];
        // $data2 = array_values($data);
        $dispenser = auth()->user()->name;

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($this->convert_all_stock_data_to_html($data, $branch, $dispenser));
        return $pdf->stream();
    }


    public function stockOut() {
        if(auth()->user()->role_id <= 2) {
            $branches = Branch::all();
            
            $branch = Branch::where('id', 1)->firstOrFail();
            $purchases = Purchase::where('branch_id', 1)->get();
            $a = 0;
            $j = 0; 
            $data = [];
            $now = Carbon::parse(Carbon::today())->format('y-m-d');
            foreach($purchases as $key=>$purchase) {
                $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                            ->where('expiry_date', '>' , $now)
                                            ->get();
                for($i = 0; $i < count($array[$a]); $i++) {
                    $data[$j] = $array[$a][$i];
                    $j = $j + 1;
                }            
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

            $data2 = [];
            $data2 = array_values($data);

            $data3 = [];
            $j = 0;
            for($i = 0; $i < count($data2); $i++) {
                if($data2[$i]->stock == 0) {
                    $data3[$j++] = $data2[$i];
                }
            }
    
            $paginator = $this->paginate($data3);
            
            return view('admin.stock.stock-out', compact('paginator', 'branches', 'branch'));

        }


        $branch = Branch::where('id', auth()->user()->branch->id)->firstOrFail();
        $purchases = Purchase::where('branch_id', auth()->user()->branch->id)->get();
        $a = 0;
        $j = 0; 
        $data = [];
        $now = Carbon::parse(Carbon::today())->format('y-m-d');
        foreach($purchases as $key=>$purchase) {
            $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                        ->where('expiry_date', '>' , $now)
                                        ->get();
            for($i = 0; $i < count($array[$a]); $i++) {
                $data[$j] = $array[$a][$i];
                $j = $j + 1;
            }            
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

        $data2 = [];
        $data2 = array_values($data);

        $data3 = [];
        $j = 0;
        for($i = 0; $i < count($data2); $i++) {
            if($data2[$i]->stock == 0) {
                $data3[$j++] = $data2[$i];
            }
        }

        // return count($data3);

        $paginator = $this->paginate($data3);


        return view('admin.stock.stock-out', compact('paginator', 'branch'));
    }

    public function stockOutSearch(Request $request) {
        if(auth()->user()->role_id <= 2) {
            $branches = Branch::all();

            switch ($request->input('action')) {
                case 'search':
                    $branch = Branch::where('branch_code', $request['branch'])->firstOrFail();
                    $purchases = Purchase::where('branch_id', $branch->id)->get();
                    $a = 0;
                    $j = 0; 
                    $data = [];
                    $now = Carbon::parse(Carbon::today())->format('y-m-d');
                    foreach($purchases as $key=>$purchase) {
                        $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                                    ->where('expiry_date', '>' , $now)
                                                    ->get();
                        for($i = 0; $i < count($array[$a]); $i++) {
                            $data[$j] = $array[$a][$i];
                            $j = $j + 1;
                        }            
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

                    $data2 = [];
                    $data2 = array_values($data);
                    $data3 = [];
                    $j = 0;
                    for($i = 0; $i < count($data2); $i++) {
                        if($data2[$i]->stock == 0) {
                            $data3[$j++] = $data2[$i];
                        }
                    }

                    $paginator = $this->paginate($data3);

                    return view('admin.stock.stock-out', compact('paginator', 'branches', 'branch'));
                    break;
        
                case 'pdf':
                    $branch = Branch::where('branch_code', $request['branch'])->firstOrFail();
                    $purchases = Purchase::where('branch_id', $branch->id)->get();
                    $a = 0;
                    $j = 0; 
                    $data = [];
                    $now = Carbon::parse(Carbon::today())->format('y-m-d');
                    foreach($purchases as $key=>$purchase) {
                        $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                                    ->where('expiry_date', '>' , $now)
                                                    ->get();
                        for($i = 0; $i < count($array[$a]); $i++) {
                            $data[$j] = $array[$a][$i];
                            $j = $j + 1;
                        }            
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

                    $data2 = [];
                    $data2 = array_values($data);
                    $data3 = [];
                    $j = 0;
                    for($i = 0; $i < count($data2); $i++) {
                        if($data2[$i]->stock == 0) {
                            $data3[$j++] = $data2[$i];
                        }
                    }

                    $dispenser = $branch->user->name;

                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($this->convert_all_stock_out_data_to_html($data3, $branch, $dispenser));
                    return $pdf->stream();
                    break;
            }
        }


        $branch = Branch::where('id', auth()->user()->branch->id)->firstOrFail();
        $purchases = Purchase::where('branch_id', auth()->user()->branch->id)->get();
        $a = 0;
        $j = 0;
        $data = [];
        $now = Carbon::parse(Carbon::today())->format('y-m-d'); 
        foreach($purchases as $key=>$purchase) {
            $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                        ->where('expiry_date', '>' , $now)
                                        ->get();
            for($i = 0; $i < count($array[$a]); $i++) {
                $data[$j] = $array[$a][$i];
                $j = $j + 1;
            }            
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

        $data2 = [];
        $data2 = array_values($data);
        $data3 = [];
        $j = 0;
        for($i = 0; $i < count($data2); $i++) {
            if($data2[$i]->stock == 0) {
                $data3[$j++] = $data2[$i];
            }
        }
        $dispenser = auth()->user()->name;

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($this->convert_all_stock_out_data_to_html($data3, $branch, $dispenser));
        return $pdf->stream();
    }



    public function expired() {
        if(auth()->user()->role_id <= 2) {
            $branches = Branch::all();
            
            $branch = Branch::where('id', 1)->firstOrFail();
            $purchases = Purchase::where('branch_id', 1)->get();
            $a = 0;
            $j = 0; 
            $data = [];
            $now = Carbon::parse(Carbon::today())->format('y-m-d');
            foreach($purchases as $key=>$purchase) {
                $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                            ->where('stock', '>', 0)
                                            ->where('expiry_date', '<' , $now)
                                            ->get();
                for($i = 0; $i < count($array[$a]); $i++) {
                    $data[$j] = $array[$a][$i];
                    $j = $j + 1;
                }            
            }
    
            $paginator = $this->paginate($data);
            
            return view('admin.stock.expired', compact('paginator', 'branches', 'branch'));

        }


        $branch = Branch::where('id', auth()->user()->branch->id)->firstOrFail();
        $purchases = Purchase::where('branch_id', auth()->user()->branch->id)->get();
        $a = 0;
        $j = 0; 
        $data = [];
        $now = Carbon::parse(Carbon::today())->format('y-m-d');
        foreach($purchases as $key=>$purchase) {
            $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                        ->where('stock', '>', 0)
                                        ->where('expiry_date', '<' , $now)
                                        ->get();
            for($i = 0; $i < count($array[$a]); $i++) {
                $data[$j] = $array[$a][$i];
                $j = $j + 1;
            }            
        }


        $paginator = $this->paginate($data);


        return view('admin.stock.expired', compact('paginator', 'branch'));
    }

    public function expiredSearch(Request $request) {
        if(auth()->user()->role_id <= 2) {
            $branches = Branch::all();

            switch ($request->input('action')) {
                case 'search':
                    $branch = Branch::where('branch_code', $request['branch'])->firstOrFail();
                    $purchases = Purchase::where('branch_id', $branch->id)->get();
                    $a = 0;
                    $j = 0; 
                    $data = [];
                    $now = Carbon::parse(Carbon::today())->format('y-m-d');
                    foreach($purchases as $key=>$purchase) {
                        $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                                    ->where('stock', '>', 0)
                                                    ->where('expiry_date', '<' , $now)
                                                    ->get();
                        for($i = 0; $i < count($array[$a]); $i++) {
                            $data[$j] = $array[$a][$i];
                            $j = $j + 1;
                        }            
                    }

                    $paginator = $this->paginate($data);

                    return view('admin.stock.expired', compact('paginator', 'branches', 'branch'));
                    break;
        
                case 'pdf':
                    $branch = Branch::where('branch_code', $request['branch'])->firstOrFail();
                    $purchases = Purchase::where('branch_id', $branch->id)->get();
                    $a = 0;
                    $j = 0; 
                    $data = [];
                    $now = Carbon::parse(Carbon::today())->format('y-m-d');
                    foreach($purchases as $key=>$purchase) {
                        $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                                    ->where('stock', '>', 0)
                                                    ->where('expiry_date', '<' , $now)
                                                    ->get();
                        for($i = 0; $i < count($array[$a]); $i++) {
                            $data[$j] = $array[$a][$i];
                            $j = $j + 1;
                        }            
                    }

                    $dispenser = $branch->user->name;

                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($this->convert_all_stock_expired_data_to_html($data, $branch, $dispenser));
                    return $pdf->stream();
                    break;
            }
        }


        $branch = Branch::where('id', auth()->user()->branch->id)->firstOrFail();
        $purchases = Purchase::where('branch_id', auth()->user()->branch->id)->get();
        $a = 0;
        $j = 0; 
        $data = [];
        $now = Carbon::parse(Carbon::today())->format('y-m-d');
        foreach($purchases as $key=>$purchase) {
            $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                        ->where('stock', '>', 0)
                                        ->where('expiry_date', '<' , $now)
                                        ->get();
            for($i = 0; $i < count($array[$a]); $i++) {
                $data[$j] = $array[$a][$i];
                $j = $j + 1;
            }            
        }

        $dispenser = auth()->user()->name;

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($this->convert_all_stock_expired_data_to_html($data, $branch, $dispenser));
        return $pdf->stream();
    }

    public function expiredSoon() {
        if(auth()->user()->role_id <= 2) {
            $branches = Branch::all();
            
            $branch = Branch::where('id', 1)->firstOrFail();
            $purchases = Purchase::where('branch_id', 1)->get();
            $a = 0;
            $j = 0; 
            $data = [];
            $now = Carbon::parse(Carbon::today())->format('y-m-d');
            $thirty = Carbon::parse(Carbon::today()->addDays(30))->format('y-m-d');
            foreach($purchases as $key=>$purchase) {
                $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                            ->where('stock', '>', 0)
                                            ->where('expiry_date', '<=' , $thirty)
                                            ->where('expiry_date', '>=' , $now)
                                            ->get();
                for($i = 0; $i < count($array[$a]); $i++) {
                    $data[$j] = $array[$a][$i];
                    $j = $j + 1;
                }            
            }
    
            $paginator = $this->paginate($data);
            
            return view('admin.stock.expired-soon', compact('paginator', 'branches', 'branch'));

        }


        $branch = Branch::where('id', auth()->user()->branch->id)->firstOrFail();
        $purchases = Purchase::where('branch_id', auth()->user()->branch->id)->get();
        $a = 0;
        $j = 0; 
        $data = [];
        $now = Carbon::parse(Carbon::today())->format('y-m-d');
        $thirty = Carbon::parse(Carbon::today()->addDays(30))->format('y-m-d');
        foreach($purchases as $key=>$purchase) {
            $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                        ->where('stock', '>', 0)
                                        ->where('expiry_date', '<=' , $thirty)
                                        ->where('expiry_date', '>=' , $now)
                                        ->get();
            for($i = 0; $i < count($array[$a]); $i++) {
                $data[$j] = $array[$a][$i];
                $j = $j + 1;
            }            
        }


        $paginator = $this->paginate($data);


        return view('admin.stock.expired-soon', compact('paginator', 'branch'));
    }

    public function expiredSoonSearch(Request $request) {
        if(auth()->user()->role_id <= 2) {
            $branches = Branch::all();

            switch ($request->input('action')) {
                case 'search':
                    $branch = Branch::where('branch_code', $request['branch'])->firstOrFail();
                    $purchases = Purchase::where('branch_id', $branch->id)->get();
                    $a = 0;
                    $j = 0; 
                    $data = [];
                    $now = Carbon::parse(Carbon::today())->format('y-m-d');
                    $thirty = Carbon::parse(Carbon::today()->addDays(30))->format('y-m-d');
                    foreach($purchases as $key=>$purchase) {
                        $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                                    ->where('stock', '>', 0)
                                                    ->where('expiry_date', '<=' , $thirty)
                                                    ->where('expiry_date', '>=' , $now)
                                                    ->get();
                        for($i = 0; $i < count($array[$a]); $i++) {
                            $data[$j] = $array[$a][$i];
                            $j = $j + 1;
                        }            
                    }

                    $paginator = $this->paginate($data);

                    return view('admin.stock.expired-soon', compact('paginator', 'branches', 'branch'));
                    break;
        
                case 'pdf':
                    $branch = Branch::where('branch_code', $request['branch'])->firstOrFail();
                    $purchases = Purchase::where('branch_id', $branch->id)->get();
                    $a = 0;
                    $j = 0; 
                    $data = [];
                    $now = Carbon::parse(Carbon::today())->format('y-m-d');
                    $thirty = Carbon::parse(Carbon::today()->addDays(30))->format('y-m-d');
                    foreach($purchases as $key=>$purchase) {
                        $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                                    ->where('stock', '>', 0)
                                                    ->where('expiry_date', '<=' , $thirty)
                                                    ->where('expiry_date', '>=' , $now)
                                                    ->get();
                        for($i = 0; $i < count($array[$a]); $i++) {
                            $data[$j] = $array[$a][$i];
                            $j = $j + 1;
                        }            
                    }

                    $dispenser = $branch->user->name;

                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($this->convert_all_stock_expired_data_to_html($data, $branch, $dispenser));
                    return $pdf->stream();
                    break;
            }
        }


        $branch = Branch::where('id', auth()->user()->branch->id)->firstOrFail();
        $purchases = Purchase::where('branch_id', auth()->user()->branch->id)->get();
        $a = 0;
        $j = 0; 
        $data = [];
        $now = Carbon::parse(Carbon::today())->format('y-m-d');
        $thirty = Carbon::parse(Carbon::today()->addDays(30))->format('y-m-d');
        foreach($purchases as $key=>$purchase) {
            $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                        ->where('stock', '>', 0)
                                        ->where('expiry_date', '<=' , $thirty)
                                        ->where('expiry_date', '>=' , $now)
                                        ->get();
            for($i = 0; $i < count($array[$a]); $i++) {
                $data[$j] = $array[$a][$i];
                $j = $j + 1;
            }            
        }

        $dispenser = auth()->user()->name;

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($this->convert_all_stock_expired_data_to_html($data, $branch, $dispenser));
        return $pdf->stream();
    }




    public function paginate($items, $perPage = 10, $page = null, $options = []) {

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        $paginated = new LengthAwarePaginator(array_values($items->forPage($page, $perPage)->toArray()), $items->count(), $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);

        return $paginated;

    }



    function convert_all_stock_data_to_html($data2, $branch, $dispenser) {
        $output = '
                <h3 align="center">
                    <div> '.$branch->branch_title.' </div>
                    <div> '.$branch->branch_address.' </div>
                    <div> Print Date: '.Carbon::now()->format('d M, Y | h:i:s a').' </div>
                    <div> Branch Operator : &nbsp;'.$dispenser.' </div>
                </h3>
                <table width="100%" style="border-collapse: collapse; border: 0px;">
                    <tr>
                        <th style="border: 1px solid; padding:12px;" width="14%">SL</th>
                        <th style="border: 1px solid; padding:12px;" width="5%">Branch</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Medicine</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">DARR No</th>
                        <th style="border: 1px solid; padding:12px;" width="15%">Expiry Date</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Stock</th>
                    </tr>';  

        foreach($data2 as $key=>$data) {
        $output .= '
            <tbody>
                <tr>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="14%">'.++$key.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="5%">'.$branch->branch_code.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.$data->medicine_name.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.$data->darr_no.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$data->expiry_date.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.$data->stock.'</td>
                </tr>
            <tbody>';
        }

        $output .= '</table>';

        return $output;
    }

    function convert_all_stock_out_data_to_html($data2, $branch, $dispenser) {
        $output = '
                <h3 align="center">
                    <div> '.$branch->branch_title.' </div>
                    <div> '.$branch->branch_address.' </div>
                    <div> Print Date: '.Carbon::now()->format('d M, Y | h:i:s a').' </div>
                    <div> Branch Operator : &nbsp;'.$dispenser.' </div>
                </h3>
                <table width="100%" style="border-collapse: collapse; border: 0px;">
                    <tr>
                        <th style="border: 1px solid; padding:12px;" width="14%">SL</th>
                        <th style="border: 1px solid; padding:12px;" width="5%">Branch</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Medicine</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Stock</th>
                    </tr>';  

        foreach($data2 as $key=>$data) {
        $output .= '
            <tbody>
                <tr>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="14%">'.++$key.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="5%">'.$branch->branch_code.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.$data->medicine_name.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.$data->stock.'</td>
                </tr>
            <tbody>';
        }

        $output .= '</table>';

        return $output;
    }


    function convert_all_stock_expired_data_to_html($data2, $branch, $dispenser) {
        $output = '
                <h3 align="center">
                    <div> '.$branch->branch_title.' </div>
                    <div> '.$branch->branch_address.' </div>
                    <div> Print Date: '.Carbon::now()->format('d M, Y | h:i:s a').' </div>
                    <div> Branch Operator : &nbsp;'.$dispenser.' </div>
                </h3>
                <table width="100%" style="border-collapse: collapse; border: 0px;">
                    <tr>
                        <th style="border: 1px solid; padding:12px;" width="10%">SL</th>
                        <th style="border: 1px solid; padding:12px;" width="5%">Branch</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Medicine</th>
                        <th style="border: 1px solid; padding:12px;" width="15%">Stock</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Expire Date</th>
                    </tr>';  

        foreach($data2 as $key=>$data) {
        $output .= '
            <tbody>
                <tr>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="10%">'.++$key.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="5%">'.$branch->branch_code.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$data->medicine_name.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$data->stock.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.$data->expiry_date.'</td>
                </tr>
            <tbody>';
        }

        $output .= '</table>';

        return $output;
    }

    
}




