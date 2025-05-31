<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\WebsiteMetric; // Assuming this model exists based on documentation
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DomainAnalysisController extends Controller
{
    /**
     * Get Domain Analysis metrics for a specific website.
     *
     * Retrieves the latest stored metrics from the database.
     * Note: Populating these metrics requires separate background jobs
     *       to interact with external APIs (Moz, PageSpeed Insights, etc.),
     *       which are not implemented in this controller.
     *
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAnalysis(Website $website)
    {
        // Basic authorization check (ensure the authenticated user owns the website or is admin)
        if (Auth::user()->id !== $website->user_id && !Auth::user()->hasRole(['admin', 'super_admin'])) {
             Log::warning("Unauthorized attempt to access domain analysis for website ID: {$website->id} by user ID: " . Auth::user()->id);
             return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Fetch the latest metrics record for this website from the database
        $latestMetric = WebsiteMetric::where('website_id', $website->id)
                                     ->orderBy('created_at', 'desc') // Assuming created_at tracks when the metric was recorded
                                     ->first();

        if (!$latestMetric) {
            Log::info("No domain analysis metrics found for website ID: {$website->id}");
            // Return a 404 or an empty/default structure based on requirements
            // For now, returning 404 as per previous PerformanceController example
            return response()->json(['error' => 'No domain analysis data found for this website'], 404);
        }

        // Format the data according to the API documentation structure
        $responseData = [
            'domain_authority' => $latestMetric->domain_authority ?? null,
            'backlinks_count' => $latestMetric->backlinks_count ?? null,
            'spam_score' => $latestMetric->spam_score ?? null,
            'page_speed' => [
                'desktop' => $latestMetric->page_speed_desktop ?? null,
                'mobile' => $latestMetric->page_speed_mobile ?? null,
            ],
            // Recommendations might be dynamically generated based on metrics or stored separately.
            // Keeping placeholder recommendations for now, as generation logic is complex.
            'recommendations' => [
                'Review latest performance metrics.',
                'Ensure content strategy aligns with SEO goals.',
            ],
            'last_updated' => $latestMetric->created_at ? $latestMetric->created_at->toIso8601String() : null,
        ];

        Log::info("Successfully retrieved domain analysis for website ID: {$website->id}");

        return response()->json($responseData);
    }

    // Potential future methods for backlink details, etc.
}

