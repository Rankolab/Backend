<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class KeywordExtractionService
{
    protected $apiUrl;
    protected $apiToken;
    protected $model;

    public function __construct()
    {
        $this->apiUrl = rtrim(Config::get("services.huggingface.inference_api_url"), "/");
        $this->apiToken = Config::get("services.huggingface.api_token");
        $this->model = Config::get("services.huggingface.keyword_extraction_model");
    }

    /**
     * Extract keywords from the given text using Hugging Face Inference API.
     *
     * @param string $text The text to extract keywords from.
     * @return array|null An array of keywords, or null on failure.
     */
    public function extractKeywords(string $text): ?array
    {
        if (empty($this->model)) {
            Log::error("Hugging Face keyword extraction model not configured.");
            return null;
        }
        if (empty(trim($text))) {
            Log::warning("Attempted to extract keywords from empty text.");
            return null;
        }

        // Clean and prepare text (basic cleaning)
        $inputText = strip_tags($text);
        $inputText = preg_replace("/\s+/", " ", $inputText); // Replace multiple spaces/newlines with single space
        $inputText = trim($inputText);

        // Limit input length if necessary, depending on model constraints
        $inputText = Str::limit($inputText, 4000, ""); 

        if (empty($inputText)) {
            Log::warning("Text became empty after cleaning, cannot extract keywords.");
            return null;
        }

        Log::info("Attempting Hugging Face keyword extraction with model: {$this->model}");

        try {
            $response = $this->callHuggingFaceKeywordAPI($inputText);

            if ($response === null || !$response->successful()) {
                Log::error("Hugging Face Keyword Extraction API call failed. Status: " . ($response ? $response->status() : "N/A") . " Body: " . ($response ? $response->body() : "N/A"));
                 if ($response && $response->serverError() && str_contains($response->body(), "currently loading")) {
                     Log::warning("Keyword model {$this->model} is loading, try again later.");
                }
                return null;
            }

            // Response format for token classification / NER often involves a list of entities.
            // The exact format depends heavily on the specific model used.
            // Example assumption: returns an array of objects like {"word": "keyword", "score": 0.9, "entity_group": "KEY"}
            $responseData = $response->json();
            
            if (!is_array($responseData)) {
                 Log::error("Hugging Face Keyword Extraction API returned non-array data. Response: " . $response->body());
                 return null;
            }

            // Extract keywords based on the assumed structure (adjust based on actual model output)
            $keywords = [];
            foreach ($responseData as $entity) {
                if (isset($entity["word"])) { // Adjust key based on model output (
                    $keywords[] = trim($entity["word"]);
                }
            }
            
            // Remove duplicates and potential empty strings
            $keywords = array_filter(array_unique($keywords));

            if (empty($keywords)) {
                Log::warning("Hugging Face Keyword Extraction API returned no valid keywords. Response: " . $response->body());
                // Return empty array instead of null if the process succeeded but found no keywords
                return []; 
            }

            Log::info("Successfully extracted keywords via Hugging Face: " . implode(", ", $keywords));
            return array_values($keywords); // Re-index array

        } catch (\Exception $e) {
            Log::error("Error during keyword extraction via Hugging Face: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Calls the Hugging Face Inference API for keyword extraction (token classification/NER).
     *
     * @param string $text
     * @return \Illuminate\Http\Client\Response|null
     */
    private function callHuggingFaceKeywordAPI(string $text): ?\Illuminate\Http\Client\Response
    {
        $endpoint = $this->apiUrl . "/" . $this->model;
        $headers = [
            "Content-Type" => "application/json",
        ];

        if (!empty($this->apiToken)) {
            $headers["Authorization"] = "Bearer " . $this->apiToken;
        }

        try {
            $response = Http::withHeaders($headers)
                ->timeout(30) // Keyword extraction should be faster
                ->post($endpoint, [
                    "inputs" => $text,
                    // Some models might require specific parameters
                    // "parameters" => [ "aggregation_strategy" => "simple" ] 
                ]);
            
            return $response;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("Hugging Face Keyword API connection error: " . $e->getMessage());
            return null;
        } catch (\Exception $e) {
            Log::error("Error during Hugging Face Keyword API call: " . $e->getMessage());
            return null;
        }
    }
}

