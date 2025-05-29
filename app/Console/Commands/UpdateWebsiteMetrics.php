<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Website;
use App\Services\SeoMetricsService;
use Illuminate\Support\Facades\Log;

class UpdateWebsiteMetrics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rankolab:update-metrics {website_id? : The ID of the website to update metrics for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and update SEO metrics (PageSpeed, etc.) for a specific website or all websites.';

    /**
     * Execute the console command.
     */
    public function handle(SeoMetricsService $metricsService)
    {
        $websiteId = $this->argument('website_id');

        if ($websiteId) {
            $website = Website::find($websiteId);
            if (!$website) {
                $this->error("Website with ID {$websiteId} not found.");
                return 1; // Indicate error
            }
            $this->info("Updating metrics for website ID: {$websiteId} ({$website->domain})...");
            try {
                $metricsService->updateAllMetrics($website);
                $this->info("Successfully updated metrics for website ID: {$websiteId}.");
            } catch (\Exception $e) {
                $this->error("Failed to update metrics for website ID: {$websiteId}. Error: " . $e->getMessage());
                Log::error("Artisan command rankolab:update-metrics failed for website ID {$websiteId}: " . $e->getMessage());
                return 1;
            }
        } else {
            $this->info("Updating metrics for all websites...");
            $websites = Website::all();
            if ($websites->isEmpty()) {
                $this->info("No websites found to update.");
                return 0;
            }

            $successCount = 0;
            $failCount = 0;

            foreach ($websites as $website) {
                $this->line("Processing website ID: {$website->website_id} ({$website->domain})...");
                try {
                    $metricsService->updateAllMetrics($website);
                    $this->info(" -> Success.");
                    $successCount++;
                } catch (\Exception $e) {
                    $this->error(" -> Failed. Error: " . $e->getMessage());
                    Log::error("Artisan command rankolab:update-metrics failed for website ID {$website->website_id}: " . $e->getMessage());
                    $failCount++;
                }
            }
            $this->info("Finished updating metrics. Success: {$successCount}, Failed: {$failCount}.");
        }

        return 0; // Indicate success
    }
}

