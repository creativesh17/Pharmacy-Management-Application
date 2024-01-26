<?php

namespace App\Http\Controllers;

use App\Staff;
use App\Branch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function index(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));

        if(auth()->user()->role_id <= 2) {
            if($request->ajax()) {
                $data = Staff::orderBy('id', 'DESC')->get(['staffs.*', 
                        DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
                
                    return DataTables::of($data)
                            ->addColumn('sl', function($data) {
                                return $data->rownum;
                            })
                            ->addColumn('name', function($data) {
                                return $data->name;
                            })
                            ->addColumn('phone', function($data) {
                                return $data->phone;
                            })
                            ->addColumn('email', function($data) {
                                return $data->email;
                            })
                            ->addColumn('branch', function($data) {
                                return $data->branch->branch_code;
                            })
                            ->addColumn('photo', function($data) {
                                if($data->photo != '')
                                    return '<img class="table-image-40" src="'.Storage::disk('public')->url('staffs/'.$data->photo).'" alt="user-photo"/>';

                                    return '<img class="table-image-40" src="'.Storage::disk('public')->url('avatar.png').'" alt="user-photo"/>';
                            })

                            ->addColumn('action', function($data){
                                $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                                $a .= '<a href="'. route('admin.staffs.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';

                                $a .= '&nbsp;&nbsp;<a href="'. route('admin.staffs.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';

                                $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.staff.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                                
                                return $a;
                            })

                            ->rawColumns(['action', 'photo'])
                            ->make(true);
            }
        }


        if($request->ajax()) {
            $data = Staff::orderBy('id', 'DESC')->where('branch_id', auth()->user()->branch->id)->get(['staffs.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            
                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('name', function($data) {
                            return $data->name;
                        })
                        ->addColumn('phone', function($data) {
                            return $data->phone;
                        })
                        ->addColumn('email', function($data) {
                            return $data->email;
                        })
                        ->addColumn('branch', function($data) {
                            return $data->branch->branch_code;
                        })
                        ->addColumn('photo', function($data) {
                            if($data->photo != '')
                                return '<img class="table-image-40" src="'.Storage::disk('public')->url('staffs/'.$data->photo).'" alt="user-photo"/>';

                                return '<img class="table-image-40" src="'.Storage::disk('public')->url('avatar.png').'" alt="user-photo"/>';
                        })

                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '<a href="'. route('admin.staffs.show', $data->id) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="'. route('admin.staffs.edit', $data->id) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';
                            
                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.staff.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                            
                            return $a;
                        })

                        ->rawColumns(['action', 'photo'])
                        ->make(true);
                        // ->toJson();
        }
     
        return view('admin.staff.index');
    }

 
    public function create() {
        $allBranch = Branch::where('branch_status', 1)->orderBy('id', 'ASC')->get();
        return view('admin.staff.create', compact('allBranch'));
    }


    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'joining_date' => 'required|date',
            'phone' => 'required|string|max:40|unique:staffs',
            'email' => 'required|string|email|max:255|unique:staffs',
            'nid' => 'required|unique:staffs',
            'branch_id' => 'required',
            'photo' => 'required|image',
            'cadd' => 'required',
            'padd' => 'required',
        ],[
            'name.required' => 'Please enter name!',
            'joining_date.required' => 'Please enter joining date!',
            'phone.required' => 'Please enter phone!',
            'email.required' => 'Please enter email address!',
            'nid.required' => 'Please enter national id!',
            'branch_id.required' => 'Please select branch code!',
            'photo.required' => 'Please enter a photo!',
            'cadd.required' => 'Please enter current address!',
            'padd.required' => 'Please select permanent address!',
        ]);

        if(auth()->user()->role_id <= 2) {
            $insert = Staff::insertGetId([
                'name' => strtolower($request['name']),
                'joining_date' => $request['joining_date'],
                'phone' => $request['phone'],
                'email' => strtolower($request['email']),
                'branch_id' => $request['branch_id'],
                'photo' => '',
                'nid' => $request['nid'],
                'current_address' => $request['cadd'],
                'permanent_address' => $request['padd'],
                'note' => $request['note'],
                'created_at' => Carbon::now()->toDateTimeString(),
            ]);
        }else {
            $insert = Staff::insertGetId([
                'name' => strtolower($request['name']),
                'joining_date' => $request['joining_date'],
                'phone' => $request['phone'],
                'email' => strtolower($request['email']),
                'branch_id' => auth()->user()->branch->id,
                'photo' => '',
                'nid' => $request['nid'],
                'current_address' => $request['cadd'],
                'permanent_address' => $request['padd'],
                'note' => $request['note'],
                'created_at' => Carbon::now()->toDateTimeString(),
            ]);
        }
        

        if($request->hasFile('photo')){
            $image = $request->file('photo');
            $imageName = 'staff_'.$insert.'_'.time().'.'.$image->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('staffs')) {
                Storage::disk('public')->makeDirectory('staffs');
            }

            $staffImage = Image::make($image)->fit(250, 250)->save($image->getClientOriginalExtension());
            Storage::disk('public')->put('staffs/'.$imageName,$staffImage);


            Staff::where('id', $insert)->update([
                'photo' => $imageName,
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
        }

        if($insert) {
            return response()->json(['success' => 'Staff successfully registered!']);
        }else {
            return response()->json(['error' => 'Opps! please try again!']);
        }
    }

    public function show(Staff $staff) {
        return view('admin.staff.show', compact('staff'));
    }


    public function edit(Staff $staff) {
        $allBranch = Branch::where('branch_status', 1)->orderBy('id', 'ASC')->get();
        return view('admin.staff.edit', compact('staff', 'allBranch'));
    }


    public function update(Request $request, Staff $staff) {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:40|unique:staffs,phone,' . $staff->id,
            'email' => 'required|string|email|max:255|unique:staffs,email,' . $staff->id,
            'nid' => 'required|string|max:255|unique:staffs,nid,' . $staff->id,
            'branch_id' => 'required',
            'cadd' => 'required',
            'padd' => 'required',
            'photo' => 'image',
        ],[
            'name.required' => 'Please enter name!',
            'phone.required' => 'Please enter phone number!',
            'email.required' => 'Please enter email address!',
            'cadd.required' => 'Please enter current address!',
            'padd.required' => 'Please select permanent address!',
        ]);
        

        if(auth()->user()->role_id <= 2) {
            $update = Staff::where('id', $staff->id)->update([
                'name' => $request['name'],
                'joining_date' => $request['joining_date'],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'nid' => $request['nid'],
                'branch_id' => $request['branch_id'],
                'current_address' => $request['cadd'],
                'permanent_address' => $request['padd'],
                'note' => $request['note'],
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
        }else {
            $update = Staff::where('id', $staff->id)->update([
                'name' => $request['name'],
                'joining_date' => $request['joining_date'],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'nid' => $request['nid'],
                'branch_id' => auth()->user()->branch->id,
                'current_address' => $request['cadd'],
                'permanent_address' => $request['padd'],
                'note' => $request['note'],
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
        }

        if($request->hasFile('photo')){
            $image = $request->file('photo');
            $imageName = 'staff_'.$update.'_'.time().'.'.$image->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('staffs')) {
                Storage::disk('public')->makeDirectory('staffs');
            }

            if(Storage::disk('public')->exists('staffs/'.$staff->photo)) {
                Storage::disk('public')->delete('staffs/'.$staff->photo);
            }

            $staffImage = Image::make($image)->fit(250, 250)->save($image->getClientOriginalExtension());
            Storage::disk('public')->put('staffs/'.$imageName,$staffImage);
            

            Staff::where('id', $staff->id)->update([
                'photo' => $imageName,
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
        }

        if($update) {
            return response()->json(['success' => 'Staff successfully updated!']);
        }else {
            return response()->json(['error' => 'Opps! please try again!']);
        }
    }



    public function destroy(Request $request) {
        if(auth()->user()->role_id <= 2) {
            $destroy = Staff::destroy($request->id);

            if($destroy){
                return response()->json(['success' => 'Staff(s) successfully deleted!']);
            }else{
                return response()->json(['error' => 'An error occurred! Please try again!']);
            }
        }


        $staffs = Staff::whereIn('id', $request->id)->get();
        foreach($staffs as $key=>$staff) {
            if($staff->branch_id != auth()->user()->branch->id) {
                abort('403', 'Permission Denied!');
            }
        }


        $destroy = Staff::destroy($request->id);

        if($destroy){
            return response()->json(['success' => 'Staff(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

    public function changeStatus(Request $request) {
        if(auth()->user()->role_id <= 2) {
            $staff = Staff::find($request->id);
            $staff->status = $request->status;
            $staff->updated_at = Carbon::now()->toDateTimeString();
            $staff->save();

            return response()->json(["success" => "Staff's status changed successfully."]);
        }

        $staff = Staff::where('id', $request->id)->firstOrFail();

        if($staff->branch_id != auth()->user()->branch->id) {
            abort('403', 'Permission Denied!');
        }

        $staff = Staff::find($request->id);
        $staff->status = $request->status;
        $staff->updated_at = Carbon::now()->toDateTimeString();
        $staff->save();
  
        return response()->json(["success" => "Staff's status changed successfully."]);
    }

    public function restore(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }
        
        $restore = Staff::whereIn('id', $request->id)->restore();        

        if($restore){
            return response()->json(['success' => 'Staff(s) successfully restored!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
        
    }

    public function forceDelete(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }       

        $staffs = DB::table('staffs')
                    ->whereIn('id', $request->id)
                    ->get();

        foreach ($staffs as $staff) {
            if(Storage::disk('public')->exists('staffs/'.$staff->photo)){
                Storage::disk('public')->delete('staffs/'.$staff->photo);
            }
        }

        $forceDelete = Staff::whereIn('id', $request->id)->forceDelete(); 

        if($forceDelete){
            return response()->json(['success' => 'Staff(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

}
