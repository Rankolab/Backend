<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\User; // Added for type hinting
use App\Traits\ManagesApiKeys; // Import the trait
use Illuminate\Support\Facades\Http; // Use Laravel HTTP Client (wraps Guzzle)
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config; // To access config values

class ImageGenerationService
{
    use ManagesApiKeys; // Use the trait

    protected $apiUrl;
    // protected $apiToken; // Removed, will be fetched via trait
    protected $model;

    public function __construct()
    {
        $this->apiUrl = rtrim(Config::get("services.huggingface.inference_api_url"), "/");
        // $this->apiToken = Config::get("services.huggingface.api_token"); // Removed
        $this->model = Config::get("services.huggingface.image_generation_model");
    }

    /**
     * Generate a relevant cover image for a blog post using Hugging Face Inference API.
     *
     * @param Blog $blog
     * @param User|null $user The user requesting the generation (for API key lookup).
     * @return string|null The public URL of the generated image, or null on failure.
     */
    public function generateCoverImage(Blog $blog, ?User $user = null): ?string
    {
        if (empty($this->model)) {
            Log::error("Hugging Face image generation model not configured.");
            return null;
        }

        try {
            $prompt = $this->createPromptFromBlog($blog);
            if (empty($prompt)) {
                Log::warning("Could not generate a meaningful prompt for blog ID: " . $blog->id);
                return null;
            }

            Log::info("Attempting Hugging Face image generation for blog ID: {$blog->id} with model: {$this->model} and prompt: '{$prompt}'", ["user_id" => $user?->id]);

            // Pass the user context to the API call method
            $response = $this->callHuggingFaceTextToImageAPI($prompt, $user);

            if ($response === null || !$response->successful()) {
                Log::error("Hugging Face API call failed for blog ID: {$blog->id}. Status: " . ($response ? $response->status() : "N/A") . " Body: " . ($response ? $response->body() : "N/A"), ["user_id" => $user?->id]);
                // Handle specific errors like model loading
                if ($response && $response->serverError() && str_contains($response->body(), "currently loading")) {
                     Log::warning("Model {$this->model} is loading, try again later.");
                }
                return null;
            }

            // Assuming the API returns raw image data in the body
            $imageData = $response->body();
            if (empty($imageData)) {
                 Log::error("Hugging Face API returned empty image data for blog ID: {$blog->id}.", ["user_id" => $user?->id]);
                 return null;
            }

            // Generate a unique filename
            $filename = "generated_" . $blog->slug . "_" . time() . ".jpg"; // Assuming JPG output, adjust if needed
            $relativePath = "public/blog_covers/" . $filename;

            // Save the image data to storage
            $saved = Storage::put($relativePath, $imageData);

            if (!$saved) {
                Log::error("Failed to save generated image to storage for blog ID: {$blog->id}. Path: {$relativePath}", ["user_id" => $user?->id]);
                return null;
            }

            Log::info("Successfully generated and saved image via Hugging Face for blog ID: {$blog->id}. Path: {$relativePath}", ["user_id" => $user?->id]);

            // Return the public URL
            return Storage::url($relativePath);

        } catch (\Exception $e) {
            Log::error("Error generating cover image via Hugging Face for blog ID: " . $blog->id . " - " . $e->getMessage(), ["user_id" => $user?->id]);
            return null;
        }
    }

    /**
     * Create a prompt for image generation based on blog content.
     *
     * @param Blog $blog
     * @return string
     */
    private function createPromptFromBlog(Blog $blog): string
    {
        $baseText = $blog->title;
        if (empty($baseText)) {
            $baseText = $blog->excerpt;
        }
        if (empty($baseText)) {
            $baseText = Str::limit(strip_tags($blog->body), 150); // Use more body text
        }

        if (empty($baseText)) {
            return ""; // Cannot generate prompt if base text is empty
        }

        // Refined prompt
        $prompt = "High-quality digital art illustration representing the concept: " . Str::limit($baseText, 200) . ". Suitable for a blog post cover image.";

        return $prompt;
    }

    /**
     * Calls the Hugging Face Inference API for text-to-image.
     *
     * @param string $prompt
     * @param User|null $user The user context for API key lookup.
     * @return \Illuminate\Http\Client\Response|null
     */
    private function callHuggingFaceTextToImageAPI(string $prompt, ?User $user = null): ?\Illuminate\Http\Client\Response
    {
        // Use the trait method to get the API key
        $apiToken = $this->getApiKeyForProvider('huggingface', $user);

        if (empty($apiToken)) {
            // Log::error("Hugging Face API token not found (user or default)."); // Already logged in trait
            return null; // Cannot proceed without a token
        }

        $endpoint = $this->apiUrl . "/" . $this->model;
        $headers = [
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $apiToken, // Use the fetched token
        ];

        // Removed redundant token check here

        try {
            // Use Http facade, configure timeout
            $response = Http::withHeaders($headers)
                ->timeout(60) // Set timeout (e.g., 60 seconds) as image generation can take time
                ->post($endpoint, [
                    "inputs" => $prompt,
                    // Add other parameters if needed, e.g., negative_prompt, guidance_scale
                    // "parameters" => [ "negative_prompt" => "ugly, blurry" ]
                ]);

            return $response;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("Hugging Face API connection error: " . $e->getMessage(), ["user_id" => $user?->id]);
            return null;
        } catch (\Exception $e) {
            Log::error("Error during Hugging Face API call: " . $e->getMessage(), ["user_id" => $user?->id]);
            return null;
        }
    }
}



