<?php

namespace App\Http\Controllers;

use App\User;
use App\Staff;
use App\Branch;
use App\Refund;
use App\Expense;
use App\Invoice;
use App\Purchase;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Charts\IncomeExpenseChart;

class DashboardController extends Controller {
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index() {
        $date = Carbon::now()->format('d');
        $month = Carbon::now()->format('m');
        $fullMonth= Carbon::now()->format('F');
        $year = Carbon::now()->format('Y');


        $allUsers = User::all()->count();
        $allStaffs = Staff::all()->count();
        $allSuppliers = Supplier::all()->count();


        $allSales = Invoice::whereDate('invoice_date', '=', Carbon::today()->toDateTimeString())->sum('invoice_nettotal');
        $allPurchases = Purchase::whereDate('purchase_date', '=', Carbon::today()->toDateTimeString())->sum('purchase_nettotal');
        $allRefunds = Refund::whereDate('refund_date', '=', Carbon::today()->toDateTimeString())->sum('refund_nettotal');
        $allExpenses = Expense::whereDate('expense_date', '=', Carbon::today()->toDateTimeString())->sum('expense_amount');
        $allPurchaseExpense = $allPurchases + $allExpenses;



        $sevenSales = Invoice::where('invoice_date', '>', Carbon::today()->subDays(7))->sum('invoice_nettotal');
        $sevenPurchases = Purchase::where('purchase_date', '>', Carbon::today()->subDays(7))->sum('purchase_nettotal');
        $sevenRefunds = Refund::where('refund_date', '>', Carbon::today()->subDays(7))->sum('refund_nettotal');
        $sevenExpenses = Expense::where('expense_date', '>', Carbon::today()->subDays(7))->sum('expense_amount');
        $sevenPurchaseExpense = $sevenPurchases + $sevenExpenses;
        $sevenPurchaseExpRefund = $sevenPurchases + $sevenExpenses + $sevenRefunds;


        $monthSales = Invoice::where('invoice_date', '>', Carbon::today()->subDays(30))->sum('invoice_nettotal');
        $monthPurchases = Purchase::where('purchase_date', '>', Carbon::today()->subDays(30))->sum('purchase_nettotal');
        $monthRefunds = Refund::where('refund_date', '>', Carbon::today()->subDays(30))->sum('refund_nettotal');
        $monthExpenses = Expense::where('expense_date', '>', Carbon::today()->subDays(30))->sum('expense_amount');
        $monthPurchaseExpense = $monthPurchases + $monthExpenses;
        $allMonthPurchaseExpRefund = $monthPurchases + $monthRefunds + $monthExpenses;

        
        $chartBarMonth = new IncomeExpenseChart;
        $chartBarMonth->labels(['Income', 'Expense']);
        $chartBarMonth->dataset('Last 30 Days Income/Expense', 'bar', [$monthSales, $allMonthPurchaseExpRefund])->backgroundcolor(['#098', '#0f7196']);



        $yearSales = Invoice::where('invoice_date', '>', Carbon::today()->subDays(365))->sum('invoice_nettotal');
        $yearPurchases = Purchase::where('purchase_date', '>', Carbon::today()->subDays(365))->sum('purchase_nettotal');
        $yearRefunds = Refund::where('refund_date', '>', Carbon::today()->subDays(365))->sum('refund_nettotal');
        $yearExpenses = Expense::where('expense_date', '>', Carbon::today()->subDays(365))->sum('expense_amount');
        $yearPurchaseExpense = $monthPurchases + $monthExpenses;
        $allYearPurchaseExpRefund = $monthPurchases + $monthRefunds + $monthExpenses;

        $chartBarYear = new IncomeExpenseChart;
        $chartBarYear->labels(['Income', 'Expense']);
        $chartBarYear->dataset('Last 365 Days Income/Expense', 'bar', [$yearSales, $allYearPurchaseExpRefund])->backgroundcolor(['#098', '#0f7196']);



        return view('admin.dashboard.dashboard', compact('allUsers', 'allStaffs', 'allSuppliers', 'allSales', 'allPurchases', 'allRefunds', 'allExpenses', 'allPurchaseExpense', 'sevenSales', 'sevenPurchases', 'sevenRefunds', 'sevenExpenses', 'sevenPurchaseExpense', 'sevenPurchaseExpRefund', 'monthSales', 'monthPurchases', 'monthRefunds', 'monthExpenses', 'monthPurchaseExpense', 'allMonthPurchaseExpRefund',  'yearSales', 'yearPurchases', 'yearRefunds', 'yearExpenses', 'yearPurchaseExpense', 'allYearPurchaseExpRefund',  'chartBarMonth', 'chartBarYear'));
    }

    public function branchwise() {
        $branches = Branch::all();
        return view('admin.dashboard.branchwise', compact('branches'));
    }

