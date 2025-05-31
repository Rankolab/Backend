<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Website;
use App\Models\Content;
use App\Models\ContentPlan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
// use App\Jobs\GenerateContentJob; // Example: Uncomment when Job is created
// use App\Jobs\PublishContentJob; // Example: Uncomment when Job is created

class ContentController extends Controller
{
    /**
     * Store a request to generate new content for a website.
     *
     * This endpoint creates a content record with status 'draft'
     * and should trigger a background job for actual AI generation.
     */
    public function store(Request $request, Website $website) // Use route model binding
    {
        // Authorization check
        if (Auth::user()->id !== $website->user_id && !Auth::user()->hasRole(["admin", "super_admin"])) {
            Log::warning("Unauthorized attempt to store content for website ID: {$website->id} by user ID: " . Auth::user()->id);
            return response()->json(["message" => "Unauthorized"], 403);
        }

        $validator = Validator::make($request->all(), [
            "plan_id" => "nullable|exists:content_plans,id,website_id," . $website->id, // Ensure plan belongs to the website
            "title" => "required|string|max:255",
            "word_count" => "required|integer|min:100",
            "keywords" => "nullable|array",
            "content_type" => "nullable|string|in:blog,article,page,landing_page|max:50", // Added more types
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => "Invalid content parameters", "details" => $validator->errors()], 400);
        }

        // Create the initial content record in the database
        $content = Content::create([
            "website_id" => $website->id,
            "user_id" => Auth::id(), // Store the user who requested it
            "plan_id" => $request->input("plan_id"),
            "title" => $request->input("title"),
            "body" => "Content generation pending...", // Indicate generation is pending
            "word_count" => $request->input("word_count"),
            "featured_image_url" => null, // Will be set by generation job
            "images" => [], // Will be set by generation job
            "internal_links" => [], // Will be set by generation job
            "external_links" => [], // Will be set by generation job
            "affiliate_links" => [], // Will be set by generation job
            "keywords" => $request->input("keywords"),
            "content_type" => $request->input("content_type", "blog"), // Default to blog
            "status" => "draft", // Start as draft, generation job will update
            "published_at" => null,
        ]);

        Log::info("Content record created with ID: {$content->id} for website ID: {$website->id}. Generation pending.");

        // --- Trigger Background Job for AI Generation ---
        // In a real application, dispatch a job here:
        // GenerateContentJob::dispatch($content);
        // Log::info("Dispatched GenerateContentJob for content ID: {$content->id}");
        // -----------------------------------------------

        // Return response indicating creation and pending generation
        $response = [
            "message" => "Content creation request received. Generation is pending.",
            "content_id" => $content->id,
            "website_id" => $content->website_id,
            "title" => $content->title,
            "status" => $content->status,
        ];

        return response()->json($response, 202); // 202 Accepted: Request received, processing pending
    }

    /**
     * Publish a draft content piece.
     *
     * Updates the content status to 'published' and should trigger
     * background jobs for post-publishing tasks (e.g., GSC submission).
     */
    public function publish(Request $request, Website $website) // Use route model binding
    {
        // Authorization check
        if (Auth::user()->id !== $website->user_id && !Auth::user()->hasRole(["admin", "super_admin"])) {
            Log::warning("Unauthorized attempt to publish content for website ID: {$website->id} by user ID: " . Auth::user()->id);
            return response()->json(["message" => "Unauthorized"], 403);
        }

        $validator = Validator::make($request->all(), [
            // Assuming content_id is passed in the request body
            "content_id" => "required|exists:content,id,website_id," . $website->id, // Ensure content belongs to the website
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => "Invalid content ID", "details" => $validator->errors()], 400);
        }

        $content = Content::find($request->input("content_id"));

        // Double check ownership just in case exists rule fails somehow
        if (!$content || $content->website_id !== $website->id) {
            return response()->json(["error" => "Content not found or does not belong to this website"], 404);
        }

        // Ensure content is ready for publishing (e.g., not already published, generation complete)
        // Add more checks if generation status is tracked separately
        if ($content->status !== "draft") {
            return response()->json(["error" => "Content is not in a publishable state (current status: {$content->status})"], 400);
        }

        // Update status and published_at timestamp
        $content->status = "published";
        $content->published_at = Carbon::now();
        $content->save();

        Log::info("Content ID: {$content->id} for website ID: {$website->id} published successfully.");

        // --- Trigger Background Job for Post-Publishing Tasks ---
        // e.g., Submit to Google Search Console, post to social media
        // PublishContentJob::dispatch($content);
        // Log::info("Dispatched PublishContentJob for content ID: {$content->id}");
        // -------------------------------------------------------

        // Return response as per API documentation
        $response = [
            "message" => "Content published successfully.",
            "content_id" => $content->id,
            "status" => $content->status,
            "published_at" => $content->published_at->toIso8601String(),
        ];

        return response()->json($response, 200);
    }

    // TODO: Add methods for GET /content, GET /content/{id}, PUT /content/{id}, DELETE /content/{id}
    // These should perform standard CRUD operations with proper authorization.
}


