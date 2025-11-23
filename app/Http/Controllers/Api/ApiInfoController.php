<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Http;

class ApiInfoController extends Controller
{
    /**
     * Handle the creation of API info.
     */
    public function create(Request $request)
    {
        // Forward the request data to the external API and return its response
        $response = Http::post('https://zidrop.com/editor/api_info/create', [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        return response()->json($response->json(), $response->status());
    }
}