    public function branchwiseSearch(Request $request) {
        // return $request->branch_code;
        $date = Carbon::now()->format('d');
        $month = Carbon::now()->format('m');
        $fullMonth= Carbon::now()->format('F');
        $year = Carbon::now()->format('Y');

        $search = $request->branch_code;
        $branch = Branch::where('branch_status', 1)
                            ->where('id', $search)
                            ->firstOrFail();

        
        $user = $branch->user->id;
        $branch = $branch->id;

        $allStaffs = Staff::where('branch_id', $branch)->count();
        $allSuppliers = Supplier::all()->count();


        $allSales = Invoice::where('branch_id', $branch)->whereDate('invoice_date', '=', Carbon::today()->toDateTimeString())->sum('invoice_nettotal');
        $allPurchases = Purchase::where('branch_id', $branch)->whereDate('purchase_date', '=', Carbon::today()->toDateTimeString())->sum('purchase_nettotal');
        $allRefunds = Refund::where('branch_id', $branch)->whereDate('refund_date', '=', Carbon::today()->toDateTimeString())->sum('refund_nettotal');
        $allExpenses = Expense::where('branch_id', $branch)->whereDate('expense_date', '=', Carbon::today()->toDateTimeString())->sum('expense_amount');
        $allPurchaseExpense = $allPurchases + $allExpenses;



        $sevenSales = Invoice::where('branch_id', $branch)->where('invoice_date', '>', Carbon::today()->subDays(7))->sum('invoice_nettotal');
        $sevenPurchases = Purchase::where('branch_id', $branch)->where('purchase_date', '>', Carbon::today()->subDays(7))->sum('purchase_nettotal');
        $sevenRefunds = Refund::where('branch_id', $branch)->where('refund_date', '>', Carbon::today()->subDays(7))->sum('refund_nettotal');
        $sevenExpenses = Expense::where('branch_id', $branch)->where('expense_date', '>', Carbon::today()->subDays(7))->sum('expense_amount');
        $sevenPurchaseExpense = $sevenPurchases + $sevenExpenses;
        $sevenPurchaseExpRefund = $sevenPurchases + $sevenExpenses + $sevenRefunds;


        $monthSales = Invoice::where('branch_id', $branch)->where('invoice_date', '>', Carbon::today()->subDays(30))->sum('invoice_nettotal');
        $monthPurchases = Purchase::where('branch_id', $branch)->where('purchase_date', '>', Carbon::today()->subDays(30))->sum('purchase_nettotal');
        $monthRefunds = Refund::where('branch_id', $branch)->where('refund_date', '>', Carbon::today()->subDays(30))->sum('refund_nettotal');
        $monthExpenses = Expense::where('branch_id', $branch)->where('expense_date', '>', Carbon::today()->subDays(30))->sum('expense_amount');
        $monthPurchaseExpense = $monthPurchases + $monthExpenses;
        $allMonthPurchaseExpRefund = $monthPurchases + $monthRefunds + $monthExpenses;

        
        $chartBarMonth = new IncomeExpenseChart;
        $chartBarMonth->labels(['Income', 'Expense']);
        $chartBarMonth->dataset('Last 30 Days Income/Expense', 'bar', [$monthSales, $allMonthPurchaseExpRefund])->backgroundcolor(['#098', '#0f7196']);



        $yearSales = Invoice::where('branch_id', $branch)->where('invoice_date', '>', Carbon::today()->subDays(365))->sum('invoice_nettotal');
        $yearPurchases = Purchase::where('branch_id', $branch)->where('purchase_date', '>', Carbon::today()->subDays(365))->sum('purchase_nettotal');
        $yearRefunds = Refund::where('branch_id', $branch)->where('refund_date', '>', Carbon::today()->subDays(365))->sum('refund_nettotal');
        $yearExpenses = Expense::where('branch_id', $branch)->where('expense_date', '>', Carbon::today()->subDays(365))->sum('expense_amount');
        $yearPurchaseExpense = $monthPurchases + $monthExpenses;
        $allYearPurchaseExpRefund = $monthPurchases + $monthRefunds + $monthExpenses;

        $chartBarYear = new IncomeExpenseChart;
        $chartBarYear->labels(['Income', 'Expense']);
        $chartBarYear->dataset('Last 365 Days Income/Expense', 'bar', [$yearSales, $allYearPurchaseExpRefund])->backgroundcolor(['#098', '#0f7196']);



        return view('admin.dashboard.branch-dashboard', compact('allStaffs', 'allSuppliers', 'allSales', 'allPurchases', 'allRefunds', 'allExpenses', 'allPurchaseExpense', 'sevenSales', 'sevenPurchases', 'sevenRefunds', 'sevenExpenses', 'sevenPurchaseExpense', 'sevenPurchaseExpRefund', 'monthSales', 'monthPurchases', 'monthRefunds', 'monthExpenses', 'monthPurchaseExpense', 'allMonthPurchaseExpRefund', 'yearSales', 'yearPurchases', 'yearRefunds', 'yearExpenses', 'yearPurchaseExpense', 'allYearPurchaseExpRefund', 'chartBarMonth', 'chartBarYear'));
    }
}
