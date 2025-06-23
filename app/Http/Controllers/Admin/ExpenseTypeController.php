<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ExpenseType;
use DB;
use Toastr;


class ExpenseTypeController extends Controller
{
   
    public function index()
    {
    	$expensetypes = DB::table('expense_types')->orderBy('id','DESC')->get();
    	return view('backEnd.expense.type.index',compact('expensetypes'));
    }

    public function create()
    {
    	return  "create";
    }

    public function store(Request $request)
    {
    	$this->validate($request,[
    		'title' => 'required|unique:expense_types,title',
			'status' => 'required',
    	]);

    	$data = array();
    	$data['title'] = $request->title;
		$data['status'] = $request->status;
		$data['created_by'] = auth()->id();
		$data['created_at'] = date('Y-m-d H:i:s');

    	DB::table('expense_types')->insert($data);

		Toastr::success('message', 'Expense Type Added Successfully!');
    	return redirect()->route('expense-type.index');
    }

    public function edit($id)
    {
		
    	$expense = DB::table('expenses')->where('id',$id)->first();
    	return view('admin.expense.edit',compact('expense'));
    }

    public function update(Request $request,$id)
    {
	
    	$this->validate($request, [
			'title' => 'required|unique:expense_types,title, '.$id . ',id',
			'status' => 'required',
		]);
		

    	$data = array();
    	$data['title'] = $request->title;
		$data['status'] = $request->status;
		$data['updated_by'] = auth()->id();
		$data['updated_at'] = date('Y-m-d H:i:s');


    	DB::table('expense_types')->where('id',$id)->update($data);
    	Toastr::success('message', 'Expense Type Updated Successfully!');
    	return redirect()->route('expense-type.index');
    }

    public function destroy($id)
    {
		$Expensetype = ExpenseType::findOrFail($id);
		$Expensetype->delete();
		Toastr::success('message', 'Expense Type Deleted Successfully!');
		return redirect()->back();
    }
}
