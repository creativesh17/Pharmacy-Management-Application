<?php

namespace App\Http\Controllers;

use App\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoleController extends Controller {
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('superadmin');
    }

    public function role() {
        $allRole = Role::all();
        return view('admin.role.index', compact('allRole'));
    }

    public function edit($id) {
        $role = Role::findOrFail($id);
        return view('admin.role.edit', compact('role'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'role_name' => 'required|string|max:255',
        ],[
            'role_name.required' => 'Please enter role name!',
        ]);
        
        $role = Role::findOrFail($id);

        $update = Role::where('id', $id)->update([
            'role_name' => $request['role_name'],
            'role_status' => 1,
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
        if($update) {
            return response()->json(['success' => 'Role successfully updated!']);
        }else {
            return response()->json(['error' => 'Opps! please try again!']);
        }

    }
}
