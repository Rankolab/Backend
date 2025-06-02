<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Google\Service\PagespeedInsights;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Added for getting user
use App\Models\WebsiteMetric;
use App\Models\Website;
use Carbon\Carbon;
use App\Traits\ManagesApiKeys; // Import the trait

class SeoMetricsService
{
    use ManagesApiKeys; // Use the trait

    protected $googleClient;
    // Removed apiKey property, will fetch using trait

    public function __construct()
    {
        // Initialize Google Client - API Key will be fetched per request using the trait
        $this->googleClient = new GoogleClient();
        $this->googleClient->setApplicationName("Rankolab Backend");
        // No API key set here directly
        // No OAuth setup here, as it requires user flow. Search Console integration will need separate handling.
    }

    /**
     * Fetch and update PageSpeed Insights score for a website.
     * Uses a free, open-source API (Google PageSpeed Insights).
     */
    public function updatePageSpeedScore(Website $website)
    {
        // Get the API key using the trait, passing the website's owner
        $googleApiKey = $this->getApiKey("google", $website->user);

        if (!$googleApiKey) {
            Log::error("Cannot update PageSpeed score for website ID {$website->website_id}: Google API Key is missing or not configured.");
            return false;
        }

        try {
            // Set the developer key for this specific request
            $this->googleClient->setDeveloperKey($googleApiKey);
            $pagespeedService = new PagespeedInsights($this->googleClient);
            
            // Fetch for both strategies if needed, or just one
            $desktopResult = $pagespeedService->pagespeedapi->runpagespeed($website->domain, ["strategy" => "DESKTOP"]);
            $mobileResult = $pagespeedService->pagespeedapi->runpagespeed($website->domain, ["strategy" => "MOBILE"]);

            // Extract the performance scores (0-100)
            $desktopScore = $desktopResult->getLighthouseResult()->getCategories()->getPerformance()->getScore() * 100;
            $mobileScore = $mobileResult->getLighthouseResult()->getCategories()->getPerformance()->getScore() * 100;
            
            // Example: Use the average or prioritize one (e.g., mobile)
            $finalScore = round(($desktopScore + $mobileScore) / 2);

            // Update the WebsiteMetric record
            $metrics = $website->metrics()->firstOrCreate(["website_id" => $website->website_id]);
            $metrics->page_speed_score = $finalScore; // Store the calculated score
            // Optionally store desktop/mobile scores separately if needed
            // $metrics->page_speed_desktop = round($desktopScore);
            // $metrics->page_speed_mobile = round($mobileScore);
            $metrics->last_analyzed = Carbon::now(); // Update last analyzed time
            $metrics->save();

            Log::info("Updated PageSpeed score for website ID: " . $website->website_id . " to " . $metrics->page_speed_score);
            return true;

        } catch (\Exception $e) {
            Log::error("Error fetching PageSpeed Insights for website ID: " . $website->website_id . " - " . $e->getMessage());
            if (str_contains($e->getMessage(), "API key not valid")) {
                 Log::error("Google API Key may be invalid or missing required permissions.");
            }
            return false;
        }
    }

    /**
     * Placeholder for updating metrics from Google Search Console.
     * Requires OAuth 2.0 setup and user consent.
     */
    public function updateSearchConsoleMetrics(Website $website)
    {
        // OAuth logic would go here, potentially using a stored user token
        Log::info("Placeholder: updateSearchConsoleMetrics called for website ID: " . $website->website_id . ". Requires OAuth setup.");
        $metrics = $website->metrics()->firstOrCreate(["website_id" => $website->website_id]);
        $metrics->last_analyzed = Carbon::now();
        $metrics->save();
        return true;
    }

    /**
     * Placeholder for updating Domain Authority and Backlinks.
     * Requires external APIs (e.g., Moz, Ahrefs) and API keys managed via the trait.
     */
    public function updateDomainAuthorityAndBacklinks(Website $website)
    {
        // Example: Fetching a hypothetical Moz API key
        // $mozApiKey = $this->getApiKey("moz", $website->user);
        // if ($mozApiKey) { ... call Moz API ... }
        
        Log::info("Placeholder: updateDomainAuthorityAndBacklinks called for website ID: " . $website->website_id . ". Requires external API integration.");
        $metrics = $website->metrics()->firstOrCreate(["website_id" => $website->website_id]);
        $metrics->last_analyzed = Carbon::now();
        $metrics->save();
        return true;
    }

    /**
     * Placeholder for updating SEO Score.
     * Calculation logic needs refinement based on available metrics.
     */
     public function updateSeoScore(Website $website)
     {
         Log::info("Updating SEO Score for website ID: " . $website->website_id);
         $metrics = $website->metrics()->firstOrCreate(["website_id" => $website->website_id]);
         
         $score = 0;
         if (isset($metrics->page_speed_score) && is_numeric($metrics->page_speed_score)) {
             $score = $metrics->page_speed_score;
         } else {
             Log::warning("PageSpeed score not available for website ID: " . $website->website_id . ". SEO score calculation might be inaccurate.");
         }

         // Add other factors here when available (DA, backlinks, etc.)

         $metrics->seo_score = max(0, min(100, round($score)));
         $metrics->last_analyzed = Carbon::now();
         $metrics->save();

         Log::info("Updated SEO score for website ID: " . $website->website_id . " to " . $metrics->seo_score);
         return true;
     }

    /**
     * Trigger updates for all relevant SEO metrics for a website.
     */
    public function updateAllMetrics(Website $website)
    {
        Log::info("Starting metric update for website ID: " . $website->website_id);
        $this->updatePageSpeedScore($website);
        $this->updateSearchConsoleMetrics($website); // Placeholder
        $this->updateDomainAuthorityAndBacklinks($website); // Placeholder
        $this->updateSeoScore($website); // Placeholder
        Log::info("Finished metric update for website ID: " . $website->website_id);
    }
}



