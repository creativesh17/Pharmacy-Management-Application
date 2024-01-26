<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\ExpenseCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExpenseCategoryController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin');
    }
    public function index(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));

        
        if($request->ajax()) {
            $data = ExpenseCategory::orderBy('id', 'ASC')->get(['expense_categories.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            
                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('name', function($data) {
                            return $data->expcate_name;
                        })
                        ->addColumn('remarks', function($data) {
                            return $data->expcate_remarks;
                        })
                        ->addColumn('creator', function($data) {
                            return $data->user->name;
                        })

                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '&nbsp;&nbsp;<a href="'. route('admin.expensecategories.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';
                            
                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.expensecategory.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                        
                            return $a;
                        })

                        ->rawColumns(['action'])
                        ->make(true);
        }

        return view('admin.expense-category.index');
        
    }

    public function create() {
        return view('admin.expense-category.create');
    }


    public function store(Request $request) {
        $request->validate([
            'expcate_name' => 'required|string|max:255',
        ],[
            'expcate_name.required' => 'Please enter expense category name!',
        ]);

        $creator = auth()->user()->id;
        $slug = Str::slug($request['expcate_name'], '-');
        $insert = ExpenseCategory::insert([
            'expcate_name' => $request['expcate_name'],
            'expcate_remarks' => $request['expcate_remarks'],
            'user_id' => $creator,
            'expcate_slug' => $slug,
            'created_at' => Carbon::now()->toDateTimeString()
        ]);

        if($insert) {
            return response()->json(['success' => 'Expense Category successfully added!']);
        }else {
            return response()->json(['error' => 'Opps! please try again!']);
        }
    }


    public function edit(ExpenseCategory $expensecategory) {
        return view('admin.expense-category.edit', compact('expensecategory'));
    }

    public function update(Request $request, ExpenseCategory $expensecategory) {
        $request->validate([
            'expcate_name' => 'required|string|max:255|unique:expense_categories,expcate_name,' .$expensecategory->id,
        ],[
            'expcate_name.required' => 'Please enter expense category name!',
            'expcate_name.unique' => 'This category has been taken!',
        ]);

        $slug = Str::slug($request['expcate_name'], '-');
        $update = ExpenseCategory::where('id', $expensecategory->id)->update([
            'expcate_name' => $request['expcate_name'],
            'expcate_remarks' => $request['expcate_remarks'],
            'expcate_slug' => $slug,
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        if($update) {
            return response()->json(['success' => 'Expense Category successfully updated!']);
        }else {
            return response()->json(['error' => 'Opps! please try again!']);
        }

    }


    public function destroy(Request $request) {
        if(auth()->user()->role_id > 2) {
            abort('403', 'Permission Denied!');
        }

        $destroy = ExpenseCategory::destroy($request->id);

        if($destroy){
            return response()->json(['success' => 'Expense Category successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
        
    }


    public function restore(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }
        
        $restore = ExpenseCategory::whereIn('id', $request->id)->restore();        

        if($restore){
            return response()->json(['success' => 'Expense Category successfully restored!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
        
    }

    public function forceDelete(Request $request) {   
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }    

        $forceDelete = ExpenseCategory::whereIn('id', $request->id)->forceDelete(); 

        if($forceDelete){
            return response()->json(['success' => 'Expense Category successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }
}
