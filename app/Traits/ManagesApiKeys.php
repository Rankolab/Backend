<?php

namespace App\Traits;

use App\Models\ExternalApiKey;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

trait ManagesApiKeys
{
    /**
     * Get the appropriate API key for a given provider and user.
     *
     * Checks for a user-specific key first, then falls back to a default key
     * defined in the configuration (config/services.php).
     *
     * @param string $providerName The name of the API provider (e.g., 'openai', 'google_pagespeed').
     * @param User|null $user The user instance, if applicable.
     * @return string|null The API key or null if not found.
     */
    protected function getApiKeyForProvider(string $providerName, ?User $user = null): ?string
    {
        $apiKey = null;
        $keySource = 'none'; // To track where the key came from

        // 1. Check for user-specific key if user is provided
        if ($user) {
            try {
                $userKeyRecord = ExternalApiKey::where("user_id", $user->id)
                    ->where("provider_name", $providerName)
                    // Optionally add ->where("is_valid", true) if validation is implemented
                    ->first();

                if ($userKeyRecord && !empty($userKeyRecord->api_key)) {
                    $apiKey = Crypt::decryptString($userKeyRecord->api_key);
                    $keySource = 'user';
                    Log::info("Using user-provided API key for provider: {$providerName}, user: {$user->id}");
                }
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                Log::error("Failed to decrypt API key for provider: {$providerName}, user: {$user->id}. Error: " . $e->getMessage());
                // Optionally invalidate the key: $userKeyRecord->update(["is_valid" => false]);
                $apiKey = null; // Ensure key is null if decryption fails
            }
        }

        // 2. Fallback to default key if no valid user key found
        if (!$apiKey) {
            // Construct the config key path, e.g., 'services.openai.api_key'
            $configKey = "services.{$providerName}.api_key";
            $defaultKey = Config::get($configKey);

            if ($defaultKey) {
                $apiKey = $defaultKey;
                $keySource = 'default';
                Log::info("Using default API key for provider: {$providerName}");
            }
        }

        if (!$apiKey) {
            Log::warning("No API key found (user or default) for provider: {$providerName}");
        }

        // Optional: Log API usage (consider doing this closer to the actual API call)
        // if ($apiKey) {
        //     Log::channel("api_usage")->info("API Key retrieved", [
        //         "provider" => $providerName,
        //         "user_id" => $user ? $user->id : null,
        //         "key_source" => $keySource
        //     ]);
        // }

        return $apiKey;
    }

    /**
     * Placeholder for validating an API key.
     *
     * This should be implemented specifically for each service that needs validation.
     *
     * @param string $providerName
     * @param string $apiKey
     * @return bool
     */
    protected function validateApiKey(string $providerName, string $apiKey): bool
    {
        // Example: Make a test call to the provider's API
        Log::warning("API key validation not implemented for provider: {$providerName}");
        // For now, assume valid if provided
        return !empty($apiKey);
    }

    /**
     * Update the validation status of a user's API key.
     *
     * @param User $user
     * @param string $providerName
     * @param bool $isValid
     * @return void
     */
    protected function updateApiKeyValidationStatus(User $user, string $providerName, bool $isValid): void
    {
        try {
            ExternalApiKey::where("user_id", $user->id)
                ->where("provider_name", $providerName)
                ->update([
                    "is_valid" => $isValid,
                    "last_validated" => now(),
                ]);
            Log::info("Updated API key validation status for provider: {$providerName}, user: {$user->id}, status: " . ($isValid ? 'valid' : 'invalid'));
        } catch (\Exception $e) {
            Log::error("Failed to update API key validation status for provider: {$providerName}, user: {$user->id}. Error: " . $e->getMessage());
        }
    }
}

