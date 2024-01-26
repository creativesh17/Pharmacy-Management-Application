<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        return view('admin.profile.index');
    }

    public function updateProfile(Request $request) {
        $user = User::findOrFail(Auth::id());

        if($user->email == $request['email']) {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'photo' => 'image',
            ], [
                'name.required' => 'Please enter your name!',
                'email.required' => 'Please enter your email!',
            ]);
        }else {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'photo' => 'image',
            ], [
                'name.required' => 'Please enter your name!',
                'email.required' => 'Please enter your email!',
            ]);
        }

        $profile = User::where('id', $user->id)->update([
            'name' => $request['name'],
            'email' => $request['email'],
            'note' => $request['note'],
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        if($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = 'profile_'.$user->id."_".uniqid()."_".time().".".$image->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('profile')) {
                Storage::disk('public')->makeDirectory('profile');
            }

            if(Storage::disk('public')->exists('profile/'.$user->profile_photo)) {
                Storage::disk('public')->delete('profile/'.$user->profile_photo);
            }

            $profileImage = Image::make($image)->fit(250, 250)->save($image->getClientOriginalExtension());

            Storage::disk('public')->put('profile/'.$imageName, $profileImage);

        }else {
            $imageName = $user->profile_photo;
        }

        User::where('id', $user->id)->update([
            'profile_photo' => $imageName,
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        if($profile) {
            return response()->json(['success' => 'Profile successfully updated!']);
        }else {
            return response()->json(['error' => 'Opps! please try again!']);
        }
    }

    public function updatePassword(Request $request) {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required',
            'pass_confirm' => 'required|same:password',
        ]);

        $hashedPass = Auth::user()->password;

        if(Hash::check($request->old_password, $hashedPass)) {
            if(!Hash::check($request->password, $hashedPass)) {
                $user = User::find(Auth::id());
                $pass = User::where('id', $user->id)->update([
                    'password' => Hash::make($request['password']),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ]);
                if($pass) {
                    Auth::logout();
                    Session::flush();
                    return response()->json(['success' => 'Password successfully changed!']);
                }
            }else {
                return response()->json(['error' => 'New password can not be the same as old one!']);
            }
        }else {
            return response()->json(['error' => 'Old password is wrong!']);
        }
    }
}
