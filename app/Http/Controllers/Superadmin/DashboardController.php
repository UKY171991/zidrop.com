<?php

namespace App\Http\Controllers\Superadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Customer;
use App\Merchant;
use App\Parceltype;
use DB;
use App\Parcel;
class DashboardController extends Controller
{
     public function index(){
    	   $agentsDue = Parcel::whereIn('status', [6, 4])
        ->whereNull('agentPaystatus')
        ->wherenotNull('agentId')
        ->groupBy('agentId')
        ->get(['agentId', \DB::raw('SUM(agentAmount) as totalDue')]);

		// 
     
		$parceltype = Parceltype::where('slug', 'return-to-merchant')->first();

		$marchents = Merchant::select(['id', 'companyName', 'paymentMethod'])
			->with(['parcels' => function ($query) use ($parceltype) {
				$query->where('status', $parceltype->id)
					->where('deliveryCharge', '>', 0)
					->where('pay_return', 0);
			}])->get();

		// Calculate total charge directly using sum()
		$totalReturnMercahntDue = $marchents->sum(function ($merchant) {
			return $merchant->parcels->sum(fn($p) => $p->deliveryCharge + $p->tax + $p->insurance);
		});

		// Area Chart 
		$months = [
			"January", "February", "March", "April", "May", "June",
			"July", "August", "September", "October", "November", "December"
		];
		
		// Fetch delivered parcels data with full month and year
		$deliveredParcelsRaw = Parcel::selectRaw("DATE_FORMAT(updated_at, '%M %Y') as month_year, COUNT(*) as count")
			->whereIn('status', [4, 6]) // Adjust the status as per requirement
			->whereYear('updated_at', date('Y'))
			->groupBy('month_year')
			->orderByRaw("MONTH(updated_at)") // Ensure correct order
			->pluck('count', 'month_year'); // Fetch as key-value pair (month_year => count)
		
		// Fetch pickup parcels data with full month and year
		$pickupParcelsRaw = Parcel::selectRaw("DATE_FORMAT(created_at, '%M %Y') as month_year, COUNT(*) as count")
			->whereIn('status', [2, 3, 4, 5, 6, 7, 8, 10, 11, 12]) // Explicitly list the statuses
			->whereYear('created_at', date('Y'))
			->groupBy('month_year')
			->orderByRaw("MONTH(created_at)") // Ensure correct order
			->pluck('count', 'month_year'); // Fetch as key-value pair (month_year => count)
		
		// Fill missing months with 0
		$deliveredParcels = [];
		$pickupParcels = [];
		
		foreach ($months as $month) {
			$fullMonthYear = $month . ' ' . date('Y'); // Append the current year to the month
		
			$deliveredParcels[] = [
				'month' => $fullMonthYear,
				'count' => $deliveredParcelsRaw[$fullMonthYear] ?? 0 // Use existing count, otherwise 0
			];
		
			$pickupParcels[] = [
				'month' => $fullMonthYear,
				'count' => $pickupParcelsRaw[$fullMonthYear] ?? 0
			];
		}
		
		// Pass to view
		$data['agentsDue'] = $agentsDue;
		$data['totalReturnMercahntDue'] = $totalReturnMercahntDue;
		$data['deliveredParcels'] = $deliveredParcels;
		$data['pickupParcels'] = $pickupParcels;


    	return view('backEnd.superadmin.dashboard', $data);
    }
   
    public function cinactive(Request $request){
	    $active_data         =   Customer::find($request->hidden_id);
	    $active_data->status =  0;
	    $active_data->save();
	    Toastr::success('message', 'customer inactive successfully!');
	    	return redirect()->back();   
		}

		public function cactive(Request $request){
	    $active_data         =   Customer::find($request->hidden_id);
	    $active_data->status =  1;
	    $active_data->save();
	    Toastr::success('message', 'customer active successfully!');
	   	return redirect()->back();     
		}

		public function destroy(Request $request){
        $deleteId = Customer::find($request->hidden_id);
        $deleteId->delete();
        Toastr::success('message', 'customer  delete successfully!');
     	  return redirect()->back();
    	}

}
