<?php

namespace App\Http\Controllers\Editor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Price;
use File;
class PriceController extends Controller
{
    
    public function create(){
        return view('backEnd.price.create');
    }
    public function store(Request $request){
        $this->validate($request,[
            'image'=>'required',
           // 'price'=>'required',
            'name'=>'required',
            'text'=>'required',
            'status'=>'required',
        ]);
        // image upload
        $file = $request->file('image');
        $name = $file->getClientOriginalName();
        $uploadPath = 'uploads/price/';
        $file->move($uploadPath,$name);
        $fileUrl =$uploadPath.$name;

        $store_data = new Price();
        $store_data->image = $fileUrl;
        $store_data->name = $request->name;
        $store_data->text = $request->text;
        $store_data->price = $request->price;
        $store_data->status = $request->status;
        $store_data->save();
        Toastr::success('message', 'Price add successfully!');
        return redirect('editor/price/manage');
    }
    public function manage(){
        $show_data = Price::get();
        return view('backEnd.price.manage',compact('show_data'));
    }
    public function edit($id){
        $edit_data = Price::find($id);
        return view('backEnd.price.edit',compact('edit_data'));
    }
     public function update(Request $request){
        $this->validate($request,[
            //'price'=>'required',
            'name'=>'required',
            'text'=>'required',
            'status'=>'required',
        ]);
        $update_data = Price::find($request->hidden_id);
       $update_image = $request->file('image');
        if ($update_image) {
           $file = $request->file('image');
            $name = $file->getClientOriginalName();
            $uploadPath = 'uploads/price/';
            File::delete(public_path() . 'uploads/price',$update_data->image);
            $file->move($uploadPath,$name);
            $fileUrl =$uploadPath.$name;
        }else{
            $fileUrl = $update_data->image;
        }
        
        $update_data->price = $request->price;
        $update_data->name = $request->name;
        $update_data->text = $request->text;
        $update_data->status = $request->status;
        $update_data->save();
        Toastr::success('message', 'Price update successfully!');
        return redirect('editor/price/manage');
    }

    public function inactive(Request $request){
        $unpublish_data = Price::find($request->hidden_id);
        $unpublish_data->status=0;
        $unpublish_data->save();
        Toastr::success('message', 'Price uppublished successfully!');
        return redirect('editor/price/manage');
    }

    public function active(Request $request){
        $publishId = Price::find($request->hidden_id);
        $publishId->status=1;
        $publishId->save();
        Toastr::success('message', 'Price uppublished successfully!');
        return redirect('editor/price/manage');
    }
     public function destroy(Request $request){
        $delete_data = Price::find($request->hidden_id);
        $delete_data->delete();
        Toastr::success('message', 'Price delete successfully!');
        return redirect('editor/price/manage');
    }
}
