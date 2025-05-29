<?php

namespace App\Jobs;

use App\Models\Website;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\SeoAlertMail;

class CheckSeoAlerts extends Job
{
    public function handle(): void
    {
        $websites = Website::with(['metrics', 'user'])->get();

        foreach ($websites as $site) {
            $alerts = [];

            if ($site->metrics?->domain_authority < 20) {
                $alerts[] = 'Low Domain Authority on ' . $site->domain;
            }

            if ($site->metrics?->page_speed < 50) {
                $alerts[] = 'Poor PageSpeed on ' . $site->domain;
            }

            foreach ($alerts as $alert) {
                
                // Send email alert
                if ($site->user && $site->user->email) {
                    Mail::to($site->user->email)->send(new SeoAlertMail($alert));
                }
    
                Notification::create([
                    'user_id' => $site->user_id,
                    'type' => 'seo',
                    'message' => $alert,
                    'is_read' => false,
                ]);
            }
        }
    }
}
