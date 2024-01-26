<?php

namespace App\Http\Controllers;

use App\Expense;
use Carbon\Carbon;
use App\ExpenseCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));

        if(auth()->user()->role_id <= 2) {
            if($request->ajax()) {
                $data = Expense::orderBy('id', 'DESC')->get(['expenses.*', 
                        DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
                
                    return DataTables::of($data)
                            ->addColumn('sl', function($data) {
                                return $data->rownum;
                            })
                            ->addColumn('branch', function($data) {
                                return $data->branch->branch_code;
                            })
                            ->addColumn('expense details', function($data) {
                                return $data->expense_details;
                            })
                            ->addColumn('expensecategory_id', function($data) {
                                return $data->expenseCategory->expcate_name;
                            })
                            ->addColumn('date', function($data) {
                                return $data->expense_date;
                            })
                            ->addColumn('amount', function($data) {
                                return $data->expense_amount;
                            })

                            ->addColumn('action', function($data){
                                $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                                $a .= '<a href="'. route('admin.expenses.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';

                                $a .= '&nbsp;&nbsp;<a href="'. route('admin.expenses.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';

                                $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.expense.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                                
                                return $a;
                            })

                            ->rawColumns(['action'])
                            ->make(true);
            }
        }


        if($request->ajax()) {
            $data = Expense::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->get(['expenses.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            
                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('branch', function($data) {
                            return $data->branch->branch_code;
                        })
                        ->addColumn('expense details', function($data) {
                            return $data->expense_details;
                        })
                        ->addColumn('expensecategory_id', function($data) {
                            return $data->expenseCategory->expcate_name;
                        })
                        ->addColumn('date', function($data) {
                            return $data->expense_date;
                        })
                        ->addColumn('amount', function($data) {
                            return $data->expense_amount;
                        })

                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '<a href="'. route('admin.expenses.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="'. route('admin.expenses.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.expense.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                            
                            return $a;
                        })

                        ->rawColumns(['action'])
                        ->make(true);
        }
     
        return view('admin.expense.index');
    }


    public function create() {
        $allExpcate = ExpenseCategory::all();
        return view('admin.expense.create', compact('allExpcate'));
    }

 
    public function store(Request $request) {
        $request->validate([
            'expense_details' => 'required',
            'expensecategory_id' => 'required',
            'expense_amount' => 'required',
            'expense_date' => 'required',
        ],[
            'expense_details.required' => 'Please enter expense details',
            'expensecategory_id.required' => 'Please enter expense vategory',
            'expense_amount.required' => 'Please enter expense amount',
            'expense_date.required' => 'Please enter expense date',
        ]);
        
        $insert = Expense::insert([
            'expense_details' => $request['expense_details'],
            'expensecategory_id' => $request['expensecategory_id'],
            'expense_amount' => $request['expense_amount'],
            'expense_date' => $request['expense_date'],
            'branch_id' => auth()->user()->branch->id,
            'user_id' => auth()->user()->id,
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);

        if($insert) {
            return response()->json(['success' => 'Expense successfully added!']);
        }else {
            return response()->json(['error' => 'Opps! please try again!']);
        }
    }


    public function show(Expense $expense) {
        if(auth()->user()->role_id <= 2) {
            return view('admin.expense.show', compact('expense'));    
        }
        if($expense->branch_id != auth()->user()->branch->id) {
            abort('403', 'Permission Denied!');
        }
        return view('admin.expense.show', compact('expense'));
    }


    public function edit(Expense $expense) {
        $allExpcate = ExpenseCategory::all();
        if(auth()->user()->role_id <= 2) {
            return view('admin.expense.edit', compact('expense', 'allExpcate'));    
        }
        if($expense->branch_id != auth()->user()->branch->id) {
            abort('403', 'Permission Denied!');
        }
        return view('admin.expense.edit', compact('expense', 'allExpcate'));
    }


    public function update(Request $request, Expense $expense) {
        $request->validate([
            'expense_details' => 'required',
            'expensecategory_id' => 'required',
            'expense_amount' => 'required',
            'expense_date' => 'required',
        ],[
            'expense_details.required' => 'Please enter expense details',
            'expensecategory_id.required' => 'Please enter expense vategory',
            'expense_amount.required' => 'Please enter expense amount',
            'expense_date.required' => 'Please enter expense date',
        ]);
        
        $insert = Expense::where('id', $expense->id)->update([
            'expense_details' => $request['expense_details'],
            'expensecategory_id' => $request['expensecategory_id'],
            'expense_amount' => $request['expense_amount'],
            'expense_date' => $request['expense_date'],
            'branch_id' => auth()->user()->branch->id,
            'user_id' => auth()->user()->id,
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        if($insert) {
            return response()->json(['success' => 'Expense successfully updated!']);
        }else {
            return response()->json(['error' => 'Opps! please try again!']);
        }
    }

 
    public function destroy(Request $request) {
        if(auth()->user()->role_id <= 2) {
            $destroy = Expense::destroy($request->id);

            if($destroy){
                return response()->json(['success' => 'Expense(s) successfully deleted!']);
            }else{
                return response()->json(['error' => 'An error occurred! Please try again!']);
            }
        }

        $expenses = Expense::whereIn('id', $request->id)->get();
        foreach($expenses as $key=>$expense) {
            if($expense->branch_id != auth()->user()->branch->id) {
                abort('403', 'Permission Denied!');
            }
        }
        
        $destroy = Expense::destroy($request->id);            


        if($destroy){
            return response()->json(['success' => 'Expense(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
        
    }


    public function restore(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }
        
        $restore = Expense::whereIn('id', $request->id)->restore();        

        if($restore){
            return response()->json(['success' => 'Expense(s) successfully restored!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
        
    }

    public function forceDelete(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }    

        $forceDelete = Expense::whereIn('id', $request->id)->forceDelete(); 

        if($forceDelete){
            return response()->json(['success' => 'Expense(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }
}
