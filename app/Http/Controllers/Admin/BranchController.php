<?php

namespace App\Http\Controllers\Admin;

use App\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use File;
use App\Deliverycharge;



class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $delivery  = Deliverycharge::where('status', 1)->get();
        $Branches = Branch::orderBy('serial', 'asc')->with('city', 'town')->get();
        // dd($Branches);
        return view('backEnd.branch.index', compact('Branches', 'delivery'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:branches,title|max:100',
            'key_person' => 'nullable|max:50',
            'address' => 'required|max:500',
            'phone' => 'required|max:40',
            'city' => 'required|max:50',
            'town' => 'required|max:50',
            'serial' => 'nullable|integer',
            'email' => 'required|max:100|email',
            'description' => 'nullable|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:500',
            'bank_poster' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:500',
            'is_active' => 'required|integer',
        ]);
   
       
        $branch = new Branch();
        $branch->title = trim(strip_tags($request->title));
        $branch->key_person = trim(strip_tags($request->key_person));
        $branch->address = trim(strip_tags($request->address));
        $branch->phone = $request->phone;
        $branch->state = $request->state;
        $branch->area = $request->area;
        $branch->serial = $request->serial ?? 1;
        $branch->email = $request->email;
        $branch->cities_id = $request->city;
        $branch->town_id = $request->town;
        $branch->description = trim(strip_tags($request->description));
        $branch->is_active = $request->is_active;
         if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $directory = 'branch/';
            $imageUrl = $directory . $imageName;
            $image->move($directory, $imageName);
            $branch->key_person_image = $imageUrl;
        }
         if ($request->hasFile('bank_poster')) {
            $image = $request->file('bank_poster');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $directory = 'branch/poster/';
            $imageUrl = $directory . $imageName;
            $image->move($directory, $imageName);
            $branch->bank_poster = $imageUrl;
        }
        $branch->save();
        Toastr::success('message', 'Branch add successfully!');
    	return redirect('/admin/branch');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
        $branch = Branch::where('id',$id)->with('division', 'district', 'upazila')->first();
        return view('admin.branch.show', compact('branch'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(Branch $branch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'title' => 'required|max:50|unique:branches,title,'.$id,
            'key_person' => 'nullable|max:50',
            'address' => 'required|max:500',
            'phone' => 'required|max:40',
            'city' => 'required|max:50',
            'town' => 'required|max:50',
            'serial' => 'nullable|integer',
            'email' => 'required|max:100|email',
            'description' => 'nullable|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:500',
            'bank_poster' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:500',
            'is_active' => 'required|integer',
        ]);

        $branch =  Branch::find($id);
        $branch->title = trim(strip_tags($request->title));
        $branch->key_person = trim(strip_tags($request->key_person));
        $branch->address = trim(strip_tags($request->address));
        $branch->phone = $request->phone;
        $branch->state = $request->state;
        $branch->area = $request->area;
        $branch->serial = $request->serial ?? 1;
        $branch->email = $request->email;
        $branch->cities_id = $request->city;
        $branch->town_id = $request->town;
        $branch->description = trim(strip_tags($request->description));
        $branch->is_active = $request->is_active;
         if ($request->hasFile('image')) {
            $old_image = $branch->key_person_image;
            if (File::exists($old_image)) {
                unlink($old_image);
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $directory = 'branch/';
            $imageUrl = $directory . $imageName;
            $image->move($directory, $imageName);
            $branch->key_person_image = $imageUrl;
        }
         if ($request->hasFile('bank_poster')) {
            $old_image = $branch->bank_poster;
            if (File::exists($old_image)) {
                unlink($old_image);
            }

            $image = $request->file('bank_poster');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $directory = 'branch/poster/';
            $imageUrl = $directory . $imageName;
            $image->move($directory, $imageName);
            $branch->bank_poster = $imageUrl;
        }
        $branch->save();
        Toastr::success('message', 'Branch Update successfully!');
    	return redirect('/admin/branch');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
    
        $branch = Branch::find($id);
        // delete image
        $old_image = $branch->key_person_image;
        if (File::exists($old_image)) {
            unlink($old_image);
        }
        $branch->delete();
        Toastr::success('message', 'Branch delete successfully!'); 
        return redirect('/admin/branch');
    }
}
