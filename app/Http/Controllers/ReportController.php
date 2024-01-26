<?php

namespace App\Http\Controllers;

use PDF;
use App\Branch;
use App\Refund;
use App\Expense;
use App\Invoice;
use App\Purchase;
use Carbon\Carbon;
use App\PharmacySetting;
use Illuminate\Http\Request;

class ReportController extends Controller {
    // sale report
    public function saleToday() {
        if(auth()->user()->role_id <= 2) {
            $branches = Branch::all();
            $setting = PharmacySetting::active()->where('id',1)->firstOrFail();
            
            $todaySale = Invoice::orderBy('id', 'DESC')->whereDate('invoice_date', Carbon::today()->format('y-m-d'))->paginate(20);

            $todaySaleTotal = Invoice::orderBy('id', 'DESC')->whereDate('invoice_date', Carbon::today()->format('y-m-d'))->sum('invoice_nettotal');

            return view('admin.reports.sale-today', compact('todaySale', 'todaySaleTotal', 'setting', 'branches'));
        }

        $branch = Branch::where('id', auth()->user()->branch->id)->firstOrFail();

        $todaySale = Invoice::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->whereDate('invoice_date', Carbon::today()->format('y-m-d'))->paginate(20);

        $todaySaleTotal = Invoice::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->whereDate('invoice_date', Carbon::today()->format('y-m-d'))->sum('invoice_nettotal');

        return view('admin.reports.sale-today', compact('todaySale', 'todaySaleTotal', 'branch'));
    }

    public function saleSearch(Request $request) {
        if(auth()->user()->role_id <= 2) {
            $this->validate($request, [
                'branch' => 'required',
                'from' => 'date|required_with:to',
                'to' => 'date|required_with:from',
            ], [
                'branch.required' => 'Please enter branch name!',
                'from.date' => 'Please enter a valid start date!',
                'to.date' => 'Please enter a valid end date!',
            ]);


            $branches = Branch::all();
            $setting = PharmacySetting::active()->where('id',1)->firstOrFail();

            switch ($request->input('action')) {
                case 'search':
                    $from = $request['from']; 
                    $to = $request['to'];

                    if($request['branch'] != "All") {
                        $branch = Branch::where('branch_code', $request['branch'])->firstOrFail();
                        $sales = Invoice::where('branch_id', $branch->id)->whereBetween('invoice_date', [$from, $to])->paginate(20);
                        $saleTotal = Invoice::where('branch_id', $branch->id)->whereBetween('invoice_date', [$from, $to])->sum('invoice_nettotal');
                        $dispenser = $branch->user->name;

                        return view('admin.reports.sale', compact('sales', 'saleTotal', 'branch', 'branches', 'setting', 'dispenser'));
                    }else {
                        $branch = $request['branch'];
                        $sales = Invoice::whereBetween('invoice_date', [$from, $to])->paginate(20);
                        $saleTotal = Invoice::whereBetween('invoice_date', [$from, $to])->sum('invoice_nettotal');

                        return view('admin.reports.sale', compact('sales', 'saleTotal', 'branch', 'branches', 'setting'));
                    }
                    break;
        
                case 'pdf':
                    $from = $request['from']; 
                    $to = $request['to'];
                    $fromDate = Carbon::parse($from)->format('d-M, Y');
                    $toDate = Carbon::parse($to)->format('d-M, Y');
                    
                    if($request['branch'] != "All") {
                        $branch = Branch::where('branch_code', $request['branch'])->firstOrFail();
                        $sales = Invoice::where('branch_id', $branch->id)->whereBetween('invoice_date', [$from, $to])->cursor();
                        $saleTotal = Invoice::where('branch_id', $branch->id)->whereBetween('invoice_date', [$from, $to])->sum('invoice_nettotal');
                        $dispenser = $branch->user->name;

                        $pdf = \App::make('dompdf.wrapper');
                        $pdf->loadHTML($this->convert_sale_data_to_html($branch, $sales, $saleTotal, $fromDate, $toDate, $dispenser));
                        return $pdf->stream();
                    }else {
                        $branch = $request['branch'];
                        $sales = Invoice::whereBetween('invoice_date', [$from, $to])->cursor();
                        $saleTotal = Invoice::whereBetween('invoice_date', [$from, $to])->sum('invoice_nettotal');

                        $pdf = \App::make('dompdf.wrapper');
                        $pdf->loadHTML($this->convert_all_sale_data_to_html($sales, $saleTotal, $setting, $fromDate, $toDate));
                        return $pdf->stream();
                    }
                    break;
            }
        }










        $this->validate($request, [
            'from' => 'date|required_with:to',
            'to' => 'date|required_with:from',
        ], [
            'from.date' => 'Please enter a valid start date!',
            'to.date' => 'Please enter a valid end date!',
        ]);

        $branch = Branch::where('id', auth()->user()->branch->id)->firstOrFail();
        
        switch ($request->input('action')) {
            case 'search':
                $from = $request['from']; 
                $to = $request['to'];
                $fromDate = Carbon::parse($from)->format('y-m-d');
                $toDate = Carbon::parse($to)->format('y-m-d');

                $sales = Invoice::where('branch_id', $branch->id)->whereBetween('invoice_date', [$from, $to])->paginate(20);
                $saleTotal = Invoice::where('branch_id', $branch->id)->whereBetween('invoice_date', [$from, $to])->sum('invoice_nettotal');
                $dispenser = auth()->user()->name;

                return view('admin.reports.sale', compact('sales', 'saleTotal', 'branch', 'dispenser'));
                
                break;
    
            case 'pdf':
                $from = $request['from']; 
                $to = $request['to'];
                $fromDate = Carbon::parse($from)->format('y-m-d');
                $toDate = Carbon::parse($to)->format('y-m-d');

                
               
                $sales = Invoice::where('branch_id', $branch->id)->whereBetween('invoice_date', [$from, $to])->cursor();
                $saleTotal = Invoice::where('branch_id', $branch->id)->whereBetween('invoice_date', [$from, $to])->sum('invoice_nettotal');
                $dispenser = auth()->user()->name;

                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($this->convert_sale_data_to_html($branch, $sales, $saleTotal, $fromDate, $toDate, $dispenser));
                return $pdf->stream();
               
                break;
        }



    }


