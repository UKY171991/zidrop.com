<?php

namespace App\Http\Controllers\Superadmin;

use App\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Setting;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::first();
        return view('backEnd.adminsettings.index', compact('settings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        return false;
       $setting = Setting::first();
       $setting->agent_create_parcel = $request->agent_create_parcel;
       $setting->save();
       return response()->json(['success' => true, 'message' => 'Settings updated successfully']);
    }

    
   
}
