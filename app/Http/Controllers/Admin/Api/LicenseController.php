<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\License;
use App\Helpers\ApiLogger;
use App\Models\LicenseDomain;
use Carbon\Carbon;

class LicenseController extends Controller
{
    public function validateLicense(Request $request)
    {
        $request->validate(['license_key' => 'required|string']);

        $license = License::where('license_key', $request->license_key)->first();

        if (! $license) {
            
        ApiLogger::log(
            'plugin',
            '/api/license/validate',
            'POST',
            $request->all(),
            ['valid' => true, 'user_id' => $license->user_id],
            200,
            microtime(true) - LARAVEL_START
        );
    
        return response()->json(['valid' => false, 'message' => 'Invalid license key.'], 404);
        }

        if ($license->status !== 'active') {
            
        ApiLogger::log(
            'plugin',
            '/api/license/validate',
            'POST',
            $request->all(),
            ['valid' => true, 'user_id' => $license->user_id],
            200,
            microtime(true) - LARAVEL_START
        );
    
        return response()->json(['valid' => false, 'message' => 'License is inactive.'], 403);
        }

        if (Carbon::parse($license->expiry_date)->isPast()) {
            
        ApiLogger::log(
            'plugin',
            '/api/license/validate',
            'POST',
            $request->all(),
            ['valid' => true, 'user_id' => $license->user_id],
            200,
            microtime(true) - LARAVEL_START
        );
    
        return response()->json(['valid' => false, 'message' => 'License has expired.'], 403);
        }

        
        ApiLogger::log(
            'plugin',
            '/api/license/validate',
            'POST',
            $request->all(),
            ['valid' => true, 'user_id' => $license->user_id],
            200,
            microtime(true) - LARAVEL_START
        );
    
        return response()->json([
            'valid' => true,
            'message' => 'License is valid.',
            'type' => $license->type,
            'expires' => $license->expiry_date,
            'user_id' => $license->user_id,
        ]);
    }
}
