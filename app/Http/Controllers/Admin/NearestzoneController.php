<?php

namespace App\Http\Controllers\Admin;

use App\Deliverycharge;
use App\Http\Controllers\Controller;
use App\Nearestzone;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class NearestzoneController extends Controller {
    public function add() {
        $state = Deliverycharge::where('status', 1)->get();

        return view('backEnd.nearestzone.add', compact('state'));

    }

    public function store(Request $request) {
        $this->validate($request, [
            'state'               => 'required',
            'zonename'            => 'required',
            'extradeliverycharge' => 'required',
            'status'              => 'required',
        ]);

        $store_data                      = new Nearestzone();
        $store_data->state               = $request->state;
        $store_data->zonename            = $request->zonename;
        $store_data->extradeliverycharge = $request->extradeliverycharge;
        $store_data->status              = $request->status;
        $store_data->save();
        Toastr::success('message', 'Nearestzone add successfully!');

        return redirect('/admin/nearestzone/manage');
    }

    public function manage() {
        $show_data = Nearestzone::with('state')
            ->orderBy('id', 'DESC')
            ->get();
// dd($show_data);
        return view('backEnd.nearestzone.manage', compact('show_data'));
    }

    public function edit($id) {
        $edit_data = Nearestzone::find($id);
        $state     = Deliverycharge::where('status', 1)->get();

        return view('backEnd.nearestzone.edit', compact('edit_data', 'state'));
    }

    public function update(Request $request) {
        $update_data = Nearestzone::find($request->hidden_id);

        $update_data->state               = $request->state;
        $update_data->zonename            = $request->zonename;
        $update_data->extradeliverycharge = $request->extradeliverycharge;
        $update_data->status              = $request->status;
        $update_data->save();
        Toastr::success('message', 'Nearestzone Update successfully!');

        return redirect('admin/nearestzone/manage');
    }

    public function inactive(Request $request) {
        $unpublish_data         = Nearestzone::find($request->hidden_id);
        $unpublish_data->status = 0;
        $unpublish_data->save();
        Toastr::success('message', 'Nearestzone active successfully!');

        return redirect('/admin/nearestzone/manage');
    }

    public function active(Request $request) {
        $publishId         = Nearestzone::find($request->hidden_id);
        $publishId->status = 1;
        $publishId->save();
        Toastr::success('message', 'Nearestzone active successfully!');

        return redirect('/admin/nearestzone/manage');
    }

    public function destroy(Request $request) {
        $destroy_id = Nearestzone::find($request->hidden_id);
        $destroy_id->delete();
        Toastr::success('message', 'Nearestzone  delete successfully!');

        return redirect('/admin/nearestzone/manage');
    }

}
