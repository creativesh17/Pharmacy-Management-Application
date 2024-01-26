<?php

namespace App\Http\Controllers;

use App\Medicine;
use Carbon\Carbon;
use App\Manufacturer;
use App\MedicineCategory;
use Illuminate\Http\Request;
use App\Imports\MedicinesImport;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MedicineController extends Controller
{
    public function index(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));
        
        if($request->ajax()) {
            $data = Medicine::orderBy('id', 'DESC')->get(['medicines.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);

                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('name', function($data) {
                            return $data->med_name;
                        })
                        ->addColumn('generic name', function($data) {
                            return $data->generic_name;
                        })
                        ->addColumn('category', function($data) {
                            return optional($data->category)->cate_name;
                        })
                        ->addColumn('manufacturer', function($data) {
                            return optional($data->manufacturer)->manu_name;
                        })
                        ->addColumn('sell_price', function($data) {
                            return optional($data)->sell_price;
                        })
                        ->addColumn('note', function($data) {
                            return $data->note;
                        })

                        ->addColumn('action', function($data) {
                            if(Auth::user()->role_id <= 2) {
                                $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                                $a .= '<a href="'. route('admin.medicines.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';

                                $a .= '&nbsp;&nbsp;<a href="'. route('admin.medicines.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';
                                
                                $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.medicine.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                                
                                
                            } else {
                                $a = '<a href="'. route('admin.medicines.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';

                                $a .= '&nbsp;&nbsp;<a href="'. route('admin.medicines.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';
                                
                                if(Auth::user()->role_id <= 2) { 
                                    $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.medicine.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                                }
                                
                            }
                            
                            return $a;
                        })

                        ->rawColumns(['action'])
                        ->make(true);
                        // ->toJson();
        }
        return view('admin.medicine.index');
    }


    public function create() {
        $medCategories = MedicineCategory::orderBy('id', 'ASC')->get();
        $manufacturers = Manufacturer::orderBy('id', 'ASC')->get();
        return view('admin.medicine.create', compact('medCategories', 'manufacturers'));
    }


    public function store(Request $request) {
        $request->validate([
            'med_name' => 'required|string|max:255|unique:medicines',
            'medicinecategory_id' => 'required',
            'manufacturer_id' => 'required',
            'sell_price' => 'nullable',
        ],[
            'med_name.required' => 'Please enter medicine name!',
            'medicinecategory_id.required' => 'Please enter medicine category!',
            'manufacturer_id.required' => 'Please enter medicine manufacturer!',
        ]);

        $create = Medicine::insert([
            'med_name' => $request['med_name'],
            'generic_name' => $request['generic_name'] ?: '',
            'medicinecategory_id' => $request['medicinecategory_id'] ?: '',
            'manufacturer_id' => $request['manufacturer_id'] ?: '',
            'sell_price' => $request['sell_price'] ?: '',
            'note' => $request['note'],
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);

        if($create) {
            return response()->json(['success' => 'Medicine successfully added!']);
        }else {
            return response()->json(['error' => 'Opps! please try again!']);
        }
    }

    public function getImportCsv() {
        return view('admin.medicine.create-medicine-csv');
    }

    public function postImportCsv(Request $request) {
        $errors = [];

        // correct one
        if ($request->hasFile('file')) {
            $medicinesImport = new MedicinesImport();
            $medicinesImport->import($request->file('file'));
            foreach ($medicinesImport->failures() as $failure) {
                foreach ($failure->errors() as $error) {
                    $errors[] = $error;
                }
            }

            $medicineValidationErrors = $medicinesImport->getErrors();
            $errors = array_merge($errors, $medicineValidationErrors);
            if(!empty($errors)) {
                return redirect()->route('admin.medicines.index')
                                ->with('success', 'Success!')
                                ->with('errors', $errors);
            }else {
                return redirect()->route('admin.medicines.index')
                                ->with('success', 'Success!');
            }
            
        }else {
            return redirect()->back();
        }
    }


    public function show(Medicine $medicine) {
        return view('admin.medicine.show', compact('medicine'));
    }

    public function edit(Medicine $medicine) {
        $medCategories = MedicineCategory::orderBy('id', 'ASC')->get();
        $manufacturers = Manufacturer::orderBy('id', 'ASC')->get();
        return view('admin.medicine.edit', compact('medicine', 'medCategories', 'manufacturers'));
    }


    public function update(Request $request, Medicine $medicine) {
        $request->validate([
            'med_name' => 'required|string|max:255|unique:medicines,med_name,' . $medicine->id,
            'medicinecategory_id' => 'required',
            'manufacturer_id' => 'required',
        ],[
            'med_name.required' => 'Please enter medicine name!',
            'medicinecategory_id.required' => 'Please enter medicine category!',
            'manufacturer_id.required' => 'Please enter medicine manufacturer!',
        ]);

        
        
        $update = Medicine::where('id', $medicine->id)->update([
            'med_name' => $request['med_name'],
            'generic_name' => $request['generic_name'],
            'medicinecategory_id' => $request['medicinecategory_id'],
            'manufacturer_id' => $request['manufacturer_id'],
            'note' => $request['note'],
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
        
        $destroy = Medicine::destroy($request->id);

        if($destroy){
            return response()->json(['success' => 'Medicine(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

    public function changeStatus(Request $request) {
        if(Auth::user()->role_id >= 3) {
            abort('403', 'No permission to delete');
        }
        $medicine = Medicine::find($request->id);
        $medicine->med_status = $request->med_status;
        $medicine->updated_at = Carbon::now()->toDateTimeString();
        $medicine->save();
  
        return response()->json(['success'=>'Medicine status changed successfully.']);
    }

    public function restore(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }

        $restore = Medicine::whereIn('id', $request->id)->restore();        

        if($restore){
            return response()->json(['success' => 'Medicine(s) successfully restored!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

    public function forceDelete(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }
        
        $forceDelete = Medicine::whereIn('id', $request->id)->forceDelete(); 

        if($forceDelete){
            return response()->json(['success' => 'Medicine(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }
}