    function convert_sale_data_to_html($branch, $sales, $saleTotal, $fromDate, $toDate, $dispenser) {
        $output = '
                <h3 align="center">
                    <div> '.$branch->branch_title.' </div>
                    <div> '.$branch->branch_address.' </div>
                    <div> Print Date: '.Carbon::now()->format('d M, Y | h:i:s a').' </div>
                    <div> From '.$fromDate.' To '.$toDate.' </div>
                    <div> Branch Operator : '.$dispenser.' </div>
                </h3>
                <table width="100%" style="border-collapse: collapse; border: 0px;">
                    <tr>
                        <th style="border: 1px solid; padding:12px;" width="15%">SL</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Invoice No</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Date</th>
                        <th style="border: 1px solid; padding:12px;" width="15%">Branch Code</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Customer</th>
                        <th style="border: 1px solid; padding:12px;" width="25%">Net Total</th>
                    </tr>';  

        foreach($sales as $key=>$sale) {
        $output .= '
            <tbody>
                <tr>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.++$key.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="30%">'.$sale->id.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$sale->invoice_date.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$sale->branch->branch_code.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$sale->customer->customer_name.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.$sale->invoice_nettotal.'</td>
                </tr>
            <tbody>';
        }

        $output .= '
                    <tfoot>
                        <tr>
                            <td colspan="5"  style="border: 1px solid; padding:12px; font-weight: 700; text-align: right;"> Net Total </td>
                            <td align="right" style="border: 1px solid; padding:12px; font-weight: 700; text-align: center;">        '.$saleTotal. '</td>
                        </tr>
                        
                    </tfoot>
                </table>';
        $output .= ' 
                <table align="right" style="padding-top: 2em;">
                    <tr>
                        <td style="padding-right: 2em; width: 100px; text-align: right;">
                            <h4 style="padding-right: 2em; padding-top: 4px; width: 100px; text-align: right;border-top: 1px solid #ddd;">Signature</h4>
                        </td>
                    </tr>
                </table>';

        return $output;
    }

    function convert_all_sale_data_to_html($sales, $saleTotal, $setting, $fromDate, $toDate) {
        $output = '
                <h3 align="center">
                    <div> '.$setting->ph_name.' </div>
                    <div> '.$setting->ph_address.' </div>
                    <div> Print Date: '.Carbon::now()->format('d M, Y | h:i:s a').' </div>
                    <div> From '.$fromDate.' To '.$toDate.' </div>
                </h3>
                <table width="100%" style="border-collapse: collapse; border: 0px;">
                    <tr>
                        <th style="border: 1px solid; padding:12px;" width="15%">SL</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Invoice No</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Date</th>
                        <th style="border: 1px solid; padding:12px;" width="15%">Branch Code</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Customer</th>
                        <th style="border: 1px solid; padding:12px;" width="25%">Net Total</th>
                    </tr>';  

        foreach($sales as $key=>$sale) {
        $output .= '
            <tbody>
                <tr>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.++$key.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="30%">'.$sale->id.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$sale->invoice_date.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$sale->branch->branch_code.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$sale->customer->customer_name.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.$sale->invoice_nettotal.'</td>
                </tr>
            <tbody>';
        }

        $output .= '
                    <tfoot>
                        <tr>
                            <td colspan="5"  style="border: 1px solid; padding:12px; font-weight: 700; text-align: right;"> Net Total </td>
                            <td align="right" style="border: 1px solid; padding:12px; font-weight: 700; text-align: center;">        '.$saleTotal. '</td>
                        </tr>
                        
                    </tfoot>
                </table>';
        $output .= '
                    
                    <table align="right" style="padding-top: 2em;">
                        <tr>
                            <td style="padding-right: 2em; width: 100px; text-align: right;">
                                <h4 style="padding-right: 2em; padding-top: 4px; width: 100px; text-align: right;border-top: 1px solid #ddd;">Signature</h4>
                            </td>
                        </tr>
                    </table>
                    
                    ';

        return $output;
    }




    
    
    
    
    
    
    
    
    
    
    // purchase report
    public function purchaseToday() {
        if(auth()->user()->role_id <= 2) {
            $branches = Branch::all();
            $setting = PharmacySetting::active()->where('id',1)->firstOrFail();
            
            $todayPurchase = Purchase::orderBy('id', 'DESC')->whereDate('purchase_date', Carbon::today()->format('y-m-d'))->paginate(20);

            $todayPurchaseTotal = Purchase::orderBy('id', 'DESC')->whereDate('purchase_date', Carbon::today()->format('y-m-d'))->sum('purchase_nettotal');

            return view('admin.reports.purchase-today', compact('todayPurchase', 'todayPurchaseTotal', 'setting', 'branches'));
        }

        $branch = Branch::where('id', auth()->user()->branch->id)->firstOrFail();

        $todayPurchase = Purchase::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->whereDate('purchase_date', Carbon::today()->format('y-m-d'))->paginate(20);

        $todayPurchaseTotal = Purchase::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->whereDate('purchase_date', Carbon::today()->format('y-m-d'))->sum('purchase_nettotal');

        return view('admin.reports.purchase-today', compact('todayPurchase', 'todayPurchaseTotal', 'branch'));
    }

