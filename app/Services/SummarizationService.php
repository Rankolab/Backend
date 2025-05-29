<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class SummarizationService
{
    protected $apiUrl;
    protected $apiToken;
    protected $model;

    public function __construct()
    {
        $this->apiUrl = rtrim(Config::get("services.huggingface.inference_api_url"), "/");
        $this->apiToken = Config::get("services.huggingface.api_token");
        $this->model = Config::get("services.huggingface.summarization_model");
    }

    /**
     * Summarize the given text using Hugging Face Inference API.
     *
     * @param string $text The text to summarize.
     * @param int $maxLength Max length of the summary.
     * @param int $minLength Min length of the summary.
     * @return string|null The summarized text, or null on failure.
     */
    public function summarizeText(string $text, int $maxLength = 150, int $minLength = 30): ?string
    {
        if (empty($this->model)) {
            Log::error("Hugging Face summarization model not configured.");
            return null;
        }
        if (empty(trim($text))) {
            Log::warning("Attempted to summarize empty text.");
            return null;
        }

        // Clean and prepare text (basic cleaning)
        $inputText = strip_tags($text);
        $inputText = preg_replace("/\s+/", " ", $inputText); // Replace multiple spaces/newlines with single space
        $inputText = trim($inputText);

        // Avoid sending overly long text if the model has limits (adjust limit as needed)
        $inputText = Str::limit($inputText, 4000, ""); // Limit input length

        if (empty($inputText)) {
            Log::warning("Text became empty after cleaning, cannot summarize.");
            return null;
        }

        Log::info("Attempting Hugging Face text summarization with model: {$this->model}");

        try {
            $response = $this->callHuggingFaceSummarizationAPI($inputText, $maxLength, $minLength);

            if ($response === null || !$response->successful()) {
                Log::error("Hugging Face Summarization API call failed. Status: " . ($response ? $response->status() : "N/A") . " Body: " . ($response ? $response->body() : "N/A"));
                if ($response && $response->serverError() && str_contains($response->body(), "currently loading")) {
                     Log::warning("Summarization model {$this->model} is loading, try again later.");
                }
                return null;
            }

            // Response format might vary; common format is [{"summary_text": "..."}]
            $responseData = $response->json();
            $summary = $responseData[0]["summary_text"] ?? null;

            if (empty($summary)) {
                Log::error("Hugging Face Summarization API returned empty or invalid summary. Response: " . $response->body());
                return null;
            }

            Log::info("Successfully generated summary via Hugging Face.");
            return trim($summary);

        } catch (\Exception $e) {
            Log::error("Error during text summarization via Hugging Face: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Calls the Hugging Face Inference API for summarization.
     *
     * @param string $text
     * @param int $maxLength
     * @param int $minLength
     * @return \Illuminate\Http\Client\Response|null
     */
    private function callHuggingFaceSummarizationAPI(string $text, int $maxLength, int $minLength): ?\Illuminate\Http\Client\Response
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
                ->timeout(45) // Summarization might take time
                ->post($endpoint, [
                    "inputs" => $text,
                    "parameters" => [
                        "max_length" => $maxLength,
                        "min_length" => $minLength,
                        // Add other parameters if needed
                    ]
                ]);
            
            return $response;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("Hugging Face Summarization API connection error: " . $e->getMessage());
            return null;
        } catch (\Exception $e) {
            Log::error("Error during Hugging Face Summarization API call: " . $e->getMessage());
            return null;
        }
    }
}

