<?php

namespace App\Helpers;

use App\Models\ApiLog;

class ApiLogger
{
    public static function log($source, $endpoint, $method = 'GET', $request = null, $response = null, $status = 200, $time = null)
    {
        return ApiLog::create([
            'source' => $source,
            'endpoint' => $endpoint,
            'method' => $method,
            'request_data' => is_array($request) ? json_encode($request) : $request,
            'response' => is_array($response) ? json_encode($response) : $response,
            'status_code' => $status,
            'response_time' => $time,
        ]);
    }
}
