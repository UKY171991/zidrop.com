<?php

namespace App\Http\Controllers\Editor;

use App\Paymentapi;
use App\Feature;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use File;
use Illuminate\Http\Request;


class PaymentAPIController extends Controller {
    public function create_contact_info() {
        $api_info = $edit_data = Paymentapi::find(1);
        return view('backEnd.api.contact' ,compact('api_info'));
    }

    public function store_contact_info(Request $request) {
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