    public function purchaseSearch(Request $request) {
        if(auth()->user()->role_id <= 2) {
            $this->validate($request, [
                'branch' => 'required',
                'from' => 'date|required_with:to',
                'to' => 'date|required_with:from',
            ], [
                'branch.required' => 'Please enter branch name!',
                'from.date' => 'Please enter a valid start date!',
                'to.date' => 'Please enter a valid end date!',
            ]);


            $branches = Branch::all();
            $setting = PharmacySetting::active()->where('id',1)->firstOrFail();

            switch ($request->input('action')) {
                case 'search':
                    $from = $request['from']; 
                    $to = $request['to'];

                    if($request['branch'] != "All") {
                        $branch = Branch::where('branch_code', $request['branch'])->firstOrFail();
                        $purchases = Purchase::where('branch_id', $branch->id)->whereBetween('purchase_date', [$from, $to])->paginate(20);
                        $purchaseTotal = Purchase::where('branch_id', $branch->id)->whereBetween('purchase_date', [$from, $to])->sum('purchase_nettotal');
                        $dispenser = $branch->user->name;

                        return view('admin.reports.purchase', compact('purchases', 'purchaseTotal', 'branch', 'branches', 'setting', 'dispenser'));
                    }else {
                        $branch = $request['branch'];
                        $purchases = Purchase::whereBetween('purchase_date', [$from, $to])->paginate(20);
                        $purchaseTotal = Purchase::whereBetween('purchase_date', [$from, $to])->sum('purchase_nettotal');
                        return view('admin.reports.purchase', compact('purchases', 'purchaseTotal', 'branch', 'branches', 'setting'));
                    }
                    break;
        
                case 'pdf':
                    $from = $request['from']; 
                    $to = $request['to'];
                    $fromDate = Carbon::parse($from)->format('d-M, Y');
                    $toDate = Carbon::parse($to)->format('d-M, Y');
                    
                    if($request['branch'] != "All") {
                        $branch = Branch::where('branch_code', $request['branch'])->firstOrFail();
                        $purchases = Purchase::where('branch_id', $branch->id)->whereBetween('purchase_date', [$from, $to])->cursor();
                        $purchaseTotal = Purchase::where('branch_id', $branch->id)->whereBetween('purchase_date', [$from, $to])->sum('purchase_nettotal');
                        $dispenser = $branch->user->name;

                        $pdf = \App::make('dompdf.wrapper');
                        $pdf->loadHTML($this->convert_purchase_data_to_html($branch, $purchases, $purchaseTotal, $fromDate, $toDate, $dispenser));
                        return $pdf->stream();
                    }else {
                        $branch = $request['branch'];
                        $purchases = Purchase::whereBetween('purchase_date', [$from, $to])->cursor();
                        $purchaseTotal = Purchase::whereBetween('purchase_date', [$from, $to])->sum('purchase_nettotal');

                        $pdf = \App::make('dompdf.wrapper');
                        $pdf->loadHTML($this->convert_all_purchase_data_to_html($purchases, $purchaseTotal, $setting, $fromDate, $toDate));
                        return $pdf->stream();
                    }
                    break;
            }
        }










        $this->validate($request, [
            'from' => 'date|required_with:to',
            'to' => 'date|required_with:from',
        ], [
            'from.date' => 'Please enter a valid start date!',
            'to.date' => 'Please enter a valid end date!',
        ]);

        $branch = Branch::where('id', auth()->user()->branch->id)->firstOrFail();
        
        switch ($request->input('action')) {
            case 'search':
                $from = $request['from']; 
                $to = $request['to'];
                $fromDate = Carbon::parse($from)->format('d-M, Y');
                $toDate = Carbon::parse($to)->format('d-M, Y');

                $purchases = Purchase::where('branch_id', $branch->id)->whereBetween('purchase_date', [$from, $to])->paginate(20);
                $purchaseTotal = Purchase::where('branch_id', $branch->id)->whereBetween('purchase_date', [$from, $to])->sum('purchase_nettotal');
                $dispenser = auth()->user()->name;

                return view('admin.reports.purchase', compact('purchases', 'purchaseTotal', 'branch', 'dispenser'));
                
                break;
    
            case 'pdf':
                $from = $request['from']; 
                $to = $request['to'];
                $fromDate = Carbon::parse($from)->format('d-M, Y');
                $toDate = Carbon::parse($to)->format('d-M, Y');

                $purchases = Purchase::where('branch_id', $branch->id)->whereBetween('purchase_date', [$from, $to])->cursor();
                $purchaseTotal = Purchase::where('branch_id', $branch->id)->whereBetween('purchase_date', [$from, $to])->sum('purchase_nettotal');
                $dispenser = auth()->user()->name;

                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($this->convert_purchase_data_to_html($branch, $purchases, $purchaseTotal, $fromDate, $toDate, $dispenser));
                return $pdf->stream();

                break;
        }



    }


    function convert_purchase_data_to_html($branch, $purchases, $purchaseTotal, $fromDate, $toDate, $dispenser) {
        $output = '
                <h3 align="center">
                    <div> '.$branch->branch_title.' </div>
                    <div> '.$branch->branch_address.' </div>
                    <div> Print Date: '.Carbon::now()->format('d M, Y | h:i:s a').' </div>
                    <div> From '.$fromDate.' To '.$toDate.' </div>
                    <div> Branch Operator :  '.$dispenser.' </div>
                </h3>
                <table width="100%" style="border-collapse: collapse; border: 0px;">
                    <tr>
                        <th style="border: 1px solid; padding:12px;" width="15%">SL</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Purchase No</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Date</th>
                        <th style="border: 1px solid; padding:12px;" width="15%">Branch Code</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Supplier</th>
                        <th style="border: 1px solid; padding:12px;" width="25%">Net Total</th>
                    </tr>';  

        foreach($purchases as $key=>$purchase) {
        $output .= '
            <tbody>
                <tr>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.++$key.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="30%">'.$purchase->id.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$purchase->purchase_date.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$purchase->branch->branch_code.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$purchase->supplier->sup_name.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.$purchase->purchase_nettotal.'</td>
                </tr>
            <tbody>';
        }

        $output .= '
                    <tfoot>
                        <tr>
                            <td colspan="5"  style="border: 1px solid; padding:12px; font-weight: 700; text-align: right;"> Net Total </td>
                            <td align="right" style="border: 1px solid; padding:12px; font-weight: 700; text-align: center;">        '.$purchaseTotal. '</td>
                        </tr>
                        
                    </tfoot>
                </table>';
        $output .= ' 
                <table align="right" style="padding-top: 2em;">
                    <tr>
                        <td style="padding-right: 2em; width: 100px; text-align: right;">
                            <h4 style="padding-right: 2em; padding-top: 4px; width: 100px; text-align: right;border-top: 1px solid #ddd;">Signature</h4>
                        </td>
                    </tr>
                </table>';

        return $output;
    }

    function convert_all_purchase_data_to_html($purchases, $purchaseTotal, $setting, $fromDate, $toDate) {
        $output = '
                <h3 align="center">
                    <div> '.$setting->ph_name.' </div>
                    <div> '.$setting->ph_address.' </div>
                    <div> Print Date: '.Carbon::now()->format('d M, Y | h:i:s a').' </div>
                    <div> From '.$fromDate.' To '.$toDate.' </div>
                </h3>
                <table width="100%" style="border-collapse: collapse; border: 0px;">
                    <tr>
                        <th style="border: 1px solid; padding:12px;" width="15%">SL</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Purchase No</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Date</th>
                        <th style="border: 1px solid; padding:12px;" width="15%">Branch Code</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Supplier</th>
                        <th style="border: 1px solid; padding:12px;" width="25%">Net Total</th>
                    </tr>';  

        foreach($purchases as $key=>$purchase) {
        $output .= '
            <tbody>
                <tr>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.++$key.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="30%">'.$purchase->id.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$purchase->purchase_date.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$purchase->branch->branch_code.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$purchase->supplier->sup_name.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.$purchase->purchase_nettotal.'</td>
                </tr>
            <tbody>';
        }

        $output .= '
                    <tfoot>
                        <tr>
                            <td colspan="5"  style="border: 1px solid; padding:12px; font-weight: 700; text-align: right;"> Net Total </td>
                            <td align="right" style="border: 1px solid; padding:12px; font-weight: 700; text-align: center;">        '.$purchaseTotal. '</td>
                        </tr>
                        
                    </tfoot>
                </table>';
        $output .= '
                    
                    <table align="right" style="padding-top: 2em;">
                        <tr>
                            <td style="padding-right: 2em; width: 100px; text-align: right;">
                                <h4 style="padding-right: 2em; padding-top: 4px; width: 100px; text-align: right;border-top: 1px solid #ddd;">Signature</h4>
                            </td>
                        </tr>
                    </table>
                    
                    ';

        return $output;
    }















