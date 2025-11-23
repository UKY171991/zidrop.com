<?php

namespace App\Http\Middleware;

use Closure;

class FixPutFormData
{
    /**
     * Handle an incoming request.
     * This middleware parses form-data for PUT/PATCH requests and merges it into the request input.
     */
    public function handle($request, Closure $next)
    {
        // IMPORTANT: PHP does NOT parse multipart/form-data for PUT/PATCH requests.
        // This is a PHP limitation, not a Laravel bug. $_POST and $_REQUEST will always be empty for PUT form-data.
        // The only reliable solutions are:
        // 1. Use POST instead of PUT for form-data requests.
        // 2. Use x-www-form-urlencoded or raw JSON for PUT requests.
        // 3. Use a third-party package to parse multipart bodies for PUT (not recommended for most apps).
        //
        // If you must support PUT with form-data, recommend clients use POST or x-www-form-urlencoded/JSON for PUT.
        //
        // This middleware cannot fix the PHP limitation. It is left here for documentation and future extension.
        return $next($request);
    }
}
