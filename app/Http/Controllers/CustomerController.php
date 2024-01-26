<?php

namespace App\Http\Controllers;

use App\Customer;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function __construct() {
        $this->middleware('auth'); 
    }

     public function index(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));
        
        if($request->ajax()) {
            $data = Customer::orderBy('id', 'DESC')->get(['customers.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);

                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('name', function($data) {
                            return $data->customer_name;
                        })
                        ->addColumn('phone', function($data) {
                            return $data->customer_phone;
                        })
                        ->addColumn('email', function($data) {
                            return $data->customer_email;
                        })
                        ->addColumn('added by', function($data) {
                            return $data->user->name ?: null;
                        })
                        ->addColumn('addrress', function($data) {
                            return $data->customer_address;
                        })
                        ->addColumn('note', function($data) {
                            return Str::words($data->customer_note, 3);
                        })

                        ->addColumn('action', function($data){
                            if(auth()->user()->role_id <= 2) {
                                $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                                $a .= '&nbsp;&nbsp;<a href="'. route('admin.customers.show', $data->id) .'" <i class="fa fa-plus-square fa-lg view-icon"></i></a>';

                                $a .= '&nbsp;&nbsp;<a href="'. route('admin.customers.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';
                                
                                $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.customer.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';

                            }else if(auth()->user()->id == $data->user_id) {
                                $a = '&nbsp;&nbsp;<a href="'. route('admin.customers.show', $data->id) .'" <i class="fa fa-plus-square fa-lg view-icon"></i></a>';

                                $a .= '&nbsp;&nbsp;<a href="'. route('admin.customers.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';
                            }else if(auth()->user()->id != $data->user_id) {
                                $a = '&nbsp;&nbsp;<a href="'. route('admin.customers.show', $data->id) .'" <i class="fa fa-plus-square fa-lg view-icon"></i></a>';
                            }
                            
                            return $a;

                        })

                        ->rawColumns(['action'])
                        ->make(true);
        }
        return view('admin.customer.index');
    }


    public function create() {
        return view('admin.customer.create');
    }


    public function store(Request $request) {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:255|unique:customers',
            'customer_email' => 'nullable|string|max:255|unique:customers',
        ],[
            'customer_name.required' => 'Please enter customer name!',
        ]);

        $create = Customer::create([
            'customer_name' => $request['customer_name'],
            'user_id' => auth()->user()->id,
            'customer_phone' => $request['customer_phone'],
            'customer_email' => $request['customer_email'],
            'customer_address' => $request['customer_address'],
            'customer_note' => $request['customer_note'],
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);

        if($create) {
            return response()->json(['success' => 'Customer successfully added!']);
        }else {
            return response()->json(['error' => 'Opps! please try again!']);
        }
    }

 
    public function show(Customer $customer) {
        return view('admin.customer.show', compact('customer'));
    }

    public function edit(Customer $customer) {
        if(auth()->user()->role_id <= 2 || auth()->user()->id == $customer->user_id) {
            return view('admin.customer.edit', compact('customer'));
        }

        if(auth()->user()->id != $customer->user_id) {
            abort('403', 'Permission Denied!');
        }
    }


    public function update(Request $request, Customer $customer) {
        if(auth()->user()->role_id <= 2 || auth()->user()->id == $customer->user_id) {
            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_phone' => 'nullable|string|max:255|unique:customers,customer_phone,' . $customer->id,
                'customer_email' => 'nullable|string|max:255|unique:customers,customer_email,' . $customer->id,
            ],[
                'customer_name.required' => 'Please enter customer name!',
            ]);
            
            
            $update = Customer::where('id', $customer->id)->update([
                'customer_name' => $request['customer_name'],
                'customer_phone' => $request['customer_phone'],
                'customer_email' => $request['customer_email'],
                'customer_address' => $request['customer_address'],
                'customer_note' => $request['customer_note'],
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
    
    
            if($update) {
                return response()->json(['success' => 'Customer successfully updated!']);
            }else {
                return response()->json(['error' => 'Opps! please try again!']);
            }
        }

        if(auth()->user()->id != $customer->user_id) {
            abort('403', 'Permission Denied!');
        }
        
    }


    public function destroy(Request $request) {
        if(Auth::user()->role_id >= 3) {
            abort('403', 'No permission to delete');
        }
        $destroy = Customer::destroy($request->id);

        if($destroy){
            return response()->json(['success' => 'Customer(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

    public function restore(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }

        $restore = Customer::whereIn('id', $request->id)->restore();        

        if($restore){
            return response()->json(['success' => 'Customer(s) successfully restored!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

    public function forceDelete(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }
        
        $forceDelete = Customer::whereIn('id', $request->id)->forceDelete(); 

        if($forceDelete){
            return response()->json(['success' => 'Customer(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }
}
