<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and has the 'admin' role
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            // If not admin, return unauthorized response
            return response()->json(['error' => 'Unauthorized. Admin access required.'], 403);
        }

        // If user is admin, proceed with the request
        return $next($request);
    }
}

