<?php

namespace App\Http\Controllers\Editor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Blog;
use File;

class BlogController extends Controller
{
    public function create(){
        return view('backEnd.blog.create');
    }
    public function store(Request $request){
        $this->validate($request,[
            'image'=>'required',
            'title_en'=>'required',
            'status'=>'required',
        ]);

        // image upload
        $file = $request->file('image');
        $name = $file->getClientOriginalName();
        $uploadPath = 'uploads/blog/';
        $file->move($uploadPath,$name);
        $fileUrl =$uploadPath.$name;

        $store_data = new Blog();
        $store_data->title_en    = $request->title_en;
        $store_data->text_en     = $request->text_en;
        $store_data->image       = $fileUrl;
        $store_data->status      = $request->status;
        $store_data->save();
        Toastr::success('message', 'Blog add successfully!');
        return redirect('editor/blog/manage');
    }
    public function manage(){
        $show_data = Blog::get();
        return view('backEnd.blog.manage',compact('show_data'));
    }
    public function edit($id){
        $edit_data = Blog::find($id);
        return view('backEnd.blog.edit',compact('edit_data'));
    }
     public function update(Request $request){
        $this->validate($request,[
            'title_en'=>'required',
            'title_bn'=>'required',
            'status'=>'required',
        ]);

        $update_data = Blog::find($request->hidden_id);
        $update_image = $request->file('image');
        if ($update_image) {
           $file = $request->file('image');
            $name = $file->getClientOriginalName();
            $uploadPath = 'uploads/blog/';
            File::delete(public_path() . 'uploads/blog', $update_data->image);
            $file->move($uploadPath,$name);
            $fileUrl =$uploadPath.$name;
        }else{
            $fileUrl = $update_data->image;
        }

        $update_data->image = $fileUrl;
        $update_data->title_en    = $request->title_en;
        $update_data->text_en     = $request->text_en;
        $update_data->status      = $request->status;
        $update_data->save();
        Toastr::success('message', 'Blog  update successfully!');
        return redirect('editor/blog/manage');
    }

    public function inactive(Request $request){
        $unpublish_data = Blog::find($request->hidden_id);
        $unpublish_data->status=0;
        $unpublish_data->save();
        Toastr::success('message', 'Blog  uppublished successfully!');
        return redirect('editor/blog/manage');
    }

    public function active(Request $request){
        $publishId = Blog::find($request->hidden_id);
        $publishId->status=1;
        $publishId->save();
        Toastr::success('message', 'Blog  uppublished successfully!');
        return redirect('editor/blog/manage');
    }
     public function destroy(Request $request){
        $delete_data = Blog::find($request->hidden_id);
        File::delete(public_path() . 'uploads/blog', $delete_data->image);  
        $delete_data->delete();
        Toastr::success('message', 'Blog delete successfully!');
        return redirect('editor/blog/manage');
    }
}
