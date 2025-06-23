<?php

namespace App\Http\Controllers\Admin;

use App\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = City::orderBy('id', 'desc')->get();
        return view('backEnd.city.index', compact('cities'));
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
        $this->validate($request, [
            'title' => 'required',
        ]);

        $city = new City();
        $city->title = $request->title;
        $city->slug = strtolower(str_replace(' ', '-', $request->title));
        $city->save();
        Toastr::success('City Created Successfully', 'Success');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(City $city)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'id' => 'required',
        ]);

        $city = City::findOrFail($request->id);
        $city->title = $request->title;
        $city->slug = strtolower(str_replace(' ', '-', $request->title));
        $city->save();
        Toastr::success('City Updated Successfully', 'Success');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        //
    }
   
}
