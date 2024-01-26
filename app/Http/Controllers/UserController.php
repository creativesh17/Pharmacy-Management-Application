<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('superadmin');
    }

    public function index(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));
        
        if($request->ajax()) {
            $data = User::orderBy('id', 'DESC')->get(['users.*', 
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
                        ->addColumn('role', function($data) {
                            return $data->role->role_name;
                        })
                        ->addColumn('photo', function($data) {
                            if($data->photo != '')
                                return '<img class="table-image-40" src="'.Storage::disk('public')->url('users/'.$data->photo).'" alt="user-photo"/>';

                                return '<img class="table-image-40" src="'.Storage::disk('public')->url('avatar.png').'" alt="user-photo"/>';
                        })

                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '<a href="'. route('admin.users.show', $data->username) .'"><i class="fa fa-plus-square fa-lg view-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="'. route('admin.users.edit', $data->username) .'" <i class="fa fa-pencil-square fa-lg edit-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.user.destroy') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                            
                            return $a;
                        })

                        // ->setRowClass(function ($data) {
                        //     return $data->id % 2 == 0 ? 'alert alert-secondary' : 'alert alert-secondary';
                        // })

                        // ->removeColumn('id')

                        ->rawColumns(['action', 'photo'])
                        ->make(true);
                        // ->toJson();
        }
        return view('admin.user.index');
    }

    public function create() {
        $allRole = Role::where('role_status', 1)->orderBy('id', 'ASC')->get();
        return view('admin.user.create', compact('allRole'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:40|unique:users',
            'username' => 'required|string|max:20|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required',
            'cadd' => 'required',
            'padd' => 'required',
            'nid' => 'required|unique:users',
            'photo' => 'required|image',
        ],[
            'name.required' => 'Please enter name!',
            'phone.required' => 'Please enter phone!',
            'username.required' => 'Please enter username!',
            'email.required' => 'Please enter email address!',
            'password.required' => 'Please enter password!',
            'role.required' => 'Please select user role!',
            'cadd.required' => 'Please enter current address!',
            'padd.required' => 'Please select permanent address!',
            'nid.required' => 'Please enter national id!',
            'photo.required' => 'Please enter a photo!',
        ]);

        $insert = User::insertGetId([
            'name' => $request['name'],
            'phone' => $request['phone'],
            'username' => $request['username'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'photo' => '',
            'role_id' => $request['role'],
            'nid' => $request['nid'],
            'current_address' => $request['cadd'],
            'permanent_address' => $request['padd'],
            'note' => $request['note'],
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);

        if($request->hasFile('photo')){
            $image = $request->file('photo');
            $imageName = 'user_'.$insert.'_'.time().'.'.$image->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('users')) {
                Storage::disk('public')->makeDirectory('users');
            }
            
            $userImage = Image::make($image)->fit(250, 250)->save($image->getClientOriginalExtension());
            Storage::disk('public')->put('users/'.$imageName,$userImage);

            User::where('id', $insert)->update([
                'photo' => $imageName,
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
        }

        if($insert) {
            return response()->json(['success' => 'User successfully registered!']);
        }else {
            return response()->json(['error' => 'Opps! please try again!']);
        }
    }

    public function show(User $user) {
        return view('admin.user.show', compact('user'));
    }

    public function edit(User $user) {
        $allRole = Role::where('role_status', 1)->orderBy('id', 'ASC')->get();
        return view('admin.user.edit', compact('user', 'allRole'));
    }

    public function update(Request $request, User $user) {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:40|unique:users,phone,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required',
            'cadd' => 'required',
            'padd' => 'required',
            'photo' => 'image',
        ],[
            'name.required' => 'Please enter name!',
            'phone.required' => 'Please enter phone number!',
            'email.required' => 'Please enter email address!',
            'role.required' => 'Please select user role!',
            'cadd.required' => 'Please enter current address!',
            'padd.required' => 'Please select permanent address!',
        ]);
        
        
        $update = User::where('id', $user->id)->update([
            'name' => $request['name'],
            'phone' => $request['phone'],
            'email' => $request['email'],
            'role_id' => $request['role'],
            'current_address' => $request['cadd'],
            'permanent_address' => $request['padd'],
            'note' => $request['note'],
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        if($request->hasFile('photo')){
            $image = $request->file('photo');
            $imageName = 'user_'.$update.'_'.time().'.'.$image->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('users')) {
                Storage::disk('public')->makeDirectory('users');
            }

            if(Storage::disk('public')->exists('users/'.$user->photo)) {
                Storage::disk('public')->delete('users/'.$user->photo);
            }
            
            $userImage = Image::make($image)->fit(250, 250)->save($image->getClientOriginalExtension());
            Storage::disk('public')->put('users/'.$imageName,$userImage);

            User::where('id', $user->id)->update([
                'photo' => $imageName,
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
        }

        if($update) {
            return response()->json(['success' => 'User successfully updated!']);
        }else {
            return response()->json(['error' => 'Opps! please try again!']);
        }

    }

    public function destroy(Request $request) {
        $destroy = User::destroy($request->id);

        if($destroy){
            return response()->json(['success' => 'User(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

    public function changeStatus(Request $request) {
        $user = User::find($request->id);
        $user->status = $request->status;
        $user->updated_at = Carbon::now()->toDateTimeString();
        $user->save();
  
        return response()->json(['success'=>'User status changed successfully.']);
    }

    public function restore(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }

        $restore = User::whereIn('id', $request->id)->restore();        

        if($restore){
            return response()->json(['success' => 'User(s) successfully restored!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

    public function forceDelete(Request $request) {
        if(auth()->user()->role_id != 1) {
            abort('403', 'Permission Denied!');
        }

        $users = DB::table('users')
                    ->whereIn('id', $request->id)
                    ->get();

        foreach ($users as $user) {
            if(Storage::disk('public')->exists('users/'.$user->photo)){
                Storage::disk('public')->delete('users/'.$user->photo);
            }
        }

        $forceDelete = User::whereIn('id', $request->id)->forceDelete(); 

        if($forceDelete){
            return response()->json(['success' => 'User(s) successfully deleted!']);
        }else{
            return response()->json(['error' => 'An error occurred! Please try again!']);
        }
    }

    
}
