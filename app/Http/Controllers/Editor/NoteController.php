<?php

namespace App\Http\Controllers\Editor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Note;
use File;

class NoteController extends Controller
{
    public function create(){
    	return view('backEnd.note.create');
    }
    public function store(Request $request){
    	$this->validate($request,[
    		'title'=>'required',
    		'status'=>'required',
    	]);

    	

    	$store_data = new Note();
    	$store_data->title = $request->title;
    	$store_data->status = $request->status;
    	$store_data->save();
        Toastr::success('message', 'Note add successfully!');
    	return redirect('editor/note/manage');
    }
    public function manage(){
    	$show_data = Note::get();
        return view('backEnd.note.manage',compact('show_data'));
    }
    public function edit($id){
        $edit_data = Note::find($id);
        return view('backEnd.note.edit',compact('edit_data'));
    }
     public function update(Request $request){
        $this->validate($request,[
            'status'=>'required',
        ]);

        $update_data = Note::find($request->hidden_id);
       
        $update_data->title = $request->title;
        $update_data->status = $request->status;
        $update_data->save();
        Toastr::success('message', 'Note update successfully!');
        return redirect('editor/note/manage');
    }

    public function inactive(Request $request){
        $unpublish_data = Note::find($request->hidden_id);
        $unpublish_data->status=0;
        $unpublish_data->save();
        Toastr::success('message', 'Note  uppublished successfully!');
        return redirect('editor/note/manage');
    }

    public function active(Request $request){
        $publishId = Note::find($request->hidden_id);
        $publishId->status=1;
        $publishId->save();
        Toastr::success('message', 'Note uppublished successfully!');
        return redirect('editor/note/manage');
    }
     public function destroy(Request $request){
        $delete_data = Note::find($request->hidden_id); 
        $delete_data->delete();
        Toastr::success('message', 'Note delete successfully!');
        return redirect('editor/note/manage');
    }
}
