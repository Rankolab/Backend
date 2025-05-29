<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApiService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use App\Models\ApiLog;


class CheckApiHealth extends Command
{
    protected $signature = 'rankolab:check-api-health';
    protected $description = 'Checks the health of configured API services';

    public function handle()
    {
        $services = ApiService::all();
        foreach ($services as $service) {
            $start = microtime(true);
            try {
                $response = Http::timeout(5)->get($service->base_url);
                $responseTime = round((microtime(true) - $start) * 1000);
                $status = $response->successful() ? ($responseTime > 1500 ? 'slow' : 'online') : 'offline';
    
                // Log status
                ApiLog::create([
                    'service_name' => $service->name,
                    'status' => $status,
                    'message' => isset($e) ? $e->getMessage() : 'Checked successfully',
                    'response_code' => isset($response) ? $response->status() : null,
                    'executed_at' => now(),
                ]);

        } catch (\Exception $e) {
                $status = 'offline';
                $responseTime = null;
                $this->error("Failed to check {$service->name}: " . $e->getMessage());
    
                // Log status
                ApiLog::create([
                    'service_name' => $service->name,
                    'status' => $status,
                    'message' => isset($e) ? $e->getMessage() : 'Checked successfully',
                    'response_code' => isset($response) ? $response->status() : null,
                    'executed_at' => now(),
                ]);

        }

            $service->update([
                'status' => $status,
                'response_time_ms' => $responseTime,
                'last_checked_at' => Carbon::now(),
                'error_count' => $status === 'offline' ? $service->error_count + 1 : 0,
            ]);

            $this->info("{$service->name} is {$status} ({$responseTime} ms)");

                // Log status
                ApiLog::create([
                    'service_name' => $service->name,
                    'status' => $status,
                    'message' => isset($e) ? $e->getMessage() : 'Checked successfully',
                    'response_code' => isset($response) ? $response->status() : null,
                    'executed_at' => now(),
                ]);

        }
    }
}
