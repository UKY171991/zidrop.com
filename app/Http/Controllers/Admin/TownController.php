<?php

namespace App\Http\Controllers\Admin;

use App\Town;
use Illuminate\Http\Request;
use App\Imports\TownImport;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class TownController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Towns = Town::orderBy('id', 'desc')->with('city')->get();
        return view('backEnd.town.index', compact('Towns'));
    }
    public function upload()
    {
        return view('backEnd.town.upload');
    }
    public function uploadSubmit()
    {
        $this->validate(request(), [
            'tarriftable' => 'required|mimes:xls,xlsx',
        ]);
        try {

            \Excel::import(new TownImport, request()->file('tarriftable'));
    
            // If no exception is thrown during the import, it implies success
            Toastr::success('Town Tarif added successfully!', 'Success');
            return redirect()->route('admin.town-tarif');
        } catch (\Exception $e) {
            // If an exception is caught during the import, it implies failure
            Toastr::error('Failed to add Town Tarif!', 'Error');
            return redirect()->route('admin.town-tarif');
        }
        
       
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Town $town)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Town $town)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Town $town)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Town $town)
    {
        //
    }
    public function getTown($cityid)
    {
        $towns = Town::where('cities_id',$cityid)->orderBy('title','ASC')->get();
        return response()->json($towns);
    }
   
}
