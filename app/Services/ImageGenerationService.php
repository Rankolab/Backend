<?php

namespace App\Services;

use App\Models\Blog;
use Illuminate\Support\Facades\Http; // Use Laravel HTTP Client (wraps Guzzle)
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config; // To access config values

class ImageGenerationService
{
    protected $apiUrl;
    protected $apiToken;
    protected $model;

    public function __construct()
    {
        $this->apiUrl = rtrim(Config::get("services.huggingface.inference_api_url"), "/");
        $this->apiToken = Config::get("services.huggingface.api_token"); // May be null for free tier
        $this->model = Config::get("services.huggingface.image_generation_model");
    }

    /**
     * Generate a relevant cover image for a blog post using Hugging Face Inference API.
     *
     * @param Blog $blog
     * @return string|null The public URL of the generated image, or null on failure.
     */
    public function generateCoverImage(Blog $blog): ?string
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

            Log::info("Attempting Hugging Face image generation for blog ID: {$blog->id} with model: {$this->model} and prompt: '{$prompt}'");

            $response = $this->callHuggingFaceTextToImageAPI($prompt);

            if ($response === null || !$response->successful()) {
                Log::error("Hugging Face API call failed for blog ID: {$blog->id}. Status: " . ($response ? $response->status() : "N/A") . " Body: " . ($response ? $response->body() : "N/A"));
                // Handle specific errors like model loading
                if ($response && $response->serverError() && str_contains($response->body(), "currently loading")) {
                     Log::warning("Model {$this->model} is loading, try again later.");
                     // Optionally, you could throw a specific exception or return a specific message
                }
                return null;
            }

            // Assuming the API returns raw image data in the body
            $imageData = $response->body();
            if (empty($imageData)) {
                 Log::error("Hugging Face API returned empty image data for blog ID: {$blog->id}.");
                 return null;
            }

            // Generate a unique filename
            $filename = "generated_" . $blog->slug . "_" . time() . ".jpg"; // Assuming JPG output, adjust if needed
            $relativePath = "public/blog_covers/" . $filename;

            // Save the image data to storage
            $saved = Storage::put($relativePath, $imageData);

            if (!$saved) {
                Log::error("Failed to save generated image to storage for blog ID: {$blog->id}. Path: {$relativePath}");
                return null;
            }

            Log::info("Successfully generated and saved image via Hugging Face for blog ID: {$blog->id}. Path: {$relativePath}");

            // Return the public URL
            return Storage::url($relativePath);

        } catch (\Exception $e) {
            Log::error("Error generating cover image via Hugging Face for blog ID: " . $blog->id . " - " . $e->getMessage());
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
     * @return \Illuminate\Http\Client\Response|null
     */
    private function callHuggingFaceTextToImageAPI(string $prompt): ?\Illuminate\Http\Client\Response
    {
        $endpoint = $this->apiUrl . "/" . $this->model;
        $headers = [
            "Content-Type" => "application/json",
        ];

        if (!empty($this->apiToken)) {
            $headers["Authorization"] = "Bearer " . $this->apiToken;
        }

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
            Log::error("Hugging Face API connection error: " . $e->getMessage());
            return null;
        } catch (\Exception $e) {
            Log::error("Error during Hugging Face API call: " . $e->getMessage());
            return null;
        }
    }
}

