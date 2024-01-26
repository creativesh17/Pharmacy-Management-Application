<?php

namespace App\Http\Controllers;

use App\User;
use App\Branch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('superadmin');
    }

    public function index(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));

        if($request->ajax()) {
            $data = Branch::orderBy('id', 'DESC')->get(['branches.*', DB::raw('@rownum := @rownum + 1 AS rownum')]);

                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('title', function($data) {
                            return $data->branch_title;
                        })
                        ->addColumn('code', function($data) {
                            return $data->branch_code;
                        })
                        ->addColumn('dispenser', function($data) {
                            return $data->user->name;
                        })
                        ->addColumn('phone', function($data) {
                            return $data->branch_phone;
                        })
                        ->addColumn('address', function($data) {
                            return $data->branch_address;
                        })
                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '<a href="'. route('admin.branches.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="'. route('admin.branches.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.branch.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                            
                            return $a;
                        })

                        ->rawColumns(['action'])
                        ->make(true);
                        // ->toJson();
        }
        return view('admin.branch.index');
    }

 
    public function create() {
        $allUser = User::where('status', 1)->orderBy('id', 'ASC')->get();
        return view('admin.branch.create', compact('allUser'));
    }


    public function store(Request $request) {
        $request->validate([
            'branch_title' => 'required|string|max:255',
            'branch_start_date' => 'required|date',
            'branch_code' => 'required|string|max:255|',
            'user_id' => 'required',
            'branch_phone' => 'required|string|max:60',
            'branch_address' => 'required',
        ],[
            'branch_title.required' => 'Please enter branch title!',
            'branch_start_date.required' => 'Please enter branch start date!',
            'branch_code.required' => 'Please enter branch code!',
            'user_id.required' => 'Please enter supervisor name!',
            'branch_phone.required' => 'Please enter phone number!',
            'branch_address.required' => 'Please enter branch address!',
        ]);

        $branch = Branch::insertGetId([
            'branch_title' => $request['branch_title'],
            'branch_start_date' => $request['branch_start_date'],
            'branch_code' => $request['branch_code'],
            'user_id' => $request['user_id'],
            'branch_phone' => $request['branch_phone'],
            'branch_address' => $request['branch_address'],
            'branch_note' => $request['branch_note'],
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);

        if($branch) {
            return response()->json(['success' => 'Branch successfully created!']);
        }else {
            return response()->json(['error' => 'Opps! please try again!']);
        }
    }

    public function show(Branch $branch) {
        return view('admin.branch.show', compact('branch'));
    }


    public function edit(Branch $branch) {
        $allUser = User::where('status', 1)->orderBy('id', 'ASC')->get();
        return view('admin.branch.edit', compact('branch', 'allUser'));
    }


    public function update(Request $request, Branch $branch) {
        $request->validate([
            'branch_title' => 'required|string|max:255',
            'branch_phone' => 'required|string|max:60|unique:branches,branch_phone,' . $branch->id,
            'branch_code' => 'required',
            'user_id' => 'required',
            'branch_start_date' => 'required',
            'branch_address' => 'required',
        ],[
            'branch_title.required' => 'Please enter branch title!',
            'branch_phone.required' => 'Please enter phone number!',
            'branch_code.required' => 'Please enter branch code!',
            'user_id.required' => 'Please enter supervisor name!',
            'branch_start_date.required' => 'Please enter branch start date!',
            'branch_address.required' => 'Please enter branch address!',
        ]);
        
        
        $update = Branch::where('id', $branch->id)->update([
            'branch_title' => $request['branch_title'],
            'branch_start_date' => $request['branch_start_date'],
            'branch_code' => $request['branch_code'],
            'user_id' => $request['user_id'],
            'branch_phone' => $request['branch_phone'],
            'branch_address' => $request['branch_address'],
            'branch_note' => $request['branch_note'],
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        if($update) {
            return response()->json(['success' => 'Branch information successfully updated!']);
        }else {
            return response()->json(['error' => 'Opps! please try again!']);
        }
    }

 
    public function destroy(Request $request, Branch $branch) {
        $destroy = Branch::destroy($request->id);

        if($destroy){
            return response()->json(['success' => 'Branch(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

    public function changeStatus(Request $request) {
        $branch = Branch::findOrFail($request->id);
        $branch->branch_status = $request->status;
        $branch->updated_at = Carbon::now()->toDateTimeString();
        $branch->save();
  
        return response()->json(['success' => 'Branch status changed successfully.']);
    }


    public function restore(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }

        $restore = Branch::whereIn('id', $request->id)->restore();        

        if($restore){
            return response()->json(['success' => 'Branch(es) successfully restored!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }


    public function forceDelete(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }

        $forceDelete = Branch::whereIn('id', $request->id)->forceDelete(); 

        if($forceDelete){
            return response()->json(['success' => 'Branch(es) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

    public function autosearch(Request $request) {
        // $search = $request->search;

        // if($search == ''){
        //     $data = User::orderby('name', 'ASC')->select('id', 'name')->limit(5)->get();
        // }else{
        //     $data = User::orderby('name', 'ASC')->select('id', 'name')->where('name', 'like', '%' .$search . '%')->get();
        // }

        // $response = array();
        // foreach($data as $row){
        //     $response[] = array("value"=>$row->id, "label"=>$row->name);
        // }
        // echo json_encode($response);
        // exit;

        // =========================

       

        $data = [];

        if($request->has('q')){
            $search = $request->q;
            
            $data = User::select("id", "name")
                          ->orderBy('id', 'DESC')
                          ->where('role_id', 3)
                          ->where('name', 'LIKE', "%$search%")
                          ->get();
        }

        return response()->json($data);
        
    }
}
