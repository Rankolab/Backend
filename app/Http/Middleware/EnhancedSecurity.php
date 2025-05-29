<?php
/**
 * Enhanced security middleware for Rankolab Backend API
 *
 * This middleware implements security best practices for the Rankolab backend API
 *
 * @package Rankolab\Backend
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\SecurityLog;

class EnhancedSecurity
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
        // Check for required security headers
        $this->validateSecurityHeaders($request);
        
        // Rate limiting is handled by Laravel's built-in throttle middleware
        
        // Log sensitive operations
        $this->logSensitiveOperations($request);
        
        // Sanitize inputs
        $this->sanitizeInputs($request);
        
        // Process the request
        $response = $next($request);
        
        // Add security headers to response
        $this->addSecurityHeaders($response);
        
        return $response;
    }
    
    /**
     * Validate security headers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    private function validateSecurityHeaders(Request $request)
    {
        // Check for Content-Security-Policy in production
        if (app()->environment('production') && !$request->header('Content-Security-Policy')) {
            $this->logSecurityEvent(
                'missing_security_header',
                'Content-Security-Policy header missing',
                ['ip' => $request->ip(), 'path' => $request->path()]
            );
        }
    }
    
    /**
     * Log sensitive operations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    private function logSensitiveOperations(Request $request)
    {
        // Define sensitive operations patterns
        $sensitivePatterns = [
            'password' => '/password/i',
            'auth' => '/auth|login|logout/i',
            'payment' => '/payment|stripe|subscription/i',
            'license' => '/license/i',
            'admin' => '/admin/i',
        ];
        
        // Check if the current request matches any sensitive pattern
        foreach ($sensitivePatterns as $type => $pattern) {
            if (preg_match($pattern, $request->path())) {
                $this->logSecurityEvent(
                    'sensitive_operation',
                    "Sensitive operation of type: {$type}",
                    [
                        'ip' => $request->ip(),
                        'path' => $request->path(),
                        'method' => $request->method(),
                        'user_id' => Auth::id() ?? 'unauthenticated',
                    ]
                );
                break;
            }
        }
    }
    
    /**
     * Sanitize request inputs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    private function sanitizeInputs(Request $request)
    {
        // Laravel handles most input sanitization automatically
        // This is just an additional layer for specific cases
        
        // Check for potential SQL injection patterns
        $sqlPatterns = ['/\bUNION\b/i', '/\bSELECT\b.*\bFROM\b/i', '/\bDROP\b/i', '/\bDELETE\b/i'];
        
        foreach ($request->all() as $key => $value) {
            if (is_string($value)) {
                foreach ($sqlPatterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        $this->logSecurityEvent(
                            'potential_sql_injection',
                            'Potential SQL injection attempt detected',
                            [
                                'ip' => $request->ip(),
                                'path' => $request->path(),
                                'parameter' => $key,
                                'value' => substr($value, 0, 100), // Log only first 100 chars
                            ]
                        );
                        
                        // Don't modify the input as Laravel's query builder will handle this safely
                        break;
                    }
                }
            }
        }
    }
    
    /**
     * Add security headers to response.
     *
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    private function addSecurityHeaders($response)
    {
        // Only add headers if this is a HTTP response object
        if (method_exists($response, 'header')) {
            // Content Security Policy
            $response->header('Content-Security-Policy', "default-src 'self'; script-src 'self' https://js.stripe.com; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self' https://api.rankolab.com;");
            
            // Prevent MIME type sniffing
            $response->header('X-Content-Type-Options', 'nosniff');
            
            // Prevent clickjacking
            $response->header('X-Frame-Options', 'SAMEORIGIN');
            
            // Enable XSS protection in browsers
            $response->header('X-XSS-Protection', '1; mode=block');
            
            // Strict Transport Security (only in production)
            if (app()->environment('production')) {
                $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            }
            
            // Referrer Policy
            $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');
            
            // Feature Policy
            $response->header('Feature-Policy', "camera 'none'; microphone 'none'; geolocation 'self'");
        }
    }
    
    /**
     * Log security events.
     *
     * @param  string  $event
     * @param  string  $message
     * @param  array   $context
     * @return void
     */
    private function logSecurityEvent($event, $message, array $context = [])
    {
        // Log to Laravel's logging system
        Log::channel('security')->warning($message, array_merge(['event' => $event], $context));
        
        // Store in database for admin viewing
        try {
            SecurityLog::create([
                'event' => $event,
                'message' => $message,
                'context' => json_encode($context),
                'ip_address' => $context['ip'] ?? request()->ip(),
                'user_id' => Auth::id(),
            ]);
        } catch (\Exception $e) {
            // If database logging fails, ensure we still have a record in the log files
            Log::error('Failed to log security event to database', [
                'error' => $e->getMessage(),
                'event' => $event,
                'message' => $message,
            ]);
        }
    }
}
