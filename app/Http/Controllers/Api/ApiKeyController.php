<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Paymentapi;
use App\Feature;
use Brian2694\Toastr\Facades\Toastr;
use File;



class ApiKeyController extends Controller
{
    // GET /api/api-keys
    public function index()
    {

        $api_info = $edit_data = Paymentapi::find(1);
        
        // Create a masked version for display
        if ($api_info) {
            $api_info->secret_display = $api_info->secret ? $this->maskApiKey($api_info->secret) : '';
            $api_info->public_display = $api_info->public ? $this->maskApiKey($api_info->public) : '';

            // Hide unwanted fields
            unset($api_info->secret, $api_info->public, $api_info->created_at, $api_info->updated_at);
        }
        return response()->json([
            'status' => true,
            'data' => $api_info
        ]);
    }

    private function maskApiKey($key) {
        if (strlen($key) <= 8) {
            return str_repeat('*', strlen($key));
        }
        
        $start = substr($key, 0, 4);
        $end = substr($key, -4);
        $middle = str_repeat('*', strlen($key) - 8);
        
        return $start . $middle . $end;
    }

    // PUT /api/api-keys/{id}
    // PUT /api/api-keys/{id}
    /**
     * Update the API key (PUT /api/api-keys/{id})
     * Accepts JSON, x-www-form-urlencoded, or form-data for PUT requests.
     * Handles all edge cases for Laravel's PUT input parsing.
     */
    public function update(Request $request, $id)
    {
        // Try to get input from all possible sources
        $secret = $request->input('secret');
        $public = $request->input('public');

        // If still missing, try raw JSON
        if (empty($secret) || empty($public)) {
            $content = $request->getContent();
            $json = json_decode($content, true);
            if (is_array($json)) {
                if (empty($secret) && isset($json['secret'])) $secret = $json['secret'];
                if (empty($public) && isset($json['public'])) $public = $json['public'];
            }
        }

        // If still missing, try urlencoded string
        if (empty($secret) || empty($public)) {
            $content = $request->getContent();
            $parsed = [];
            parse_str($content, $parsed);
            if (empty($secret) && !empty($parsed['secret'])) $secret = $parsed['secret'];
            if (empty($public) && !empty($parsed['public'])) $public = $parsed['public'];
        }

        // Final fallback: check PHP superglobals (for form-data PUT edge case)
        if (empty($secret) && isset($_POST['secret'])) $secret = $_POST['secret'];
        if (empty($public) && isset($_POST['public'])) $public = $_POST['public'];
        if (empty($secret) && isset($_REQUEST['secret'])) $secret = $_REQUEST['secret'];
        if (empty($public) && isset($_REQUEST['public'])) $public = $_REQUEST['public'];

        // Validate input
        $validator = Validator::make([
            'secret' => $secret,
            'public' => $public,
        ], [
            'secret' => 'required|string',
            'public' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Save or update the record
        Paymentapi::updateOrCreate(
            ['id' => $id],
            [
                'secret' => $secret,
                'public' => $public,
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'API key updated successfully.'
        ]);
    }
}
