<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    */

    use AuthenticatesUsers;

    // protected $redirectTo = '/admin';
    protected $redirectTo;

    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo() {
        if(Auth::check() && Auth::user()->role_id <= 2) {
            return '/admin';
        }else{
            return 'admin/dispenser';
        }
    }

    protected function credentials(Request $request) {
        if(is_numeric($request->get('email'))){
            return ['phone'=>$request->get('email'), 'password'=>$request->get('password'), 'status' => 1];
        }elseif (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            return ['email' => $request->get('email'), 'password'=>$request->get('password'), 'status' => 1];
        }
        return ['username' => $request->get('email'), 'password'=>$request->get('password'), 'status' => 1];
    }

    public function authenticated() {     
        Auth::logoutOtherDevices(request('password'));

        if(Auth::check() && Auth::user()->role_id <= 2) {
            return redirect()->route('admin.dashboard');
        }else{
            return redirect()->route('dispenser.dashboard');
        }

        return back();
    } 

    protected function loggedOut(Request $request) {
        return back();
    }

}