    // Refund report
    public function refundToday() {
        if(auth()->user()->role_id <= 2) {
            $branches = Branch::all();
            $setting = PharmacySetting::active()->where('id',1)->firstOrFail();
            
            $todayRefund = Refund::orderBy('id', 'DESC')->whereDate('refund_date', Carbon::today()->format('y-m-d'))->paginate(20);

            $todayRefundTotal = Refund::orderBy('id', 'DESC')->whereDate('refund_date', Carbon::today()->format('y-m-d'))->sum('refund_nettotal');

            return view('admin.reports.refund-today', compact('todayRefund', 'todayRefundTotal', 'setting', 'branches'));
        }

        $branch = Branch::where('id', auth()->user()->branch->id)->firstOrFail();

        $todayRefund = Refund::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->whereDate('refund_date', Carbon::today()->format('y-m-d'))->paginate(20);

        $todayRefundTotal = Refund::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->whereDate('refund_date', Carbon::today()->format('y-m-d'))->sum('refund_nettotal');

        return view('admin.reports.refund-today', compact('todayRefund', 'todayRefundTotal', 'branch'));
    }

    public function refundSearch(Request $request) {
        if(auth()->user()->role_id <= 2) {
            $this->validate($request, [
                'branch' => 'required',
                'from' => 'date|required_with:to',
                'to' => 'date|required_with:from',
            ], [
                'branch.required' => 'Please enter branch name!',
                'from.date' => 'Please enter a valid start date!',
                'to.date' => 'Please enter a valid end date!',
            ]);


            $branches = Branch::all();
            $setting = PharmacySetting::active()->where('id',1)->firstOrFail();

            switch ($request->input('action')) {
                case 'search':
                    $from = $request['from']; 
                    $to = $request['to'];

                    if($request['branch'] != "All") {
                        $branch = Branch::where('branch_code', $request['branch'])->firstOrFail();
                        $refunds = Refund::where('branch_id', $branch->id)->whereBetween('refund_date', [$from, $to])->paginate(20);
                        $refundTotal = Refund::where('branch_id', $branch->id)->whereBetween('refund_date', [$from, $to])->sum('refund_nettotal');
                        $dispenser = $branch->user->name;

                        return view('admin.reports.refund', compact('refunds', 'refundTotal', 'branch', 'branches', 'setting', 'dispenser'));
                    }else {
                        $branch = $request['branch'];
                        $refunds = Refund::whereBetween('refund_date', [$from, $to])->paginate(20);
                        $refundTotal = Refund::whereBetween('refund_date', [$from, $to])->sum('refund_nettotal');
                        return view('admin.reports.refund', compact('refunds', 'refundTotal', 'branch', 'branches', 'setting'));
                    }
                    break;
        
                case 'pdf':
                    $from = $request['from']; 
                    $to = $request['to'];
                    $fromDate = Carbon::parse($from)->format('d-M, Y');
                    $toDate = Carbon::parse($to)->format('d-M, Y');
                    
                    if($request['branch'] != "All") {
                        $branch = Branch::where('branch_code', $request['branch'])->firstOrFail();
                        $refunds = Refund::where('branch_id', $branch->id)->whereBetween('refund_date', [$from, $to])->cursor();
                        $refundTotal = Refund::where('branch_id', $branch->id)->whereBetween('refund_date', [$from, $to])->sum('refund_nettotal');
                        $dispenser = $branch->user->name;

                        $pdf = \App::make('dompdf.wrapper');
                        $pdf->loadHTML($this->convert_refund_data_to_html($branch, $refunds, $refundTotal, $fromDate, $toDate, $dispenser));
                        return $pdf->stream();
                    }else {
                        $branch = $request['branch'];
                        $refunds = Refund::whereBetween('refund_date', [$from, $to])->cursor();
                        $refundTotal = Refund::whereBetween('refund_date', [$from, $to])->sum('refund_nettotal');

                        $pdf = \App::make('dompdf.wrapper');
                        $pdf->loadHTML($this->convert_all_refund_data_to_html($refunds, $refundTotal, $setting, $fromDate, $toDate));
                        return $pdf->stream();
                    }
                    break;
            }
        }










        $this->validate($request, [
            'from' => 'date|required_with:to',
            'to' => 'date|required_with:from',
        ], [
            'from.date' => 'Please enter a valid start date!',
            'to.date' => 'Please enter a valid end date!',
        ]);

        $branch = Branch::where('id', auth()->user()->branch->id)->firstOrFail();
        
        switch ($request->input('action')) {
            case 'search':
                $from = $request['from']; 
                $to = $request['to'];
                $fromDate = Carbon::parse($from)->format('d-M, Y');
                $toDate = Carbon::parse($to)->format('d-M, Y');

                $refunds = Refund::where('branch_id', $branch->id)->whereBetween('refund_date', [$from, $to])->paginate(20);
                $refundTotal = Refund::where('branch_id', $branch->id)->whereBetween('refund_date', [$from, $to])->sum('refund_nettotal');
                $dispenser = auth()->user()->name;

                return view('admin.reports.refund', compact('refunds', 'refundTotal', 'branch', 'dispenser'));
                
                break;
    
            case 'pdf':
                $from = $request['from']; 
                $to = $request['to'];
                $fromDate = Carbon::parse($from)->format('d-M, Y');
                $toDate = Carbon::parse($to)->format('d-M, Y');

                $refunds = Refund::where('branch_id', $branch->id)->whereBetween('refund_date', [$from, $to])->cursor();
                $refundTotal = Refund::where('branch_id', $branch->id)->whereBetween('refund_date', [$from, $to])->sum('refund_nettotal');
                $dispenser = auth()->user()->name;

                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($this->convert_refund_data_to_html($branch, $refunds, $refundTotal, $fromDate, $toDate, $dispenser));
                return $pdf->stream();

                break;
        }



    }


