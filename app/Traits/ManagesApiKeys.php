<?php

namespace App\Traits;

use App\Models\ExternalApiKey;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait ManagesApiKeys
{
    protected function getApiKeyForProvider(string $providerName, ?User $user = null): ?string
    {
        $apiKey = null;
        $keySource = 'none';

        if ($user) {
            try {
                $userKeyRecord = ExternalApiKey::where("user_id", $user->id)
                    ->where("provider_name", $providerName)
                    ->first();

                if ($userKeyRecord && !empty($userKeyRecord->api_key)) {
                    $apiKey = Crypt::decryptString($userKeyRecord->api_key);
                    $keySource = 'user';
                    Log::info("Using user-provided API key for provider: {$providerName}, user: {$user->id}");
                }
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                Log::error("Failed to decrypt API key for provider: {$providerName}, user: {$user->id}. Error: " . $e->getMessage());
                $apiKey = null;
            }
        }

        if (!$apiKey) {
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

        return $apiKey;
    }

    /**
     * Validate an API key with a real check depending on the provider.
     */
    protected function validateApiKey(string $providerName, string $apiKey): bool
    {
        try {
            switch (strtolower($providerName)) {
                case 'google':
                    // Use PageSpeed API with dummy URL
                    $response = Http::get('https://www.googleapis.com/pagespeedonline/v5/runPagespeed', [
                        'url' => 'https://example.com',
                        'key' => $apiKey,
                        'strategy' => 'desktop',
                    ]);
                    return $response->ok();

                case 'huggingface':
                    $testModel = 'gpt2'; // lightweight text model
                    $response = Http::withToken($apiKey)
                        ->timeout(10)
                        ->post("https://api-inference.huggingface.co/models/{$testModel}", [
                            'inputs' => 'Test',
                        ]);
                    return $response->ok();

                case 'openai':
                    $response = Http::withToken($apiKey)
                        ->timeout(10)
                        ->get("https://api.openai.com/v1/models");
                    return $response->ok();

                default:
                    Log::warning("No validation logic implemented for provider: {$providerName}");
                    return !empty($apiKey);
            }
        } catch (\Exception $e) {
            Log::error("API key validation error for {$providerName}: " . $e->getMessage());
            return false;
        }
    }

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


