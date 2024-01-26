<?php

namespace App\Http\Controllers\Dispenser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DispenserController extends Controller {
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('dispenser');
    }

    public function logout() {
        session()->invalidate();
        session()->flush();
        session()->regenerate();
        return back();
    }
}
