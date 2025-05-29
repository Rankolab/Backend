<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Affiliate;
use App\Models\Commission;
use App\Models\Payout;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AffiliatePayoutProcessed;

class ProcessAffiliatePayouts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'affiliates:process-payouts {--force : Force processing regardless of minimum threshold}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process pending affiliate payouts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting affiliate payout processing...');
        
        $force = $this->option('force');
        $minimumThreshold = config('affiliates.minimum_payout_threshold', 50);
        
        // Get all affiliates with pending commissions
        $affiliates = Affiliate::has('pendingCommissions')->get();
        
        $this->info("Found {$affiliates->count()} affiliates with pending commissions.");
        
        $processedCount = 0;
        $skippedCount = 0;
        $errorCount = 0;
        
        foreach ($affiliates as $affiliate) {
            try {
                // Calculate total pending amount
                $pendingAmount = $affiliate->pendingCommissions()->sum('amount');
                
                // Skip if below threshold and not forced
                if ($pendingAmount < $minimumThreshold && !$force) {
                    $this->line("Skipping affiliate #{$affiliate->id} ({$affiliate->user->email}): Below threshold ($pendingAmount < $minimumThreshold)");
                    $skippedCount++;
                    continue;
                }
                
                // Create payout record
                $payout = new Payout();
                $payout->affiliate_id = $affiliate->id;
                $payout->amount = $pendingAmount;
                $payout->status = 'pending';
                $payout->payment_method = $affiliate->preferred_payment_method;
                $payout->payment_details = $affiliate->payment_details;
                $payout->save();
                
                // Update commissions to link to this payout
                $affiliate->pendingCommissions()->update([
                    'payout_id' => $payout->id,
                    'status' => 'processing'
                ]);
                
                // Log the payout
                Log::info("Affiliate payout created", [
                    'affiliate_id' => $affiliate->id,
                    'payout_id' => $payout->id,
                    'amount' => $pendingAmount
                ]);
                
                // Send notification email
                Mail::to($affiliate->user->email)->send(new AffiliatePayoutProcessed($payout));
                
                $this->info("Processed payout for affiliate #{$affiliate->id}: ${$pendingAmount}");
                $processedCount++;
                
            } catch (\Exception $e) {
                $this->error("Error processing affiliate #{$affiliate->id}: {$e->getMessage()}");
                Log::error("Affiliate payout error", [
                    'affiliate_id' => $affiliate->id,
                    'error' => $e->getMessage()
                ]);
                $errorCount++;
            }
        }
        
        $this->info("Affiliate payout processing completed.");
        $this->info("Processed: $processedCount, Skipped: $skippedCount, Errors: $errorCount");
        
        return 0;
    }
}
