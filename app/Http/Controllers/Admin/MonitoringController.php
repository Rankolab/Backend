<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log; // For potential future structured logging
use Illuminate\Support\Facades\File; // For reading log file
use Illuminate\Support\Facades\Schema; // To check for failed_jobs table

class MonitoringController extends Controller
{
    /**
     * Get recent API logs (basic implementation - reads Laravel log file).
     * Note: For production, consider structured logging (e.g., database, ELK stack).
     */
    public function getApiLogs(Request $request)
    {
        $logPath = storage_path("logs/laravel.log");
        $lines = [];
        $maxLines = $request->input("lines", 200); // Default to last 200 lines

        if (File::exists($logPath)) {
            // Read the file line by line from the end
            $file = new \SplFileObject($logPath, "r");
            $file->seek(PHP_INT_MAX); // Go to end
            $lastLine = $file->key();
            
            $iterator = new \LimitIterator($file, max(0, $lastLine - $maxLines), $lastLine);
            $lines = iterator_to_array($iterator);
            $lines = array_reverse($lines); // Show newest first
            $lines = array_map("trim", $lines); // Trim whitespace
        }

        return response()->json(["logs" => $lines]);
    }

    /**
     * Get status of the job queue, focusing on failed jobs.
     */
    public function getJobQueueStatus(Request $request)
    {
        $failedJobs = [];
        if (Schema::hasTable("failed_jobs")) {
             $failedJobs = DB::table("failed_jobs")
                            ->orderBy("failed_at", "desc")
                            ->paginate(20); // Paginate failed jobs
        } else {
            return response()->json(["error" => "Failed jobs table not found. Run 'php artisan queue:failed-table' and 'php artisan migrate'."], 500);
        }
       
        return response()->json($failedJobs);
    }

    /**
     * Retry a specific failed job.
     */
    public function retryJob(Request $request, string $failedJobId)
    {
        if (!Schema::hasTable("failed_jobs")) {
             return response()->json(["error" => "Failed jobs table not found."], 500);
        }

        // Use Artisan command to retry the job
        $exitCode = Artisan::call("queue:retry", ["id" => [$failedJobId]]);

        if ($exitCode === 0) {
            return response()->json(["message" => "Job retry command executed successfully for ID: " . $failedJobId]);
        } else {
            // Check if the job ID was invalid or already retried/deleted
            $jobExists = DB::table("failed_jobs")->where("id", $failedJobId)->exists();
            if (!$jobExists) {
                 return response()->json(["error" => "Failed job with ID " . $failedJobId . " not found."], 404);
            }
            return response()->json(["error" => "Failed to execute job retry command for ID: " . $failedJobId], 500);
        }
    }

    /**
     * Delete a specific failed job.
     */
    public function deleteJob(Request $request, string $failedJobId)
    {
         if (!Schema::hasTable("failed_jobs")) {
             return response()->json(["error" => "Failed jobs table not found."], 500);
        }

        // Use Artisan command to delete the job
        $exitCode = Artisan::call("queue:forget", ["id" => $failedJobId]);

        if ($exitCode === 0) {
            return response()->json(["message" => "Job delete command executed successfully for ID: " . $failedJobId]);
        } else {
             // Check if the job ID was invalid or already deleted
            // Note: queue:forget returns 0 even if ID doesn't exist, so this check is less reliable here
            return response()->json(["error" => "Failed to execute job delete command for ID: " . $failedJobId . " (It might have already been deleted)"], 500);
        }
    }
}