    function convert_refund_data_to_html($branch, $refunds, $refundTotal, $fromDate, $toDate, $dispenser) {
        $output = '
                <h3 align="center">
                    <div> '.$branch->branch_title.' </div>
                    <div> '.$branch->branch_address.' </div>
                    <div> Print Date: '.Carbon::now()->format('d M, Y | h:i:s a').' </div>
                    <div> From '.$fromDate.' To '.$toDate.' </div>
                    <div> Branch Operator : '.$dispenser.' </div>
                </h3>
                <table width="100%" style="border-collapse: collapse; border: 0px;">
                    <tr>
                        <th style="border: 1px solid; padding:12px;" width="15%">SL</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Refund No</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Date</th>
                        <th style="border: 1px solid; padding:12px;" width="15%">Branch Code</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Customer</th>
                        <th style="border: 1px solid; padding:12px;" width="25%">Net Total</th>
                    </tr>';  

        foreach($refunds as $key=>$refund) {
        $output .= '
            <tbody>
                <tr>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.++$key.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="30%">'.$refund->id.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$refund->refund_date.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$refund->branch->branch_code.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$refund->customer->customer_name.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.$refund->refund_nettotal.'</td>
                </tr>
            <tbody>';
        }

        $output .= '
                    <tfoot>
                        <tr>
                            <td colspan="5"  style="border: 1px solid; padding:12px; font-weight: 700; text-align: right;"> Net Total </td>
                            <td align="right" style="border: 1px solid; padding:12px; font-weight: 700; text-align: center;">        '.$refundTotal. '</td>
                        </tr>
                        
                    </tfoot>
                </table>';
        $output .= ' 
                <table align="right" style="padding-top: 2em;">
                    <tr>
                        <td style="padding-right: 2em; width: 100px; text-align: right;">
                            <h4 style="padding-right: 2em; padding-top: 4px; width: 100px; text-align: right;border-top: 1px solid #ddd;">Signature</h4>
                        </td>
                    </tr>
                </table>';

        return $output;
    }

    function convert_all_refund_data_to_html($refunds, $refundTotal, $setting, $fromDate, $toDate) {
        $output = '
                <h3 align="center">
                    <div> '.$setting->ph_name.' </div>
                    <div> '.$setting->ph_address.' </div>
                    <div> Print Date: '.Carbon::now()->format('d M, Y | h:i:s a').' </div>
                    <div> From '.$fromDate.' To '.$toDate.' </div>
                </h3>
                <table width="100%" style="border-collapse: collapse; border: 0px;">
                    <tr>
                        <th style="border: 1px solid; padding:12px;" width="15%">SL</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Refund No</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Date</th>
                        <th style="border: 1px solid; padding:12px;" width="15%">Branch Code</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Customer</th>
                        <th style="border: 1px solid; padding:12px;" width="25%">Net Total</th>
                    </tr>';  

        foreach($refunds as $key=>$refund) {
        $output .= '
            <tbody>
                <tr>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.++$key.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="30%">'.$refund->id.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$refund->refund_date.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$refund->branch->branch_code.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$refund->customer->customer_name.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.$refund->refund_nettotal.'</td>
                </tr>
            <tbody>';
        }

        $output .= '
                    <tfoot>
                        <tr>
                            <td colspan="5"  style="border: 1px solid; padding:12px; font-weight: 700; text-align: right;"> Net Total </td>
                            <td align="right" style="border: 1px solid; padding:12px; font-weight: 700; text-align: center;">        '.$refundTotal. '</td>
                        </tr>
                        
                    </tfoot>
                </table>';
        $output .= '
                    
                    <table align="right" style="padding-top: 2em;">
                        <tr>
                            <td style="padding-right: 2em; width: 100px; text-align: right;">
                                <h4 style="padding-right: 2em; padding-top: 4px; width: 100px; text-align: right;border-top: 1px solid #ddd;">Signature</h4>
                            </td>
                        </tr>
                    </table>
                    
                    ';

        return $output;
    }












    // expense report
    public function expenseToday() {
        if(auth()->user()->role_id <= 2) {
            $branches = Branch::all();
            $setting = PharmacySetting::active()->where('id',1)->firstOrFail();
            
            $todayExpense = Expense::orderBy('id', 'DESC')->whereDate('expense_date', Carbon::today()->format('y-m-d'))->paginate(20);

            $todayExpenseTotal = Expense::orderBy('id', 'DESC')->whereDate('expense_date', Carbon::today()->format('y-m-d'))->sum('expense_amount');

            return view('admin.reports.expense-today', compact('todayExpense', 'todayExpenseTotal', 'setting', 'branches'));
        }

        $branch = Branch::where('id', auth()->user()->branch->id)->firstOrFail();

        $todayExpense = Expense::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->whereDate('expense_date', Carbon::today()->format('y-m-d'))->paginate(20);

        $todayExpenseTotal = Expense::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->whereDate('expense_date', Carbon::today()->format('y-m-d'))->sum('expense_amount');

        return view('admin.reports.expense-today', compact('todayExpense', 'todayExpenseTotal', 'branch'));
    }

    public function expenseSearch(Request $request) {
        if(auth()->user()->role_id <= 2) {
            $this->validate($request, [
                'branch' => 'required',
                'from' => 'date|required_with:to',
                'to' => 'date|required_with:from',
            ], [
                'branch.required' => 'Please enter branch name!',
                'from.date' => 'Please enter a valid start date!',
                'to.date' => 'Please enter a valid end date!',
            ]);


            $branches = Branch::all();
            $setting = PharmacySetting::active()->where('id',1)->firstOrFail();

            switch ($request->input('action')) {
                case 'search':
                    $from = $request['from']; 
                    $to = $request['to'];

                    if($request['branch'] != "All") {
                        $branch = Branch::where('branch_code', $request['branch'])->firstOrFail();
                        $expenses = Expense::where('branch_id', $branch->id)->whereBetween('expense_date', [$from, $to])->paginate(20);
                        $expenseTotal = Expense::where('branch_id', $branch->id)->whereBetween('expense_date', [$from, $to])->sum('expense_amount');
                        $dispenser = $branch->user->name;

                        return view('admin.reports.expense', compact('expenses', 'expenseTotal', 'branch', 'branches', 'setting', 'dispenser'));
                    }else {
                        $branch = $request['branch'];
                        $expenses = Expense::whereBetween('expense_date', [$from, $to])->paginate(20);
                        $expenseTotal = Expense::whereBetween('expense_date', [$from, $to])->sum('expense_amount');
                        return view('admin.reports.expense', compact('expenses', 'expenseTotal', 'branch', 'branches', 'setting'));
                    }
                    break;
        
                case 'pdf':
                    $from = $request['from']; 
                    $to = $request['to'];
                    $fromDate = Carbon::parse($from)->format('d-M, Y');
                    $toDate = Carbon::parse($to)->format('d-M, Y');
                    
                    if($request['branch'] != "All") {
                        $branch = Branch::where('branch_code', $request['branch'])->firstOrFail();
                        $expenses = Expense::where('branch_id', $branch->id)->whereBetween('expense_date', [$from, $to])->cursor();
                        $expenseTotal = Expense::where('branch_id', $branch->id)->whereBetween('expense_date', [$from, $to])->sum('expense_amount');
                        $dispenser = $branch->user->name;

                        $pdf = \App::make('dompdf.wrapper');
                        $pdf->loadHTML($this->convert_expense_data_to_html($branch, $expenses, $expenseTotal, $fromDate, $toDate, $dispenser));
                        return $pdf->stream();
                    }else {
                        $branch = $request['branch'];
                        $expenses = Expense::whereBetween('expense_date', [$from, $to])->cursor();
                        $expenseTotal = Expense::whereBetween('expense_date', [$from, $to])->sum('expense_amount');

                        $pdf = \App::make('dompdf.wrapper');
                        $pdf->loadHTML($this->convert_all_expense_data_to_html($expenses, $expenseTotal, $setting, $fromDate, $toDate));
                        return $pdf->stream();
                    }
                    break;
            }
        }










        $this->validate($request, [
            'from' => 'date|required_with:to',
            'to' => 'date|required_with:from',
        ], [
            'from.date' => 'Please enter a valid start date!',
            'to.date' => 'Please enter a valid end date!',
        ]);

        $branch = Branch::where('id', auth()->user()->branch->id)->firstOrFail();
        
        switch ($request->input('action')) {
            case 'search':
                $from = $request['from']; 
                $to = $request['to'];
                $fromDate = Carbon::parse($from)->format('d-M, Y');
                $toDate = Carbon::parse($to)->format('d-M, Y');

                $expenses = Expense::where('branch_id', $branch->id)->whereBetween('expense_date', [$from, $to])->paginate(20);
                $expenseTotal = Expense::where('branch_id', $branch->id)->whereBetween('expense_date', [$from, $to])->sum('expense_amount');
                $dispenser = auth()->user()->name;

                return view('admin.reports.expense', compact('expenses', 'expenseTotal', 'branch', 'dispenser'));
                
                break;
    
            case 'pdf':
                $from = $request['from']; 
                $to = $request['to'];
                $fromDate = Carbon::parse($from)->format('d-M, Y');
                $toDate = Carbon::parse($to)->format('d-M, Y');

                $expenses = Expense::where('branch_id', $branch->id)->whereBetween('expense_date', [$from, $to])->cursor();
                $expenseTotal = Expense::where('branch_id', $branch->id)->whereBetween('expense_date', [$from, $to])->sum('expense_amount');
                $dispenser = auth()->user()->name;

                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($this->convert_expense_data_to_html($branch, $expenses, $expenseTotal, $fromDate, $toDate, $dispenser));
                return $pdf->stream();

                break;
        }



    }


