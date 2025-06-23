<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Parcel;
use App\Merchant;
use App\Nearestzone;
use App\Deliverycharge;
use DB;
use App\Topup;
use App\RemainTopup;
use App\Expense;


class ReportController extends Controller
{
    public function salse()
    {
        return view('backEnd.report.salse');
        // $reports = Parcel::latest()->limit(10)->get();
        // $start_date = date('Y-m-d');
        // $end_date = date('Y-m-d');
        // return view('backEnd.report.salesreportdata', compact('reports','start_date','end_date'));

    }
    public function salseSearch(Request $request)
    {
       
        $reports=$this->getSearchReports($request->all());
    
        
        $start_date=$request->start_date ?? date('Y-m-d');
        $end_date=$request->end_date ?? date('Y-m-d');
        if($reports->count() == 0){
            
            return view('backEnd.report.noData');
        }
        if($request->report_type == 'del_charge'){
        
            $head = 'Delivery Charge Report';
            return view('backEnd.report.del_charge', compact('reports','start_date','end_date','head'));
        } elseif($request->report_type == 'cod_charge'){
            $head = 'COD Charge Report';
            return view('backEnd.report.cod_charge', compact('reports','start_date','end_date','head'));
        } elseif($request->report_type == 'tax'){
            $head = 'Tax Report';
            return view('backEnd.report.tax', compact('reports','start_date','end_date','head'));
        } elseif($request->report_type == 'insurance'){
            $head = 'Insurance Report';
            return view('backEnd.report.insurance', compact('reports','start_date','end_date','head'));
        } elseif($request->report_type == 'walet_topup'){
            $head = 'Wallet Report';
            $topup = Topup::with('merchant')
            ->whereBetween('created_at', [$start_date.' 00:00:00', $end_date.' 23:59:59'])
            ->orderBy('created_at', 'DESC')
            ->get();

            if($topup->count() == 0){
            
                return view('backEnd.report.noData');
            }
            return view('backEnd.report.walet', compact('topup', 'start_date','end_date','head'));
        } else {
            $head = 'Delivery Charge Report';
            return view('backEnd.report.del_charge', compact('reports','start_date','end_date','head'));
        }
        
    }

    public function getSearchReports($data){

        $query = \App\Parcel::select('*')
        ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen','agent', 'parceltype'])
        ->orderByDesc('id');
   

        // $query = DB::table('parcels')
        // ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
        // ->join('parceltypes', 'parceltypes.id', '=', 'parcels.status')
        // ->join('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
        // ->join('deliverycharges', 'deliverycharges.id', '=', 'parcels.orderType')
        // ->orderBy('updated_at', 'DESC')
        // ->select('parcels.*', 'deliverycharges.title', 'nearestzones.zonename', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid', 'parceltypes.title as parceltype');
        
        if($data['report_type'] != null){
            if($data['report_type'] == 'del_charge'){
                $query->whereIN('status', [4,6,8]);
                // $query->addSelect(DB::raw("parcels.deliveryCharge as Charge"));
            }
            if($data['report_type'] == 'cod_charge'){
                $query->whereIN('status', [4,6]);
                // $query->addSelect(DB::raw("parcels.codCharge as Charge"));
            }
            if($data['report_type'] == 'tax'){
                $query->whereIN('status', [4,6,8]);
                // $query->addSelect(DB::raw("parcels.tax as Charge"));
            }
            if($data['report_type'] == 'insurance'){
                $query->whereIN('status', [4,6,8]);
                // $query->addSelect(DB::raw("parcels.insurance as Charge"));
            }
            if($data['report_type'] == 'walet_topup'){
                $query->whereIN('status', [4,6,8]);
                // $query->addSelect(DB::raw("parcels.insurance as Charge"));
            }
           
        }
        
        if ($data['start_date'] == null && $data['end_date'] == null) {
            $query->whereDate('updated_at' , today());
        }
        if ($data['start_date'] != null && $data['end_date'] != null) {
            if ($data['start_date'] == $data['end_date']) {
                // If start and end dates are the same, filter for that specific date
                $query->whereDate('updated_at', $data['start_date']);
            } else {
                // If start and end dates are different, filter for the date range
                // $query->whereBetween('parcels.updated_at', [$data['start_date'], $data['end_date']]);
                $query->whereBetween('updated_at', [$data['start_date'].' 00:00:00', $data['end_date'].' 23:59:59']);
            }
        }
        // if ($data['start_date'] != null && $data['end_date'] != null) {
        //     $query->whereBetween('parcels.updated_at', [$data['start_date'], $data['end_date']]);
        // }
        // if($data['merchant_id'] != null){
        //     $query->where('parcels.merchantId', $data['merchant_id']);
        // }
        // if($data['agent_id'] != null){
        //     $query->where('parcels.agentId', $data['agent_id']);
        // }
        // if($data['deliveryman_id'] != null){
        //     $query->where('parcels.pickupmanId', $data['deliveryman_id']);
        //     $query->orWhere('parcels.deliverymanId', $data['deliveryman_id']);
        // }
        $reports=$query->get();
        return $reports;
  
    }

    public function report()
    {
    	return view('backEnd.expense.report');
    }

    public function getExpenseSearchReports(Request $request){
        $reports=$this->getExpensedata($request->all());
        // dd($reports);
        $start_date=$request->start_date ?? date('Y-m-d');
        $end_date=$request->end_date ?? date('Y-m-d');

        if($reports->count() == 0){
            return view('backEnd.report.noData');
        }

        $head = 'Expense Report';
        return view('backEnd.expense.invoice', compact('reports','start_date','end_date','head'));
  
    }
    public function getExpensedata($data){
      
       

        $query = Expense::query()
        ->join('expense_types', 'expense_types.id', '=', 'expenses.expense_type_id')
        ->with('agent')
        ->orderBy('expenses.date', 'DESC')
        ->select('expenses.*', 'expense_types.title as expense_type');
        
        
        if ($data['start_date'] == null && $data['end_date'] == null) {
            $query->whereDate('expenses.date' , today());
        }
        if ($data['start_date'] != null && $data['end_date'] != null) {
            if ($data['start_date'] == $data['end_date']) {
                // If start and end dates are the same, filter for that specific date
                $query->whereDate('expenses.date', $data['start_date']);
            } else {
                // If start and end dates are different, filter for the date range
                // $query->whereBetween('parcels.created_at', [$data['start_date'], $data['end_date']]);
                $query->whereBetween('expenses.date', [$data['start_date'].' 00:00:00', $data['end_date'].' 23:59:59']);
            }
        }
       
        $reports=$query->get();
       
        return $reports;
  
    }
    
}
