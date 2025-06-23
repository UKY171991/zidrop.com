<?php

namespace App\Http\Controllers\Admin;

use App\ParcelTypeDescribe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Parcel;
use DB;
use Toastr;



class ParcelTypeDescribeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parcelTypeDescribes = ParcelTypeDescribe::with('parcelType')->get();
        return view('backEnd.parcel-type-describe.index', compact('parcelTypeDescribes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'parcel_type_id' => 'required',
            'description' => 'required|max:10000',
            'status' => 'required',
        ]);

        $parcelTypeDescribe = new ParcelTypeDescribe();
        $parcelTypeDescribe->parcel_type_id = $request->parcel_type_id;
        $parcelTypeDescribe->description = $request->description;
        $parcelTypeDescribe->status = $request->status;
        $parcelTypeDescribe->save();

        Toastr::success('message', 'Parcel Type Describe Added Successfully!');
        return redirect()->back()->with('message', 'Parcel Type Describe Added Successfully');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(ParcelTypeDescribe $parcelTypeDescribe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParcelTypeDescribe $parcelTypeDescribe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'parcel_type_id' => 'required',
            'description' => 'required|max:10000',
            'status' => 'required',
        ]);
        $parcelTypeDescribe =  ParcelTypeDescribe::find($request->hidden_id);
        $parcelTypeDescribe->parcel_type_id = $request->parcel_type_id;
        $parcelTypeDescribe->description = $request->description;
        $parcelTypeDescribe->status = $request->status;
        $parcelTypeDescribe->save();

        Toastr::success('message', 'Parcel Type Describe Updated Successfully!');
        return redirect()->back()->with('message', 'Parcel Type Describe Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParcelTypeDescribe $parcelTypeDescribe)
    {
        $parcelTypeDescribe->delete();
        Toastr::success('message', 'Parcel Type Describe Deleted Successfully!');
        return redirect()->back()->with('message', 'Parcel Type Describe Deleted Successfully');
    }
}
