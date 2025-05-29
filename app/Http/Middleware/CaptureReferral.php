<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

class CaptureReferral
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('ref')) {
            $code = $request->query('ref');
            cookie()->queue(cookie('referral_code', $code, 60 * 24 * 30)); // 30 days
        }

        return $next($request);
    }
}