    function convert_expense_data_to_html($branch, $expenses, $expenseTotal, $fromDate, $toDate, $dispenser) {
        $output = '
                <h3 align="center">
                    <div> '.$branch->branch_title.' </div>
                    <div> '.$branch->branch_address.' </div>
                    <div> Print Date: '.Carbon::now()->format('d M, Y | h:i:s a').' </div>
                    <div> From '.$fromDate.' To '.$toDate.' </div>
                    <div> Branch Operator : '.$dispenser.' </div>
                </h3>
                <table width="100%" style="border-collapse: collapse; border: 0px;">
                    <tr>
                        <th style="border: 1px solid; padding:12px;" width="15%">SL</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Date</th>
                        <th style="border: 1px solid; padding:12px;" width="15%">Branch Code</th>
                        <th style="border: 1px solid; padding:12px;" width="25%">Net Total</th>
                    </tr>';  

        foreach($expenses as $key=>$expense) {
        $output .= '
            <tbody>
                <tr>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.++$key.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$expense->expense_date.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$expense->branch->branch_code.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.$expense->expense_amount.'</td>
                </tr>
            <tbody>';
        }

        $output .= '
                    <tfoot>
                        <tr>
                            <td colspan="3"  style="border: 1px solid; padding:12px; font-weight: 700; text-align: right;"> Net Total </td>
                            <td align="right" style="border: 1px solid; padding:12px; font-weight: 700; text-align: center;">        '.$expenseTotal. '</td>
                        </tr>
                        
                    </tfoot>
                </table>';
        $output .= ' 
                <table align="right" style="padding-top: 2em;">
                    <tr>
                        <td style="padding-right: 2em; width: 100px; text-align: right;">
                            <h4 style="padding-right: 2em; padding-top: 4px; width: 100px; text-align: right;border-top: 1px solid #ddd;">Signature</h4>
                        </td>
                    </tr>
                </table>';

        return $output;
    }

    function convert_all_expense_data_to_html($expenses, $expenseTotal, $setting, $fromDate, $toDate) {
        $output = '
                <h3 align="center">
                    <div> '.$setting->ph_name.' </div>
                    <div> '.$setting->ph_address.' </div>
                    <div> Print Date: '.Carbon::now()->format('d M, Y | h:i:s a').' </div>
                    <div> From '.$fromDate.' To '.$toDate.' </div>
                </h3>
                <table width="100%" style="border-collapse: collapse; border: 0px;">
                    <tr>
                        <th style="border: 1px solid; padding:12px;" width="15%">SL</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Date</th>
                        <th style="border: 1px solid; padding:12px;" width="15%">Branch Code</th>
                        <th style="border: 1px solid; padding:12px;" width="25%">Net Total</th>
                    </tr>';  

        foreach($expenses as $key=>$expense) {
        $output .= '
            <tbody>
                <tr>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.++$key.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$expense->expense_date.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$expense->branch->branch_code.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.$expense->expense_amount.'</td>
                </tr>
            <tbody>';
        }

        $output .= '
                    <tfoot>
                        <tr>
                            <td colspan="3"  style="border: 1px solid; padding:12px; font-weight: 700; text-align: right;"> Net Total </td>
                            <td align="right" style="border: 1px solid; padding:12px; font-weight: 700; text-align: center;">        '.$expenseTotal. '</td>
                        </tr>
                        
                    </tfoot>
                </table>';
        $output .= '
                    
                    <table align="right" style="padding-top: 2em;">
                        <tr>
                            <td style="padding-right: 2em; width: 100px; text-align: right;">
                                <h4 style="padding-right: 2em; padding-top: 4px; width: 100px; text-align: right;border-top: 1px solid #ddd;">Signature</h4>
                            </td>
                        </tr>
                    </table>
                    
                    ';

        return $output;
    }












