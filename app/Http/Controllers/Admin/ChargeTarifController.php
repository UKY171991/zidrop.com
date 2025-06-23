<?php

namespace App\Http\Controllers\Admin;

use App\Branch;
use App\ChargeTarif;
use App\Http\Controllers\Controller;
use App\Imports\ChargeImport;
use App\Merchant;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ChargeTarifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Chargetariffs = ChargeTarif::orderBy('id', 'desc')->with('pickupcity', 'deliverycity')->get();
        return view('backEnd.chargeTarif.index', compact('Chargetariffs'));
    }
    public function upload()
    {
        return view('backEnd.chargeTarif.upload');
    }


    public function uploadSubmit()
    {
        $this->validate(request(), [
            'tarriftable' => 'required|mimes:xls,xlsx',
        ]);
    
        try {
            // ChargeTarif::truncate();
            Excel::import(new ChargeImport, request()->file('tarriftable'));
            // If no exception is thrown during the import, it implies success
            $erMessage = session('importError');
            Session::forget('importError');
            if ($erMessage) {
                Toastr::error($erMessage, 'Error');
                return redirect()->route('admin.charge-tarif');
            }else{
                Toastr::success('Charge Tarif added successfully!', 'Success');
                return redirect()->route('admin.charge-tarif');
            }
        } catch (\Exception $e) {
            // If an exception is caught during the import, it implies failure
            Toastr::error('Failed to add Charge Tarif!', 'Error');
            return redirect()->route('admin.charge-tarif');
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
    public function show(ChargeTarif $chargeTarif)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChargeTarif $chargeTarif)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChargeTarif $chargeTarif)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChargeTarif $chargeTarif)
    {
        //
    }
    public function getTarrif($cityid)
    {
        $chargeTarif = ChargeTarif::with('pickupcity', 'deliverycity')
            ->where('pickup_cities_id', $cityid)
            ->join('cities', 'charge_tarifs.delivery_cities_id', '=', 'cities.id')
            ->orderBy('cities.title', 'ASC')
            ->get();
    
        return response()->json($chargeTarif);
    }
    
    
    
    public function getBranch($cityid)
    {
        $Branches = Branch::where('cities_id', $cityid)->get();
        return response()->json($Branches);
    }
    public function getMerchant($id)
    {
        $Merchant = Merchant::where('id', $id)->get();

        // Check if pickLocation is null and replace with "N/A"
        foreach ($Merchant as $key => $value) {
            if ($value->pickLocation == null) {
                $Merchant->pickLocation = "N/A";
            }
        }

        return response()->json($Merchant);
    }
}
