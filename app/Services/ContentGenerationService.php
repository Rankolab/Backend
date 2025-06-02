<?php

namespace App\Services;

use App\Models\ContentPlan;
use App\Models\Content;
use App\Models\Website;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth; // Added for getting user
use Carbon\Carbon;
use App\Traits\ManagesApiKeys; // Import the trait

class ContentGenerationService
{
    use ManagesApiKeys; // Use the trait

    // Removed constructor that directly fetched env variables

    /**
     * Summarize text using ApyHub API.
     *
     * @param string $text The text to summarize.
     * @param string $length ("short", "medium", "long")
     * @return string|null The summary or null on failure.
     */
    private function summarizeTextWithApyHub(string $text, string $length = "medium"): ?string
    {
        $apyhubApiKey = $this->getApiKey("apyhub", Auth::user()); // Use trait to get key

        if (!$apyhubApiKey) {
            Log::error("Cannot summarize text: ApyHub API Key is missing or not configured.");
            return null;
        }

        try {
            $response = Http::withHeaders([
                "apy-token" => $apyhubApiKey,
                "Content-Type" => "application/json",
            ])->post("https://api.apyhub.com/ai/summarize-text", [
                "text" => $text,
                "summary_length" => $length,
            ]);

            if ($response->successful() && isset($response->json()["data"]["summary"])) {
                Log::info("Successfully summarized text using ApyHub.");
                return $response->json()["data"]["summary"];
            } else {
                Log::error("ApyHub summarization failed. Status: " . $response->status() . " Body: " . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            Log::error("Exception during ApyHub summarization: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract keywords using Cortical.io API.
     *
     * @param string $text The text to extract keywords from.
     * @param int $limit Max number of keywords.
     * @return array|null List of keywords or null on failure.
     */
    private function extractKeywordsWithCortical(string $text, int $limit = 10): ?array
    {
        $corticalApiKey = $this->getApiKey("cortical", Auth::user()); // Use trait to get key

        if (!$corticalApiKey) {
            Log::error("Cannot extract keywords: Cortical.io API Key is missing or not configured.");
            return null;
        }

        try {
            $response = Http::withHeaders([
                "Authorization" => "Bearer " . $corticalApiKey,
                "Content-Type" => "application/json",
            ])->post("https://api.cortical.io/rest/text/keywords", [
                "text" => $text,
                "retina_name" => "en_associative"
            ]);

            if ($response->successful() && is_array($response->json())) {
                Log::info("Successfully extracted keywords using Cortical.io.");
                return array_slice($response->json(), 0, $limit);
            } else {
                Log::error("Cortical.io keyword extraction failed. Status: " . $response->status() . " Body: " . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            Log::error("Exception during Cortical.io keyword extraction: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate content based on a content plan, using AI tools if available.
     *
     * @param ContentPlan $plan The content plan to generate content for.
     * @return Content|null The generated content model instance, or null on failure.
     */
    public function generateContent(ContentPlan $plan): ?Content
    {
        Log::info("Generating content for plan ID: " . $plan->content_plan_id . " - Title: " . $plan->topic);

        try {
            $title = $plan->topic ?? "Generated Content";
            $initialKeywords = $plan->keywords ?? [];
            $targetAudience = $plan->target_audience ?? "";

            $baseText = "Introduction to {$title}. Key aspects include: " . implode(", ", $initialKeywords) . ". This content is targeted towards {$targetAudience}.";

            // Use the internal methods which now use the trait for API keys
            $summary = $this->summarizeTextWithApyHub($baseText, "short");
            $extractedKeywords = $this->extractKeywordsWithCortical($baseText);
            $finalKeywords = $extractedKeywords ? array_unique(array_merge($initialKeywords, $extractedKeywords)) : $initialKeywords;

            $body = $summary ? "Summary: {$summary}\n\n{$baseText}" : $baseText;
            $body .= "\n\nKeywords: [" . implode(", ", $finalKeywords) . "]";
            $body .= "\n\n(Note: This content was auto-generated using basic AI assistance. Please review and expand.)";

            $content = Content::create([
                "website_id" => $plan->website_id,
                "content_plan_id" => $plan->content_plan_id,
                "title" => $title,
                "content_type" => $plan->content_type ?? "blog_post",
                "status" => "draft",
                "content_data" => json_encode([
                    "body" => $body,
                    "keywords" => $finalKeywords,
                    "target_audience" => $targetAudience,
                    "ai_summary" => $summary,
                ]),
                "source_url" => null,
                "guid" => null,
                "published_at" => null,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ]);

            Log::info("AI-assisted content generated with ID: " . $content->content_id);
            return $content;

        } catch (\Exception $e) {
            Log::error("Error generating AI-assisted content for plan ID: " . $plan->content_plan_id . " - " . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate content ideas based on keywords or topics.
     * Placeholder - could integrate with a title generation API.
     *
     * @param Website $website
     * @param array $keywords
     * @return array List of content ideas (strings).
     */
    public function generateContentIdeas(Website $website, array $keywords): array
    {
        Log::info("Generating content ideas (placeholder) for website ID: " . $website->website_id);
        $ideas = [];
        foreach ($keywords as $keyword) {
            $ideas[] = "How to use {$keyword} effectively for your website";
            $ideas[] = "Top 5 benefits of {$keyword} in 2025";
            $ideas[] = "A beginner's guide to {$keyword}";
            $ideas[] = "{$keyword}: Common Mistakes to Avoid";
            $ideas[] = "The Future of {$keyword}";
        }
        return array_slice($ideas, 0, 5);
    }
}



