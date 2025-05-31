<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Website;
use App\Models\Content; // Optional, if linking posts to content
use App\Models\SocialMediaPost;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
// use App\Jobs\PostToSocialMediaJob; // Example: Uncomment when Job is created

class SocialMediaController extends Controller
{
    /**
     * Store (schedule) a new social media post for a website.
     *
     * Creates a record in the database. Actual posting to social media
     * platforms should be handled by a background job.
     */
    public function store(Request $request, Website $website) // Use route model binding
    {
        // Authorization check
        if (Auth::user()->id !== $website->user_id && !Auth::user()->hasRole(["admin", "super_admin"])) {
            Log::warning("Unauthorized attempt to store social media post for website ID: {$website->id} by user ID: " . Auth::user()->id);
            return response()->json(["message" => "Unauthorized"], 403);
        }

        $validator = Validator::make($request->all(), [
            // Assuming content_id is passed in the request body if linking to specific content
            "content_id" => "nullable|exists:content,id,website_id," . $website->id, // Ensure content belongs to the website
            "platform" => "required|string|in:facebook,twitter,linkedin,instagram,pinterest", // Example platforms
            "post_content" => "required|string|max:1000", // Max length depends on platform
            "status" => "required|string|in:draft,scheduled", // Removed 'posted', 'failed' as initial statuses
            "scheduled_at" => "nullable|required_if:status,scheduled|date_format:Y-m-d H:i:s|after_or_equal:now", // Validate datetime format and ensure it's in the future if scheduled
        ]);

        if ($validator->fails()) {
            // Check for specific platform error as per API doc example
            if ($validator->errors()->has("platform")) {
                 return response()->json(["error" => "Invalid platform specified"], 400);
            }
            return response()->json(["error" => "Invalid social media post parameters", "details" => $validator->errors()], 400);
        }

        // Create the social media post record
        $socialMediaPost = SocialMediaPost::create([
            "website_id" => $website->id,
            "user_id" => Auth::id(), // Store the user who created it
            "content_id" => $request->input("content_id"),
            "platform" => $request->input("platform"),
            "post_content" => $request->input("post_content"),
            "post_url" => null, // Will be set by the posting job
            "status" => $request->input("status"), // 'draft' or 'scheduled'
            "scheduled_at" => $request->input("scheduled_at") ? Carbon::parse($request->input("scheduled_at")) : null,
            "posted_at" => null,
            "error_message" => null, // Field to store potential errors from posting job
        ]);

        Log::info("Social media post record created with ID: {$socialMediaPost->id} for website ID: {$website->id}. Status: {$socialMediaPost->status}");

        // --- Trigger Background Job for Posting (if scheduled) ---
        // if ($socialMediaPost->status === 'scheduled') {
        //     PostToSocialMediaJob::dispatch($socialMediaPost)->delay($socialMediaPost->scheduled_at);
        //     Log::info("Dispatched PostToSocialMediaJob for post ID: {$socialMediaPost->id} scheduled at {$socialMediaPost->scheduled_at}");
        // }
        // --------------------------------------------------------

        // Return response as per API documentation
        $response = [
            "message" => "Social media post saved successfully as {$socialMediaPost->status}.",
            "post_id" => $socialMediaPost->id,
            "website_id" => $socialMediaPost->website_id,
            "platform" => $socialMediaPost->platform,
            "status" => $socialMediaPost->status,
            "scheduled_at" => $socialMediaPost->scheduled_at ? $socialMediaPost->scheduled_at->toIso8601String() : null,
        ];

        // Use 201 Created for new resource creation
        return response()->json($response, 201);
    }

    // TODO: Add methods for GET /social-posts, GET /social-posts/{id}, PUT /social-posts/{id}, DELETE /social-posts/{id}
    // These should perform standard CRUD operations with proper authorization.
}


