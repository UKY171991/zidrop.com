<?php

namespace App\Http\Controllers\Editor;

use App\Paymentapi;
use App\Feature;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use File;
use Illuminate\Http\Request;


class PaymentAPIController extends Controller {    public function create_contact_info() {
        $api_info = $edit_data = Paymentapi::find(1);
        
        // Create a masked version for display
        if ($api_info) {
            $api_info->secret_display = $api_info->secret ? $this->maskApiKey($api_info->secret) : '';
            $api_info->public_display = $api_info->public ? $this->maskApiKey($api_info->public) : '';
        }
        
        return view('backEnd.api.contact' ,compact('api_info'));
    }
    
    private function maskApiKey($key) {
        if (strlen($key) <= 8) {
            return str_repeat('*', strlen($key));
        }
        
        $start = substr($key, 0, 4);
        $end = substr($key, -4);
        $middle = str_repeat('*', strlen($key) - 8);
        
        return $start . $middle . $end;
    }    public function store_contact_info(Request $request) {
        Paymentapi::updateOrCreate(
            [
                'id' => 1,
            ],
            [
                'secret'   => $request->secret,
                'public'  => $request->public,
            ]
        );
        Toastr::success('message', 'Contact Information Updated successfully!');

        return back();
    }

    

    public function edit($id) {
        $edit_data = Paymentapi::find($id);

        return view('backEnd.api.edit', compact('edit_data'));
    }

    public function update(Request $request) {
        $this->validate($request, [
            'title'  => 'required',
            'status' => 'required',
        ]);
        $update_data = Paymentapi::find($request->hidden_id);

        $update_data->title    = $request->title;
        $update_data->subtitle = $request->subtitle;
        $update_data->icon     = $request->icon;
        $update_data->status   = $request->status;
        $update_data->save();
        Toastr::success('message', 'Feature update successfully!');

        return redirect('editor/api/manage');
    }

    public function inactive(Request $request) {
        $unpublish_data         = Feature::find($request->hidden_id);
        $unpublish_data->status = 0;
        $unpublish_data->save();
        Toastr::success('message', 'Feature uppublished successfully!');

        return redirect('editor/api/manage');
    }

    public function active(Request $request) {
        $publishId         = Paymentapi::find($request->hidden_id);
        $publishId->status = 1;
        $publishId->save();
        Toastr::success('message', 'API uppublished successfully!');

        return redirect('editor/api/manage');
    }

    public function destroy(Request $request) {
        $delete_data = Feature::find($request->hidden_id);
        File::delete(public_path() . 'uploads/feature', $delete_data->image);
        $delete_data->delete();
        Toastr::success('message', 'Featuredelete successfully!');

        return redirect('editor/api/manage');
    }
}
