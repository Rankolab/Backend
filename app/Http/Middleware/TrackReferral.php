<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Affiliate;
use App\Models\Referral;

class TrackReferral
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('ref')) {
            $code = $request->query('ref');
            $affiliate = Affiliate::where('ref_code', $code)->first();

            if ($affiliate) {
                session(['referral_code' => $code]);

                // Prevent duplicate log on refresh
                if (!session()->has('referral_logged')) {
                    Referral::create([
                        'affiliate_id' => $affiliate->id,
                        'ip_address' => $request->ip(),
                        'converted' => false,
                    ]);

                    session(['referral_logged' => true]);
                }
            }
        }

        return $next($request);
    }
}
