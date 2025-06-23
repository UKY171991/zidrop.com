<?php
namespace App\Http\Controllers\Editor;

use App\Agent;
use App\AgentCommission;
use App\Deliveryman;
use App\DeliverymanCommission;
use App\Exports\AgentsCommissionExport;
use App\Exports\DeliverymanCommissionExport;
use App\Http\Controllers\Controller;
use App\Parcel;
use App\Parceltype;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel as MatwebsiteExcel;

class ManagePaymentController extends Controller
{

    public function agent_payment_list()
    {
        // Get the parcel type IDs that match the given slugs
        $parceltypes = Parceltype::whereIn('slug', ['deliverd', 'partial-delivery'])->pluck('id');

        // Fetch agents with parcels that have valid statuses and unpaid commissions
        $agents = Agent::select(['id', 'name', 'paymentMethod'])
            ->with(['parcels' => function ($query) use ($parceltypes) {
                $query->whereIn('status', $parceltypes)
                ->whereRaw("COALESCE(agent_commission, 0) > 0") // Handle NULL values
                    ->where('agent_commission_pay_status', 0); // Only unpaid commissions
            }])
            ->get();

        // Calculate agent commission charges and filter agents
        $filteredAgents = $agents->map(function ($agent) {
            $charge = $agent->parcels->sum('agent_commission');

            if ($charge > 0) {
                $agent->charge = $charge; // Add a custom attribute
                return $agent;
            }
            return null;
        })->filter(); // Remove null values (agents with zero commission)
                      // dd($filteredAgents);
        return view('backEnd.agentpayment.agentpayment', ['agents' => $filteredAgents]);
    }
    public function agent_paid_payment_list($id)
    {
        // dd($id);
        $AgentId = $id;
        
        return view('backEnd.agentpayment.commissionpayments', compact('AgentId'));
    }
    public function agent_get_com_payment(Request $request, $id)
    {
        // Base query
        $query = AgentCommission::where('agent_id', $id)->where('pay_status', 1)
            ->with([ 'agent'])
            ->orderByDesc('id');
    
        // Pagination variables
        $start  = $request->start ?? 0;
        $draw   = $request->draw ?? 1;
        $length = $request->length ?? 10;
    
        // Clone query to avoid running twice
        $count     = (clone $query)->count();
        $show_data = $query->offset($start)->limit($length)->get();
        
        // Manually load computed `parcels` attribute
        $show_data->each(function ($commission) {
            $commission->parcels = $commission->parcels; // This triggers the `getParcelsAttribute()`
        });
        // Prepare DataTable response
        $data = [
            'draw'            => $draw,
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'            => [],
        ];
        
        foreach ($show_data as $key => $value) {
            
            $invoiceUrl = route('editor.agent.agent_get_com_payment_invoice', $value->id);
            $TotalInvoice =  count(explode(',', $value->parcel_ids));

            
            // dd($value->parcels);
            $datavalue = [
                $key + 1, // Serial number starts from 1
                optional($value->agent)->name, // Avoid redundant `?? 'N/A'`
                number_format($value->agent_commission, 2),
                date('d M Y', strtotime($value->date)) . '<br>' . date("g:i a", strtotime($value->created_at)),
                $TotalInvoice,
                '<td>
                    <ul class="action_buttons cust-action-btn">
                      <li>
                         <a class="edit_icon anchor" href="'.$invoiceUrl.'" title="Invoice">
                            <i class="fa fa-eye"></i>
                         </a>
                      </li> 
                   </ul>
                </td>',
            ];
    
            $data['data'][] = $datavalue;
        }
    
        return response()->json($data);
    }
    public function agent_get_com_payment_invoice($id)
    {
        // Retrieve AgentCommission by ID and load related agent and parcels
        $AgentCommission = AgentCommission::where('id', $id)
            ->with(['agent']) // Eager load the agent
            ->first(); // Get the first (and only) result by ID
    
        // Check if the commission exists
        if (!$AgentCommission) {
            return redirect()->back()->with('error', 'Agent Commission not found.');
        }
    
        // Now, you can access the parcels using the `parcels` attribute
        $parcels = $AgentCommission->parcels; // This triggers the `getParcelsAttribute()` method
    
        // Pass the data to the view
        return view('backEnd.agentpayment.invoice_commission', compact('AgentCommission', 'parcels'));
    }
    public function exportAgentPaymentList()
    {
        $selected_agents = json_decode(request()->input('agents'), true); // Decode as an array

        // Get the parcel type IDs that match the given slugs
        $parceltypes = Parceltype::whereIn('slug', ['deliverd', 'partial-delivery'])->pluck('id');

        // Fetch agents with valid parcels
        $agents = Agent::whereIn('id', $selected_agents)
            ->with(['parcels' => function ($query) use ($parceltypes) {
                $query->whereIn('status', $parceltypes)
                    ->where('agent_commission', '>', 0)
                    ->where('agent_commission_pay_status', 0); // Only unpaid commissions
            }])
            ->get();

        // Calculate total agent commission and filter those with commission
        $filteredAgents = $agents->map(function ($agent) {
            $charge = $agent->parcels->sum('agent_commission');

            if ($charge > 0) {
                $agent->charge = $charge; // Add commission amount as an attribute
                return $agent;
            }
            return null;
        })->filter(); // Remove agents with zero commission

        if ($filteredAgents->isEmpty()) {
            return back()->with('error', 'No agents found with unpaid commissions.');
        }

        // Generate export file
        $filename = 'zidrop_agent_commission_'. Carbon::now()->format('Y-m-d') . '_at_' . Carbon::now()->format('H.i.s A');

        if (request()->type == 'csv') {
            return MatwebsiteExcel::download(new AgentsCommissionExport($filteredAgents), "$filename.csv");
        } else {
            return MatwebsiteExcel::download(new AgentsCommissionExport($filteredAgents), "$filename.csv");
        }
    }
    public function agentconfirmpayment(Request $request)
    {

        foreach ($request->agent_id as $agent_id) {
            $parcels = Parcel::where('agentId', $agent_id)->whereIn('status', [4, 6])->where('agent_commission_pay_status', 0)->get();
            if ($parcels->isEmpty()) {
                continue; // Skip if no eligible parcels
            }
            $totalCommission = $parcels->sum('agent_commission'); // Get total commission sum
            $agentsettings   = \App\Agent::find($agent_id);
            if (! $agentsettings) {
                return 'Error, Agent Not Found'; // Skip if agent not found
            }
            $parcel_ids = [];
            foreach ($parcels as $parcel) {
                // Update Agent
                $agentsettings->total_commission -= $parcel->agent_commission;
                $agentsettings->total_paid_commission += $parcel->agent_commission;

                $parcel->agent_commission_pay_status = 1;
                $parcel->timestamps                  = false;
                $parcel->save();

                $parcel_ids[] = $parcel->id;

            }
            $agentsettings->save();

            $payment                   = new AgentCommission();
            $payment->agent_id         = $agent_id;
            $payment->parcel_ids       = implode(',', $parcel_ids); // Convert array to comma-separated string
            $payment->agent_commission = $totalCommission;
            $payment->pay_status       = 1;
            $payment->approved_by      = 'admin';
            $payment->payment_purpose  = 'agent_commission';
            $payment->batchnumber      = 'BAT' . mt_rand(1111111111, 9999999999);
            $payment->sealnumber       = 'SEL' . mt_rand(1111111111, 9999999999);
            $payment->tagnumber        = 'TAG' . mt_rand(1111111111, 9999999999);
            $payment->date             = now();
            $payment->save();
        }

        Toastr::success('message', 'Agent Commission Paid.');

        return back();

    }

