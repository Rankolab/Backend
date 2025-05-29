<?php

namespace App\Jobs;

use App\Models\License;
use App\Models\Notification;
use App\Mail\LicenseRenewalReminder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class SendLicenseRenewalReminders extends Job
{
    public function handle(): void
    {
        $expiringSoon = License::with('user')
            ->where('expires_at', '<=', now()->addDays(7))
            ->where('expires_at', '>=', now())
            ->get();

        foreach ($expiringSoon as $license) {
            $message = "Your Rankolab license expires in " . Carbon::parse($license->expires_at)->diffForHumans() . ".";

            Notification::create([
                'user_id' => $license->user_id,
                'type' => 'license',
                'message' => $message,
                'is_read' => false,
            ]);

            if ($license->user && $license->user->email) {
                Mail::to($license->user->email)->send(new LicenseRenewalReminder($message));
            }
        }
    }
}