    // sale report
    public function profitMonth() {
        $month = Carbon::now()->format('m');
        $fullMonth= Carbon::now()->format('F');
        $year = Carbon::now()->format('Y');

        if(auth()->user()->role_id <= 2) {
            $branches = Branch::all();
            $setting = PharmacySetting::active()->where('id',1)->firstOrFail();
            
            $saleTotal = Invoice::orderBy('id', 'DESC')->whereMonth('invoice_date', '=', $month)->whereYear('invoice_date', '=', $year)->sum('invoice_nettotal');

            $purchaseTotal = Purchase::orderBy('id', 'DESC')->whereMonth('purchase_date', '=', $month)->whereYear('purchase_date', '=', $year)->sum('purchase_nettotal');

            $refundTotal = Refund::orderBy('id', 'DESC')->whereMonth('refund_date', '=', $month)->whereYear('refund_date', '=', $year)->sum('refund_nettotal');

            $expenseTotal = Expense::orderBy('id', 'DESC')->whereMonth('expense_date', '=', $month)->whereYear('expense_date', '=', $year)->sum('expense_amount');

            $allGoing = $purchaseTotal + $refundTotal + $expenseTotal;

            return view('admin.reports.profit-month', compact('saleTotal', 'purchaseTotal', 'refundTotal', 'expenseTotal', 'allGoing', 'setting', 'branches'));
        }

        $branch = Branch::where('id', auth()->user()->branch->id)->firstOrFail();
        $dispenser = auth()->user()->name;
        
        $saleTotal = Invoice::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->whereMonth('invoice_date', '=', $month)->whereYear('invoice_date', '=', $year)->sum('invoice_nettotal');

        $purchaseTotal = Purchase::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->whereMonth('purchase_date', '=', $month)->whereYear('purchase_date', '=', $year)->sum('purchase_nettotal');

        $refundTotal = Refund::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->whereMonth('refund_date', '=', $month)->whereYear('refund_date', '=', $year)->sum('refund_nettotal');

        $expenseTotal = Expense::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->whereMonth('expense_date', '=', $month)->whereYear('expense_date', '=', $year)->sum('expense_amount');

        $allGoing = $purchaseTotal + $refundTotal + $expenseTotal;


        return view('admin.reports.profit-month', compact('saleTotal', 'purchaseTotal', 'refundTotal', 'expenseTotal', 'allGoing', 'branch', 'dispenser'));
    }

    public function profitSearch(Request $request) {
        if(auth()->user()->role_id <= 2) {
            $this->validate($request, [
                'branch' => 'required',
                'from' => 'date|required_with:to',
                'to' => 'date|required_with:from',
            ], [
                'branch.required' => 'Please enter branch name!',
                'from.date' => 'Please enter a valid start date!',
                'to.date' => 'Please enter a valid end date!',
            ]);


            $branches = Branch::all();
            $setting = PharmacySetting::active()->where('id',1)->firstOrFail();

            switch ($request->input('action')) {
                case 'search':
                    $from = $request['from']; 
                    $to = $request['to'];

                    if($request['branch'] != "All") {
                        $branch = Branch::where('branch_code', $request['branch'])->firstOrFail();

                        $saleTotal = Invoice::where('branch_id', $branch->id)->whereBetween('invoice_date', [$from, $to])->sum('invoice_nettotal');

                        $purchaseTotal = Purchase::where('branch_id', $branch->id)->whereBetween('purchase_date', [$from, $to])->sum('purchase_nettotal');

                        $refundTotal = Refund::where('branch_id', $branch->id)->whereBetween('refund_date', [$from, $to])->sum('refund_nettotal');

                        $expenseTotal = Expense::where('branch_id', $branch->id)->whereBetween('expense_date', [$from, $to])->sum('expense_amount');

                        $allGoing = $purchaseTotal + $refundTotal + $expenseTotal;
                        $dispenser = $branch->user->name;
                        
                        return view('admin.reports.profit', compact('saleTotal', 'purchaseTotal', 'refundTotal', 'expenseTotal', 'allGoing', 'branch', 'branches', 'setting', 'dispenser'));
                    }else {
                        $branch = $request['branch'];
                       
                        $saleTotal = Invoice::whereBetween('invoice_date', [$from, $to])->sum('invoice_nettotal');

                        $purchaseTotal = Purchase::whereBetween('purchase_date', [$from, $to])->sum('purchase_nettotal');

                        $refundTotal = Refund::whereBetween('refund_date', [$from, $to])->sum('refund_nettotal');

                        $expenseTotal = Expense::whereBetween('expense_date', [$from, $to])->sum('expense_amount');

                        $allGoing = $purchaseTotal + $refundTotal + $expenseTotal;

                        return view('admin.reports.profit', compact('saleTotal', 'purchaseTotal', 'refundTotal', 'expenseTotal', 'allGoing', 'branch', 'branches', 'setting'));
                    }
                    break;
        
                case 'pdf':
                    $from = $request['from']; 
                    $to = $request['to'];
                    $fromDate = Carbon::parse($from)->format('d-M, Y');
                    $toDate = Carbon::parse($to)->format('d-M, Y');
                    
                    if($request['branch'] != "All") {
                        $branch = Branch::where('branch_code', $request['branch'])->firstOrFail();
                        
                        $saleTotal = Invoice::where('branch_id', $branch->id)->whereBetween('invoice_date', [$from, $to])->sum('invoice_nettotal');

                        $purchaseTotal = Purchase::where('branch_id', $branch->id)->whereBetween('purchase_date', [$from, $to])->sum('purchase_nettotal');

                        $refundTotal = Refund::where('branch_id', $branch->id)->whereBetween('refund_date', [$from, $to])->sum('refund_nettotal');

                        $expenseTotal = Expense::where('branch_id', $branch->id)->whereBetween('expense_date', [$from, $to])->sum('expense_amount');

                        $allGoing = $purchaseTotal + $refundTotal + $expenseTotal;
                        $dispenser = $branch->user->name;
                        

                        $pdf = \App::make('dompdf.wrapper');
                        $pdf->loadHTML($this->convert_profit_data_to_html($branch, $saleTotal, $purchaseTotal, $refundTotal, $expenseTotal, $allGoing, $fromDate, $toDate, $dispenser));
                        return $pdf->stream();
                    }else {
                        $branch = $request['branch'];
                        
                        $saleTotal = Invoice::whereBetween('invoice_date', [$from, $to])->sum('invoice_nettotal');

                        $purchaseTotal = Purchase::whereBetween('purchase_date', [$from, $to])->sum('purchase_nettotal');

                        $refundTotal = Refund::whereBetween('refund_date', [$from, $to])->sum('refund_nettotal');

                        $expenseTotal = Expense::whereBetween('expense_date', [$from, $to])->sum('expense_amount');

                        $allGoing = $purchaseTotal + $refundTotal + $expenseTotal;

                        $pdf = \App::make('dompdf.wrapper');
                        $pdf->loadHTML($this->convert_all_profit_data_to_html($saleTotal, $purchaseTotal, $refundTotal, $expenseTotal, $allGoing, $setting, $fromDate, $toDate));
                        return $pdf->stream();
                    }
                    break;
            }
        }







        
        $this->validate($request, [
            'from' => 'date|required_with:to',
            'to' => 'date|required_with:from',
        ], [
            'from.date' => 'Please enter a valid start date!',
            'to.date' => 'Please enter a valid end date!',
        ]);

        $branch = Branch::where('id', auth()->user()->branch->id)->firstOrFail();
        
        switch ($request->input('action')) {
            case 'search':
                $from = $request['from']; 
                $to = $request['to'];
                $fromDate = Carbon::parse($from)->format('y-m-d');
                $toDate = Carbon::parse($to)->format('y-m-d');


                $saleTotal = Invoice::where('branch_id', $branch->id)->whereBetween('invoice_date', [$from, $to])->sum('invoice_nettotal');

                $purchaseTotal = Purchase::where('branch_id', $branch->id)->whereBetween('purchase_date', [$from, $to])->sum('purchase_nettotal');

                $refundTotal = Refund::where('branch_id', $branch->id)->whereBetween('refund_date', [$from, $to])->sum('refund_nettotal');

                $expenseTotal = Expense::where('branch_id', $branch->id)->whereBetween('expense_date', [$from, $to])->sum('expense_amount');

                $allGoing = $purchaseTotal + $refundTotal + $expenseTotal;
                $dispenser = auth()->user()->name;


                return view('admin.reports.profit', compact('saleTotal', 'purchaseTotal', 'refundTotal', 'expenseTotal', 'allGoing', 'branch', 'dispenser'));
                
                break;
    
            case 'pdf':
                $from = $request['from']; 
                $to = $request['to'];
                $fromDate = Carbon::parse($from)->format('y-m-d');
                $toDate = Carbon::parse($to)->format('y-m-d');

                
            
                $saleTotal = Invoice::where('branch_id', $branch->id)->whereBetween('invoice_date', [$from, $to])->sum('invoice_nettotal');

                $purchaseTotal = Purchase::where('branch_id', $branch->id)->whereBetween('purchase_date', [$from, $to])->sum('purchase_nettotal');

                $refundTotal = Refund::where('branch_id', $branch->id)->whereBetween('refund_date', [$from, $to])->sum('refund_nettotal');

                $expenseTotal = Expense::where('branch_id', $branch->id)->whereBetween('expense_date', [$from, $to])->sum('expense_amount');

                $allGoing = $purchaseTotal + $refundTotal + $expenseTotal;
                $dispenser = auth()->user()->name;

                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($this->convert_profit_data_to_html($branch, $saleTotal, $purchaseTotal, $refundTotal, $expenseTotal, $allGoing, $fromDate, $toDate, $dispenser));
                return $pdf->stream();
            
                break;
        }


    }


