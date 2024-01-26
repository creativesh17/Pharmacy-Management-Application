<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\WebSetting;
use App\PharmacySetting;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller {
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function pharmacysetting(){
        $data = PharmacySetting::active()->where('id',1)->firstOrFail();
        return view('admin.manage.pharmacysetting', compact('data'));
    }

    public function update_pharmacysetting(Request $request){
        $this->validate($request, [
            'ph_name' => 'required|string|max:255',
            'ph_phone' => 'required|string|max:40',
            'ph_email' => 'required|string|email|max:255',
            'ph_address' => 'required',
            'ph_logo' => 'image',
        ],[
            'ph_name.required' => 'Please enter name!',
            'ph_phone.required' => 'Please enter phone number!',
            'ph_email.required' => 'Please enter email address!',
            'ph_address.required' => 'Please enter main address!',
        ]);

        $update = PharmacySetting::active()->where('id', 1)->update([
            'ph_name' => $request['ph_name'],
            'ph_phone' => $request['ph_phone'],
            'ph_email' => $request['ph_email'],
            'ph_address' => $request['ph_address'],
            'ph_about' => $request['ph_about'],
        ]);

        $data = PharmacySetting::active()->where('id', 1)->firstOrFail();

        if($request->hasFile('ph_logo')){
            $image = $request->file('ph_logo');
            $imageName = 'ph_logo'.'_'.time().'.'.$image->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('settings')) {
                Storage::disk('public')->makeDirectory('settings');
            }

            if(Storage::disk('public')->exists('settings/'.$data->ph_logo)) {
                Storage::disk('public')->delete('settings/'.$data->ph_logo);
            }
            
            $pharmacyImage = Image::make($image)->fit(129, 40)->save($image->getClientOriginalExtension());
            Storage::disk('public')->put('settings/'.$imageName,$pharmacyImage);


            PharmacySetting::where('id', $data->id)->update([
                'ph_logo' => $imageName,
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);

        }
            
        if($update){
            return back()->with('success', 'Pharmacy settings updated successfully!');
        }else{
            return back()->with('error', 'Opps! Please try again');
        }
    }

    public function websetting() {
        $data = WebSetting::where('web_status',1)->where('id', 1)->firstOrFail();
        return view('admin.manage.websetting', compact('data'));
    }

    public function update_websetting(Request $request){
        $this->validate($request, [
            'web_title' => 'required|string|max:255',
            'web_subtitle' => 'required',
            'web_favicon' => 'image',
            'web_footer_text' => 'required',
            'web_language' => 'required',
        ],[
            'web_title.required' => 'Please enter website title!',
            'web_subtitle.required' => 'Please enter website subtitle!',
            'web_footer_text.required' => 'Please enter website footer text!',
            'web_language.required' => 'Please enter website language!',
        ]);

        $update = WebSetting::active()->where('id', 1)->update([
            'web_title' => $request['web_title'],
            'web_subtitle' => $request['web_subtitle'],
            'web_footer_text' => $request['web_footer_text'],
            'web_language' => $request['web_language'],
        ]);

        $data = WebSetting::active()->where('id', 1)->firstOrFail();

        if($request->hasFile('web_favicon')){
            $image = $request->file('web_favicon');
            $imageName = 'favicon_'.time().'.'.$image->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('favicon')) {
                Storage::disk('public')->makeDirectory('favicon');
            }

            if(Storage::disk('public')->exists('favicon/'.$data->web_favicon)) {
                Storage::disk('public')->delete('favicon/'.$data->web_favicon);
            }

            $webImage = Image::make($image)->fit(250, 250)->save($image->getClientOriginalExtension());
            Storage::disk('public')->put('favicon/'.$imageName,$webImage);

            WebSetting::where('id', $data->id)->update([
                'web_favicon' => $imageName,
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);

        }
            
        if($update){
            return back()->with('success', 'Pharmacy settings updated successfully!');
        }else{
            return back()->with('error', 'Opps! Please try again');
        }
    }

}
