<?php

namespace App\Http\Controllers;

use PDF;
use App\Branch;
use App\Purchase;
use Carbon\Carbon;
use App\PurchaseDetails;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Pagination\LengthAwarePaginator;

class DamagedController extends Controller {
    public function damaged(Request $request) {
        return view('admin.damage.damage');
    }


    public function index(Request $request) {
        if(auth()->user()->role_id <= 2) {
            $branches = Branch::all();
            
            $branch = Branch::where('id', 1)->firstOrFail();
            $purchases = Purchase::where('branch_id', 1)->get();
            $a = 0;
            $j = 0;
            $data = [];
            foreach($purchases as $key=>$purchase) {
                $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                            ->where('stock_damaged', '!=', 0)
                                            ->latest()
                                            ->get();
                for($i = 0; $i < count($array[$a]); $i++) {
                    $data[$j] = $array[$a][$i];
                    $j = $j + 1;
                }            
            }

            $paginator = $this->paginate($data);


            return view('admin.damage.index', compact('paginator', 'branches', 'branch'));
        }

        if(auth()->user()->role_id > 2) {
            $branch = Branch::where('id', auth()->user()->branch->id)->firstOrFail();
            $purchases = Purchase::where('branch_id', auth()->user()->branch->id)->get();
            $a = 0;
            $j = 0;
            $data = [];
            foreach($purchases as $key=>$purchase) {
                $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                            ->where('stock_damaged', '!=', 0)
                                            ->latest()
                                            ->get();
                for($i = 0; $i < count($array[$a]); $i++) {
                    $data[$j] = $array[$a][$i];
                    $j = $j + 1;
                }            
            }

            $paginator = $this->paginate($data);


            return view('admin.damage.index', compact('paginator', 'branch'));
        }
        
    }

    public function createDamage(Request $request) {
        if(auth()->user()->role_id <= 2) {
            abort('403', 'Permission Denied');
        }

        $request->validate([
            'medicine_id' => 'required',
            'darr_no' => 'required',
            'expiry_date' => 'required',
            'pieces' => 'required',
        ]);

        $purchases = Purchase::where('branch_id', auth()->user()->branch->id)->get();
        $a = 0;
        $j = 0;
        $data = [];
        foreach($purchases as $key=>$purchase) {
            $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                            ->where('stock', '!=', 0)
                                            ->where('medicine_id', $request->medicine_id)
                                            ->where('darr_no', $request->darr_no)
                                            ->where('expiry_date', $request->expiry_date)
                                            ->latest()
                                            ->get();
            for($i = 0; $i < count($array[$a]); $i++) {
                $data[$j] = $array[$a][$i];
                $j = $j + 1;
            }            
        }

        $damageMed = new PurchaseDetails();
        $damageMed = PurchaseDetails::where('purchase_id', $data[0]->purchase_id)
                                ->where('stock', '!=', 0)
                                ->where('medicine_id', $data[0]->medicine_id)
                                ->where('darr_no',  $data[0]->darr_no)
                                ->where('expiry_date', $data[0]->expiry_date)
                                ->firstOrFail();

        $damageMed->stock = $damageMed->stock - $request->pieces;
        $damageMed->stock_damaged = $request->pieces;
        $damageMed->save();

        return Redirect::route('admin.damaged.index');
    }

    public function searchDamage(Request $request) {
        if(auth()->user()->role_id <= 2) {
            $branches = Branch::all();

            switch ($request->input('action')) {
                case 'search':
                    $branch = Branch::where('branch_code', $request['branch'])->firstOrFail();
                    $purchases = Purchase::where('branch_id', $branch->id)->get();
                    $a = 0;
                    $j = 0;
                    $data = [];
                    foreach($purchases as $key=>$purchase) {
                        $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                                    ->where('stock_damaged', '!=', 0)
                                                    ->latest()
                                                    ->get();
                        for($i = 0; $i < count($array[$a]); $i++) {
                            $data[$j] = $array[$a][$i];
                            $j = $j + 1;
                        }            
                    }

                    $paginator = $this->paginate($data);

                    return view('admin.damage.index', compact('paginator', 'branches', 'branch'));
                    break;
        
                case 'pdf':
                    $branch = Branch::where('branch_code', $request['branch'])->firstOrFail();
                    $purchases = Purchase::where('branch_id', $branch->id)->get();
                    $a = 0;
                    $j = 0;
                    $data = [];
                    foreach($purchases as $key=>$purchase) {
                        $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                                    ->where('stock_damaged', '!=', 0)
                                                    ->latest()
                                                    ->get();
                        for($i = 0; $i < count($array[$a]); $i++) {
                            $data[$j] = $array[$a][$i];
                            $j = $j + 1;
                        }            
                    }

                    $dispenser = $branch->user->name;

                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($this->convert_damaged_data_to_html($data, $branch, $dispenser));
                    return $pdf->stream();
                    break;
            }
        }

        if(auth()->user()->role_id > 2) {
            $branch = Branch::where('id', auth()->user()->branch->id)->firstOrFail();
            $purchases = Purchase::where('branch_id', auth()->user()->branch->id)->get();
            $a = 0;
            $j = 0;
            $data = [];
            foreach($purchases as $key=>$purchase) {
                $array[$a] = PurchaseDetails::where('purchase_id', $purchase->id)
                                            ->where('stock_damaged', '!=', 0)
                                            ->latest()
                                            ->get();
                for($i = 0; $i < count($array[$a]); $i++) {
                    $data[$j] = $array[$a][$i];
                    $j = $j + 1;
                }            
            }

            $dispenser = $branch->user->name;

            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($this->convert_damaged_data_to_html($data, $branch, $dispenser));
            return $pdf->stream();
        }
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


    function convert_damaged_data_to_html($data2, $branch, $dispenser) {
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
                        <th style="border: 1px solid; padding:12px;" width="20%">Damged Pieces</th>
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
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.$data->stock_damaged.'</td>
                </tr>
            <tbody>';
        }

        $output .= '</table>';

        return $output;
    }

}
