<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExternalApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt; // Use Laravel's built-in encryption

class ExternalApiKeyController extends Controller
{
    /**
     * Display a listing of the user's API keys.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        // Return only provider_name, is_valid, last_validated, updated_at for security
        $keys = $user->externalApiKeys()->select(
            'provider_name', 
            'is_valid', 
            'last_validated', 
            'updated_at'
        )->get();

        return response()->json($keys);
    }

    /**
     * Store a newly created or updated API key for the user.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'provider_name' => 'required|string|max:100',
            'api_key' => 'required|string',
            // Add validation rules for specific providers if needed
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Use updateOrCreate to handle both adding new keys and updating existing ones
        $apiKey = ExternalApiKey::updateOrCreate(
            [
                'user_id' => $user->id,
                'provider_name' => $request->input('provider_name'),
            ],
            [
                'api_key' => Crypt::encryptString($request->input('api_key')), // Encrypt the key
                'is_valid' => false, // Mark as invalid until validated
                'last_validated' => null,
            ]
        );

        // Optionally: Trigger an immediate validation job here
        // dispatch(new ValidateApiKeyJob($apiKey));

        // Return only safe fields
        return response()->json([
            'message' => 'API key saved successfully. Validation pending.',
            'provider_name' => $apiKey->provider_name,
            'is_valid' => $apiKey->is_valid,
            'updated_at' => $apiKey->updated_at,
        ], 201);
    }

    /**
     * Remove the specified API key for the user.
     * Note: Route model binding won't work directly with provider_name.
     * We'll find the key based on the authenticated user and provider name.
     */
    public function destroy(Request $request, string $provider)
    {
        $user = $request->user();

        $deleted = ExternalApiKey::where('user_id', $user->id)
                                 ->where('provider_name', $provider)
                                 ->delete();

        if ($deleted) {
            return response()->json(['message' => 'API key for ' . $provider . ' deleted successfully.'], 200);
        } else {
            return response()->json(['message' => 'API key for ' . $provider . ' not found.'], 404);
        }
    }

    // Remove unused default methods if desired (show, update)
    // public function show(ExternalApiKey $externalApiKey) { /* ... */ }
    // public function update(Request $request, ExternalApiKey $externalApiKey) { /* ... */ }
}

