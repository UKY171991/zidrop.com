<?php

namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StatisticsDetails;
use Brian2694\Toastr\Facades\Toastr;

class StatisticsDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $StatisticsDetails = StatisticsDetails::where('is_active', 1)->get();
        return view('backEnd.statistics.index', compact('StatisticsDetails'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backEnd.statistics.createOredit');

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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $statisticDetail = StatisticsDetails::where('id', $id)->first();
        return view('backEnd.statistics.createOredit', compact('statisticDetail'));

    }


      
    public function update(Request $request, $id)
    {
        $request->validate([
            'total_delivery' => 'nullable|numeric|min:0', // Changed 'integer' to 'numeric'
            'total_customers' => 'nullable|numeric|min:0', // Changed 'integer' to 'numeric'
            'total_years' => 'nullable|numeric|min:0',     // Changed 'integer' to 'numeric'
            'total_member' => 'nullable|numeric|min:0',    // Changed 'integer' to 'numeric'
            // Add validation rules for other fields as needed
        ]);
        $statisticDetail = StatisticsDetails::find($id); 
        $statisticDetail->total_delivery =  $request->total_delivery;
        $statisticDetail->total_customers =  $request->total_customers;
        $statisticDetail->total_years =  $request->total_years;
        $statisticDetail->total_member =  $request->total_member;
        $statisticDetail->save();
        // dd($statisticDetail);

    
       
        Toastr::success('message', 'Statistic detail updated successfully.');

        return redirect()->route('statistics-details.index') ;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
