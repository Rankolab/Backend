<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated. Please log in.'
                ], 401);
            }
            
            return redirect()->route('login')
                ->with('error', 'Please log in to access this area.');
        }
        
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin') {
            // Log unauthorized access attempt
            Log::warning('Unauthorized admin access attempt', [
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
                'url' => $request->fullUrl()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Access denied. Admin privileges required.'
                ], 403);
            }
            
            return redirect()->route('login')
                ->with('error', 'Access denied. Admin privileges required.');
        }
        
        return $next($request);
    }
}
