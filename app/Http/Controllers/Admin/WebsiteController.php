<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\License;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the websites.
     */
    public function index(Request $request)
    {
        // Add permission check if needed: if (!auth()->user()->hasPermission("view_websites")) abort(403);

        $query = Website::with(["user", "license"]);

        // Add search/filtering if needed
        if ($request->filled("search")) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where("domain", "like", "%{$searchTerm}%")
                  ->orWhereHas("user", function ($userQuery) use ($searchTerm) {
                      $userQuery->where("name", "like", "%{$searchTerm}%")
                                ->orWhere("email", "like", "%{$searchTerm}%");
                  });
            });
        }

        $websites = $query->latest()->paginate(15)->withQueryString();
        return view("admin.websites.index", compact("websites"));
    }

    /**
     * Show the form for creating a new website.
     */
    public function create()
    {
        // if (!auth()->user()->hasPermission("create_websites")) abort(403);

        $users = User::orderBy("name")->get(["id", "name"]); // Get only necessary fields
        // Consider filtering licenses (e.g., only active ones?)
        $licenses = License::where("status", "active")->orderBy("key")->get(["id", "key"]); // Example: only active licenses

        return view("admin.websites.create", compact("users", "licenses"));
    }

    /**
     * Store a newly created website in storage.
     */
    public function store(Request $request)
    {
        // if (!auth()->user()->hasPermission("create_websites")) abort(403);

        $validated = $request->validate([
            "name" => ["required", "string", "max:255"], // Added name validation
            "domain" => ["required", "string", "max:255", "url", "unique:websites,domain"],
            "niche" => ["nullable", "string", "max:255"],
            "website_type" => ["nullable", "string", "max:100"], // Add validation if needed
            "user_id" => ["required", "integer", "exists:users,id"],
            "license_id" => ["nullable", "integer", "exists:licenses,id"],
            // Add status validation if you have a status field
            // "status" => ["required", Rule::in(["active", "inactive", "pending"])],
        ]);

        // Add default status if applicable
        // $validated["status"] = "pending";

        // Set the url field based on the domain
        $validated["url"] = $validated["domain"];

        Website::create($validated);

        return redirect()->route("admin.websites.index")->with("success", "Website created successfully.");
    }

    /**
     * Display the specified website.
     */
    public function show(Website $website)
    {
        // if (!auth()->user()->hasPermission("view_websites")) abort(403);
        $website->load(["user", "license", "metrics"]); // Load metrics if available
        return view("admin.websites.show", compact("website"));
    }

    /**
     * Show the form for editing the specified website.
     */
    public function edit(Website $website)
    {
        // if (!auth()->user()->hasPermission("edit_websites")) abort(403);

        $users = User::orderBy("name")->get(["id", "name"]);
        $licenses = License::orderBy("key")->get(["id", "key"]); // Get all licenses for editing flexibility
        return view("admin.websites.edit", compact("website", "users", "licenses"));
    }

    /**
     * Update the specified website in storage.
     */
    public function update(Request $request, Website $website)
    {
        // if (!auth()->user()->hasPermission("edit_websites")) abort(403);

        $validated = $request->validate([
            "name" => ["required", "string", "max:255"], // Added name validation
            // Ensure domain validation ignores the current website ID
            "domain" => ["required", "string", "max:255", "url", Rule::unique("websites", "domain")->ignore($website->website_id, "website_id")],
            "niche" => ["nullable", "string", "max:255"],
            "website_type" => ["nullable", "string", "max:100"],
            "user_id" => ["required", "integer", "exists:users,id"],
            "license_id" => ["nullable", "integer", "exists:licenses,id"],
            // Add status validation if you have a status field
            // "status" => ["required", Rule::in(["active", "inactive", "pending"])],
        ]);

        $website->update($validated);

        return redirect()->route("admin.websites.index")->with("success", "Website updated successfully.");
    }

    /**
     * Remove the specified website from storage.
     */
    public function destroy(Website $website)
    {
        // if (!auth()->user()->hasPermission("delete_websites")) abort(403);

        // Add any pre-deletion logic if needed (e.g., detach related data)
        $website->delete();
        return redirect()->route("admin.websites.index")->with("success", "Website deleted successfully.");
    }
}

