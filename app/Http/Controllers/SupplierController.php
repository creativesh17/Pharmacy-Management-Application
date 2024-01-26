<?php

namespace App\Http\Controllers;

use App\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));
        
        if($request->ajax()) {
            $data = Supplier::orderBy('id', 'DESC')->get(['suppliers.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);

                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('name', function($data) {
                            return $data->sup_name;
                        })
                        ->addColumn('phone', function($data) {
                            return $data->sup_phone;
                        })
                        ->addColumn('email', function($data) {
                            return $data->sup_email;
                        })
                        ->addColumn('address', function($data) {
                            return $data->sup_address;
                        })
                        ->addColumn('note', function($data) {
                            return $data->sup_note;
                        })

                        ->addColumn('action', function($data){
                            if(Auth::user()->role_id <= 2) {
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '&nbsp;&nbsp;<a href="'. route('admin.suppliers.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.supplier.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                            }else {
                                $a = '&nbsp;&nbsp;<a href="'. route('admin.suppliers.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon edit-margin-icon"></i></a>';
                            }
                            
                            return $a;
                        })

                        ->rawColumns(['action'])
                        ->make(true);
                        // ->toJson();
        }
        return view('admin.supplier.index');
    }


    public function create() {
        return view('admin.supplier.create');
    }


    public function store(Request $request) {
        $request->validate([
            'sup_name' => 'required|string|max:255|unique:suppliers',
            'sup_phone' => 'required|string|max:255|unique:suppliers',
        ],[
            'sup_name.required' => 'Please enter supplier name!',
        ]);

        $supplier = Supplier::create([
            'sup_name' => $request['sup_name'],
            'sup_phone' => $request['sup_phone'],
            'sup_email' => $request['sup_email'],
            'sup_address' => $request['sup_address'],
            'sup_note' => $request['sup_note'],
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);

        if($supplier) {
            return response()->json(['success' => 'Supplier successfully added!']);
        }else {
            return response()->json(['error' => 'Opps! please try again!']);
        }
    }

 
    public function edit(Supplier $supplier) {
        return view('admin.supplier.edit', compact('supplier'));
    }


    public function update(Request $request, Supplier $supplier) {
        $request->validate([
            'sup_name' => 'required|string|max:255|unique:suppliers,sup_name,' . $supplier->id,
            'sup_phone' => 'required|string|max:40|unique:suppliers,sup_phone,' . $supplier->id,
        ],[
            'sup_name.required' => 'Please enter name!',
            'sup_phone.required' => 'Please enter phone number!',
        ]);
        
        
        $update = Supplier::where('id', $supplier->id)->update([
            'sup_name' => $request['sup_name'],
            'sup_phone' => $request['sup_phone'],
            'sup_email' => $request['sup_email'],
            'sup_address' => $request['sup_address'],
            'sup_note' => $request['sup_note'],
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);


        if($update) {
            return response()->json(['success' => 'Supplier successfully updated!']);
        }else {
            return response()->json(['error' => 'Opps! please try again!']);
        }
    }

 
    public function destroy(Request $request) {
        if(Auth::user()->role_id >= 3) {
            abort('403', 'No permission to delete');
        }
        
        $destroy = Supplier::destroy($request->id);

        if($destroy){
            return response()->json(['success' => 'Supplier(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

    public function changeStatus(Request $request) {
        if(Auth::user()->role_id >= 3) {
            abort('403', 'No permission to change status');
        }
        $supplier = Supplier::find($request->id);
        $supplier->sup_status = $request->status;
        $supplier->updated_at = Carbon::now()->toDateTimeString();
        $supplier->save();
  
        return response()->json(['success' => 'Supplier status changed successfully.']);
    }

    public function restore(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }

        $restore = Supplier::whereIn('id', $request->id)->restore();        

        if($restore){
            return response()->json(['success' => 'Supplier(s) successfully restored!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

    public function forceDelete(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }
        
        $forceDelete = Supplier::whereIn('id', $request->id)->forceDelete(); 

        if($forceDelete){
            return response()->json(['success' => 'Supplier(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }
}