    function convert_profit_data_to_html($branch, $saleTotal, $purchaseTotal, $refundTotal, $expenseTotal, $allGoing, $fromDate, $toDate, $dispenser) {

        if($saleTotal != $allGoing) {
            if($saleTotal > $allGoing) {
                $profit = $saleTotal - $allGoing;
            } else {
                $profit = 0.00;
            }

            if($saleTotal < $allGoing) {
                $loss = $allGoing - $saleTotal;
            } else {
                $loss = 0.00;
            }

            $neutral = 0.00;
        }else {
            $profit = 0.00;
            $loss = 0.00;
            $neutral =  "Neutral";
        }

        $output = '
                <h3 align="center">
                    <div> '.$branch->branch_title.' </div>
                    <div> '.$branch->branch_address.' </div>
                    <div> Print Date: '.Carbon::now()->format('d M, Y | h:i:s a').' </div>
                    <div> From '.$fromDate.' To '.$toDate.' </div>
                    <div> Branch Operator : '.$dispenser.' </div>
                </h3>
                <table width="100%" style="border-collapse: collapse; border: 0px;">
                    <tr>
                        <th style="border: 1px solid; padding:12px;" width="15%">Purchases</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Expenses</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Refunds</th>
                        <th style="border: 1px solid; padding:12px;" width="15%">Sales</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Profit</th>
                        <th style="border: 1px solid; padding:12px;" width="25%">Loss</th>
                        <th style="border: 1px solid; padding:12px;" width="25%">Neutral</th>
                    </tr>';  

        
        $output .= '
            <tbody>
                <tr>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.++$purchaseTotal.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="30%">'.$expenseTotal.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$refundTotal.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$saleTotal.'</td>
                    

                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$profit.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.$loss.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.$neutral.'</td>

                </tr>
            <tbody>';
        

        $output .= '</table>';
        $output .= ' 
                <table align="right" style="padding-top: 2em;">
                    <tr>
                        <td style="padding-right: 2em; width: 100px; text-align: right;">
                            <h4 style="padding-right: 2em; padding-top: 4px; width: 100px; text-align: right;border-top: 1px solid #ddd;">Signature</h4>
                        </td>
                    </tr>
                </table>';

        return $output;
    }

    function convert_all_profit_data_to_html($saleTotal, $purchaseTotal, $refundTotal, $expenseTotal, $allGoing, $setting, $fromDate, $toDate) {

        if($saleTotal != $allGoing) {
            if($saleTotal > $allGoing) {
                $profit = $saleTotal - $allGoing;
            } else {
                $profit = 0.00;
            }

            if($saleTotal < $allGoing) {
                $loss = $allGoing - $saleTotal;
            } else {
                $loss = 0.00;
            }

            $neutral = 0.00;
        }else {
            $profit = 0.00;
            $loss = 0.00;
            $neutral =  "Neutral";
        }

        $output = '
                <h3 align="center">
                    <div> '.$setting->ph_name.' </div>
                    <div> '.$setting->ph_address.' </div>
                    <div> Print Date: '.Carbon::now()->format('d M, Y | h:i:s a').' </div>
                    <div> From '.$fromDate.' To '.$toDate.' </div>
                </h3>
                <table width="100%" style="border-collapse: collapse; border: 0px;">
                    <tr>
                        <th style="border: 1px solid; padding:12px;" width="15%">Purchases</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Expenses</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Refunds</th>
                        <th style="border: 1px solid; padding:12px;" width="15%">Sales</th>
                        <th style="border: 1px solid; padding:12px;" width="20%">Profit</th>
                        <th style="border: 1px solid; padding:12px;" width="25%">Loss</th>
                        <th style="border: 1px solid; padding:12px;" width="25%">Neutral</th>
                    </tr>';  

        
        $output .= '
            <tbody>
                <tr>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.++$purchaseTotal.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="30%">'.$expenseTotal.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$refundTotal.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$saleTotal.'</td>
                    

                    <td style="border: 1px solid; padding:12px; text-align: center;" width="15%">'.$profit.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.$loss.'</td>
                    <td style="border: 1px solid; padding:12px; text-align: center;" width="20%">'.$neutral.'</td>
                </tr>
            <tbody>';
        

        $output .= '</table>';
        $output .= '
                    
                    <table align="right" style="padding-top: 2em;">
                        <tr>
                            <td style="padding-right: 2em; width: 100px; text-align: right;">
                                <h4 style="padding-right: 2em; padding-top: 4px; width: 100px; text-align: right;border-top: 1px solid #ddd;">Signature</h4>
                            </td>
                        </tr>
                    </table>
                    
                    ';

        return $output;
    }





}
