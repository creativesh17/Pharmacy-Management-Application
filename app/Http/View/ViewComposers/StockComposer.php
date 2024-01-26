<?php

namespace App\Http\View\ViewComposers;

use App\Branch;
use App\Purchase;
use Carbon\Carbon;
use App\PharmacySetting;
use App\PurchaseDetails;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class StockComposer {

    public function compose(View $view) {
        if(Auth::check()) {
            if(auth()->user()->role_id <= 2) {
                $pharmacy = PharmacySetting::active()->where('id',1)->firstOrFail();
                $user = auth()->user();
                $branch = Branch::where('id', 1)->firstOrFail();
                $purchases = Purchase::where('branch_id', 1)->get();
                $a = 0;
                $j = 0; 
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
        
                $stockOut = count($data3);
    
    
    
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
    
                $expired = count($data);
    
    
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
    
                $expiredSoon = count($data);
    
                $view->with(['stockOut' => $stockOut, 'expired' => $expired, 'expiredSoon' => $expiredSoon, 'pharmacy' => $pharmacy, 'user' => $user]);
    
            }
    
            if(auth()->user()->role_id > 2) {
                $pharmacy = PharmacySetting::active()->where('id',1)->firstOrFail();
                $user = auth()->user();
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
    
                // Stock Out
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
    
                $stockOut = count($data3);
    
    
                // Expired
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
    
                $expired = count($data);
    
    
                // Expires within 30 days
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
    
                $expiredSoon = count($data);
    
                $view->with(['stockOut' => $stockOut, 'expired' => $expired, 'expiredSoon' => $expiredSoon, 'pharmacy' => $pharmacy, 'user' => $user]);
            }
        }
        
    }
}