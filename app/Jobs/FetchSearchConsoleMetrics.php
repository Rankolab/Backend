<?php

namespace App\Jobs;

use App\Models\Website;
use App\Models\SearchMetric;
use App\Services\SearchConsoleService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class FetchSearchConsoleMetrics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $service = new SearchConsoleService();
        $yesterday = now()->subDay()->toDateString();

        foreach (Website::all() as $site) {
            try {
                $response = $service->getPerformanceReport($site->domain, $yesterday, $yesterday);
                foreach ($response->getRows() as $row) {
                    SearchMetric::updateOrCreate(
                        [
                            'website_id' => $site->id,
                            'date' => $yesterday,
                            'query' => $row['keys'][0] ?? null,
                        ],
                        [
                            'clicks' => $row['clicks'],
                            'impressions' => $row['impressions'],
                            'ctr' => $row['ctr'] * 100,
                        ]
                    );
                }
            } catch (\Exception $e) {
                \Log::error('SearchConsole API error for ' . $site->domain . ': ' . $e->getMessage());
            }
        }
    }
}