    public function deliveryman_payment_list()
    {
        // Get the parcel type IDs that match the given slugs
        $parceltypes = Parceltype::whereIn('slug', ['deliverd', 'partial-delivery'])->pluck('id');

        // Fetch Deliverymans with parcels that have valid statuses and unpaid commissions
        $Deliverymans = Deliveryman::select(['id', 'name', 'paymentMethod'])
            ->with(['parcels' => function ($query) use ($parceltypes) {
                $query->whereIn('status', $parceltypes)
                ->whereRaw("COALESCE(deliveryman_commission, 0) > 0") // Handle NULL values
                ->where('deliveryman_commission_pay_status', 0); // Only unpaid commissions
            }])
            ->get();

        // Calculate agent commission charges and filter Deliverymans
        $filteredDeliverymans = $Deliverymans->map(function ($Deliverymans) {
            $charge = $Deliverymans->parcels->sum('deliveryman_commission');

            if ($charge > 0) {
                $Deliverymans->charge = $charge; // Add a custom attribute
                return $Deliverymans;
            }
            return null;
        })->filter(); // Remove null values (Deliverymans with zero commission)
                      // dd($filteredDeliverymans);
        return view('backEnd.deliverymanpayment.deliverymanpayment', ['Deliverymans' => $filteredDeliverymans]);
    }
    public function deliveryman_paid_payment_list($id)
    {
        $deliverymanId = $id;
        return view('backEnd.deliverymanpayment.commissionpayments', compact('deliverymanId'));
    }
    public function deliveryman_get_com_payment(Request $request, $id)
    {
        // Base query
        $query = DeliverymanCommission::where('deliveryman_id', $id)->where('pay_status', 1)
            ->with(['deliveryman'])
            ->orderByDesc('id');

        // Pagination variables
        $start  = $request->start ?? 0;
        $draw   = $request->draw ?? 1;
        $length = $request->length ?? 10;

        // Clone query to avoid running twice
        $count     = (clone $query)->count();
        $show_data = $query->offset($start)->limit($length)->get();

        // Manually load computed `parcels` attribute
        $show_data->each(function ($commission) {
            $commission->parcels = $commission->parcels; // This triggers the `getParcelsAttribute()`
        });

        // Prepare DataTable response
        $data = [
            'draw'            => $draw,
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'            => [],
        ];

        foreach ($show_data as $key => $value) {

            $invoiceUrl = route('editor.deliveryman.deliveryman_get_com_payment_invoice', $value->id);
            $TotalInvoice =  count(explode(',', $value->parcel_ids));


            $datavalue = [
                $key + 1, // Serial number starts from 1
                optional($value->deliveryman)->name,
                number_format($value->deliveryman_commission, 2),
                date('d M Y', strtotime($value->date)) . '<br>' . date("g:i a", strtotime($value->created_at)),
                $TotalInvoice,
                '<td>
                    <ul class="action_buttons cust-action-btn">
                      <li>
                         <a class="edit_icon anchor" href="'.$invoiceUrl.'" title="Invoice">
                            <i class="fa fa-eye"></i>
                         </a>
                      </li> 
                   </ul>
                </td>',
            ];

            $data['data'][] = $datavalue;
        }

        return response()->json($data);
    }
    public function deliveryman_get_com_payment_invoice($id)
    {
        // Retrieve AgentCommission by ID and load related agent and parcels
        $DeliverymanCommission = DeliverymanCommission::where('id', $id)
            ->with(['deliveryman']) // Eager load the agent
            ->first(); // Get the first (and only) result by ID
    
        // Check if the commission exists
        if (!$DeliverymanCommission) {
            return redirect()->back()->with('error', 'Deliveryman Commission not found.');
        }
    
        // Now, you can access the parcels using the `parcels` attribute
        $parcels = $DeliverymanCommission->parcels; // This triggers the `getParcelsAttribute()` method
    
        // Pass the data to the view
        return view('backEnd.deliverymanpayment.invoice_commission', compact('DeliverymanCommission', 'parcels'));
    }
    public function exportDeliverymanPaymentList()
    {
        $selected_deliverymans = json_decode(request()->input('deliverymans'), true); // Decode as an array

        // Get the parcel type IDs that match the given slugs
        $parceltypes = Parceltype::whereIn('slug', ['deliverd', 'partial-delivery'])->pluck('id');

        // Fetch Deliveryman with valid parcels
        $Deliveryman = Deliveryman::whereIn('id', $selected_deliverymans)
            ->with(['parcels' => function ($query) use ($parceltypes) {
                $query->whereIn('status', $parceltypes)
                ->where('deliveryman_commission', '>', 0)
                ->where('deliveryman_commission_pay_status', 0); // Only unpaid commissions
            }])
            ->get();

        // Calculate total Deliveryman commission and filter those with commission
        $filterDeliveryman = $Deliveryman->map(function ($Deliveryman) {
            $charge = $Deliveryman->parcels->sum('deliveryman_commission');

            if ($charge > 0) {
                $Deliveryman->charge = $charge; // Add commission amount as an attribute
                return $Deliveryman;
            }
            return null;
        })->filter(); // Remove Deliveryman with zero commission

        if ($filterDeliveryman->isEmpty()) {
            return back()->with('error', 'No deliveryman found with unpaid commissions.');
        }

        // Generate export file
        $filename = 'zidrop_agent_commission_date_time_' . Carbon::now()->format('Y-m-d') . '_at_' . Carbon::now()->format('H.i.s A');

        if (request()->type == 'csv') {
            return MatwebsiteExcel::download(new DeliverymanCommissionExport($filterDeliveryman), "$filename.csv");
        } else {
            return MatwebsiteExcel::download(new DeliverymanCommissionExport($filterDeliveryman), "$filename.csv");
        }
    }
    public function deliverymanconfirmpayment(Request $request)
    {
        foreach ($request->deliveryman_id as $deliveryman_id) {
            $parcels = Parcel::where('deliverymanId', $request->deliveryman_id)->whereIn('status', [4, 6])->where('deliveryman_commission_pay_status', 0)->get();

            if ($parcels->isEmpty()) {
                continue; // Skip if no eligible parcels
            }
            $totalCommission     = $parcels->sum('deliveryman_commission'); // Get total commission sum
            $Deliverymansettings = \App\Deliveryman::find($deliveryman_id);
            if (! $Deliverymansettings) {
                continue; // Skip if agent not found
            }
            $parcel_ids = [];
            foreach ($parcels as $parcel) {
                // Update Agent
                $Deliverymansettings->total_commission -= $parcel->deliveryman_commission;
                $Deliverymansettings->total_paid_commission += $parcel->deliveryman_commission;

                $parcel->deliveryman_commission_pay_status = 1;
                $parcel->timestamps                        = false;
                $parcel->save();

                $parcel_ids[] = $parcel->id;

            }
            $Deliverymansettings->save();

            $payment                         = new DeliverymanCommission();
            $payment->deliveryman_id         = $deliveryman_id;
            $payment->parcel_ids             = implode(',', $parcel_ids); // Convert array to comma-separated string
            $payment->deliveryman_commission = $totalCommission;
            $payment->pay_status             = 1;
            $payment->approved_by            = 'admin';
            $payment->payment_purpose        = 'deliveryman_commission';
            $payment->batchnumber            = 'BAT' . mt_rand(1111111111, 9999999999);
            $payment->sealnumber             = 'SEL' . mt_rand(1111111111, 9999999999);
            $payment->tagnumber              = 'TAG' . mt_rand(1111111111, 9999999999);
            $payment->date                   = now();
            $payment->save();
        }

        Toastr::success('message', 'Deliveryman Commission Paid.');

        return back();

    }

}
