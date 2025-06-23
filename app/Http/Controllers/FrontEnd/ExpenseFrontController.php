<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Expense;
use App\ExpenseType;
use DB;
use Toastr;
use Session;



class ExpenseFrontController extends Controller
{
   
    public function index()
    {
    	$expenses = Expense::where('agent_id', Session::get('agentId'))->with('expenseType')->orderBy('id','desc')->get();		
		$ExpenseTypes = ExpenseType::where('status',1)->get();

		$lastExpNUmber = Expense::orderBy('id', 'desc')->pluck('expense_number')->first();

        if ($lastExpNUmber === null) {
            // If there are no existing expense numbers, start from 1
            $lastExpNUmber = 1;
        } else {
            // Extract the numeric part of the expense number
            preg_match('/\d+$/', $lastExpNUmber, $matches);
            $lastNumber = (int)$matches[0];

            // Increment the last expense number
            $lastExpNUmber = $lastNumber + 1;
        }

    	return view('frontEnd.layouts.pages.agent.expense.index',compact('expenses','ExpenseTypes','lastExpNUmber'));
    }

    public function create()
    {
		$ExpenseTypes = ExpenseType::where('status',1)->get();
		$lastExpNUmber = Expense::orderBy('id','desc')->pluck('expense_number')->first();
		if($lastExpNUmber == null){
			$lastExpNUmber = 1;
		} else {
			// "#EXP_70 get only 70 and add 1 to it"
			$lastExpNUmber = substr($lastExpNUmber, 5) + 1;
		}
    	return view('backEnd.expense.create',compact('ExpenseTypes','lastExpNUmber'));
    }

    public function store(Request $request)
    {
		$this->validate($request,[
			'title' => 'required|max:255',
			'expense_number' => 'required|unique:expenses,expense_number',
			'expense_type_id' => 'required|numeric|exists:expense_types,id',
			'vehicle' => 'required',
			'date' => 'required',
			'amount' => 'required',
			'note' => 'required|max:500',
			'receipt_file' => 'nullable|mimes:jpeg,jpg,png,pdf|max:1000',
		]);
		$lastExpNumberrr = Expense::orderBy('id', 'desc')->pluck('expense_number')->first();

		if ($lastExpNumberrr === null) {
			// If there are no existing expense numbers, start from 1
			$ExpNumber = 1;
		} else {
			// Extract the numeric part of the expense number
			preg_match('/\d+$/', $lastExpNumberrr, $matches);
			$lastNumber = (int)$matches[0];

			// Increment the last expense number
			$ExpNumber = $lastNumber + 1;
		}
    	$data = array();
    	$data['title'] = $request->title;
		$data['expense_number'] = "#EXP-" . $ExpNumber;
		$data['expense_type_id'] = $request->expense_type_id;
		$data['vehicle'] = $request->vehicle;
		$data['done_by'] = 'Agent';
		$data['date'] = $request->date;
		$data['amount'] = $request->amount;
		$data['note'] = $request->note;
		$data['agent_id'] =  Session::get('agentId');
		if ($request->hasFile('receipt_file')) {
			$file = $request->file('receipt_file');
			$fileName = time().'.'.$file->getClientOriginalExtension();
			$file->move(public_path('uploads/expense'), $fileName);
			$data['receipt_file'] = $fileName;
		}
		$data['created_by'] = auth()->id();
		
    	DB::table('expenses')->insert($data);

		Toastr::success('message', 'Expense Added Successfully!');
    	return redirect()->route('agentexpense.index');
    }

    public function edit($id)
    {
		
    	$expense = DB::table('expenses')->where('id',$id)->first();
    	return view('admin.expense.edit',compact('expense'));
    }

    public function update(Request $request,$id)
    {
	
    	$this->validate($request,[
			'title' => 'required|max:255',
			'expense_number' => 'required',
			'expense_type_id' => 'required|numeric|exists:expense_types,id',
			'vehicle' => 'required',
			'date' => 'required',
			'amount' => 'required',
			'note' => 'required|max:500',
			'receipt_file' => 'nullable|mimes:jpeg,jpg,png,pdf|max:1000',
		]);

		

    	$data = array();
    	$data['title'] = $request->title;
		$data['expense_type_id'] = $request->expense_type_id;
		$data['vehicle'] = $request->vehicle;
		$data['date'] = $request->date;
		$data['amount'] = $request->amount;
		$data['note'] = $request->note;
		if ($request->hasFile('receipt_file')) {
			$file = $request->file('receipt_file');
			$fileName = time().'.'.$file->getClientOriginalExtension();
			$file->move(public_path('uploads/expense'), $fileName);
			// Delete old file from folder
			$oldFile = Expense::findOrFail($id)->receipt_file;
			unlink(public_path('uploads/expense/'.$oldFile));
			$data['receipt_file'] = $fileName;
		}
		
		$data['updated_by'] = auth()->id();

    	DB::table('expenses')->where('id',$id)->update($data);
    	Toastr::success('message', 'Expense Updated Successfully!');
    	return redirect()->route('agentexpense.index');
    }

    public function destroy($id)
    {
		$Expense = Expense::findOrFail($id);
		$Expense->delete();
		Toastr::success('message', 'Expense Deleted Successfully!');
		return redirect()->back();
    }
}      
