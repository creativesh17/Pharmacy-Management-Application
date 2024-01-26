<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Manufacturer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ManufacturerController extends Controller
{
    public function __construct() {
        $this->middleware('auth'); 
    }

    public function index(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));
        
        if($request->ajax()) {
            $data = Manufacturer::orderBy('id', 'DESC')->get(['manufacturers.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);

                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('name', function($data) {
                            return $data->manu_name;
                        })
                        ->addColumn('note', function($data) {
                            return $data->manu_note;
                        })

                        ->addColumn('action', function($data){
                            if(Auth::user()->role_id <= 2) { 
                                $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                                $a .= '&nbsp;&nbsp;<a href="'. route('admin.manufacturers.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';

                                $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.manufacturer.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                            }else {

                                $a = '&nbsp;&nbsp;<a href="'. route('admin.manufacturers.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon edit-margin-icon"></i></a>';
                            }
                            
                            return $a;
                        })

                        ->rawColumns(['action'])
                        ->make(true);
                        // ->toJson();
        }
        return view('admin.manufacturer.index');
    }


    public function create() {
        return view('admin.manufacturer.create');
    }


    public function store(Request $request) {
        $request->validate([
            'manu_name' => 'required|string|max:255|unique:manufacturers',
        ],[
            'manu_name.required' => 'Please enter manufacturer name!',
        ]);

        $create = Manufacturer::create([
            'manu_name' => $request['manu_name'],
            'manu_note' => $request['manu_note'],
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);

        if($create) {
            return response()->json(['success' => 'Manufacturer successfully added!']);
        }else {
            return response()->json(['error' => 'Opps! please try again!']);
        }
    }

 
    public function edit(Manufacturer $manufacturer) {
        return view('admin.manufacturer.edit', compact('manufacturer'));
    }


    public function update(Request $request, Manufacturer $manufacturer) {
        $request->validate([
            'manu_name' => 'required|string|max:255|unique:manufacturers,manu_name,' . $manufacturer->id,
        ],[
            'manu_name.required' => 'Please enter manufacturer name!',
        ]);
        
        
        $update = Manufacturer::where('id', $manufacturer->id)->update([
            'manu_name' => $request['manu_name'],
            'manu_note' => $request['manu_note'],
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);


        if($update) {
            return response()->json(['success' => 'Manufacturer successfully updated!']);
        }else {
            return response()->json(['error' => 'Opps! please try again!']);
        }
    }

 
    public function destroy(Request $request) {
        if(Auth::user()->role_id >= 3) {
            abort('403', 'No permission to delete');
        }
        $destroy = Manufacturer::destroy($request->id);

        if($destroy){
            return response()->json(['success' => 'Manufacturer(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

    public function restore(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }

        $restore = Manufacturer::whereIn('id', $request->id)->restore();        

        if($restore){
            return response()->json(['success' => 'Manufacturer(s) successfully restored!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

    public function forceDelete(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }
        
        $forceDelete = Manufacturer::whereIn('id', $request->id)->forceDelete(); 

        if($forceDelete){
            return response()->json(['success' => 'Manufacturer(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }
}
