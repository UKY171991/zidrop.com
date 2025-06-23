<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use File;

use Illuminate\Support\Facades\DB;




class RefreshController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $old_data = DB::table('auto_refresh')->where('id', 1)->first();
        return view('backEnd.refresh.refresh', compact('old_data'));
    }

    public function check(Request $request){
        $this->validate($request,[
            'time'=>'required',
           // 'status'=>'required',
        ]);

        //print_r($_POST); die;


       // Check if the record with id = 1 exists
        $recordExists = DB::table('auto_refresh')->where('id', 1)->exists();

        if ($recordExists) {
            // If the record exists, update it
            DB::table('auto_refresh')->where('id', 1)->update([
                'time' => $request->time,
                'status' => $request->status === 'on' ? 1 : 0,
            ]);
        } else {
        // If the record does not exist, insert it
            DB::table('auto_refresh')->insert([
                'id' => 1,  // You must explicitly set the id when inserting
                'time' => $request->time,
                'status' => $request->status === 'on' ? 1 : 0,
            ]);
        }
        Toastr::success('message', 'Refresh time update successfully!');
        return redirect('admin/refresh');
    }

    public function checkAutoRefresh()
    {
        // Fetch the data where id = 1
        $update_data = DB::table('auto_refresh')->where('id', 1)->first();

        // Check if data was found
        if ($update_data) {
            // Check if status is active (assuming 1 = active) and time is not empty
            if ($update_data->status == 1 && !empty($update_data->time)) {
                // Return the time in minutes for refresh (assuming 'time' is in minutes)
                return response()->json([
                    'status' => 'active',
                    'time' => $update_data->time,
                    'message' => 'Auto-refresh active, refreshing every ' . $update_data->time . ' minutes.',
                ]);
            } else {
                // If status is not active or time is empty
                return response()->json([
                    'status' => 'inactive',
                    'message' => 'Auto-refresh not active or time is not set.',
                ]);
            }
        } else {
            // If no data is found for id = 1
            return response()->json([
                'status' => 'error',
                'message' => 'No data found for the given ID.',
            ]);
        }
    }
}
