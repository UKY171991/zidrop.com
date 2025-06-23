<?php

namespace App\Http\Controllers\FrontEnd;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebsiteController extends Controller
{
   
    public function p2p()
    {
        $results = DB::table('paymentapis')->where('id', 1)->first();
        return view('frontEnd.layouts.pages.p2p', compact('results'));
    }
}
