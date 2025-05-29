<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\User; // Import User model
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Import Str for generating license key
use Carbon\Carbon; // Import Carbon for date handling

class LicenseController extends Controller
{
    public function index()
    {
        $licenses = License::with("user")->latest()->paginate(15);
        return view("admin.licenses.index", compact("licenses"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::orderBy("name")->get(); // Fetch users for dropdown
        return view("admin.licenses.create", compact("users"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "user_id" => "nullable|exists:users,id",
            "type" => "required|string|in:standard,premium,lifetime,trial", // Adjust types as needed
            "status" => "required|string|in:active,inactive,pending,expired",
            "expires_at" => "nullable|date|after_or_equal:today",
            "allowed_domains" => "required|integer|min:1",
        ]);

        // Generate a unique license key
        $licenseKey = "LIC-" . strtoupper(Str::random(16));
        while (License::where("key", $licenseKey)->exists()) {
            $licenseKey = "LIC-" . strtoupper(Str::random(16));
        }

        License::create([
            "key" => $licenseKey,
            "user_id" => $request->user_id,
            "type" => $request->type,
            "status" => $request->status,
            "expires_at" => $request->expires_at ? Carbon::parse($request->expires_at) : null,
            "allowed_domains" => $request->allowed_domains,
        ]);

        return redirect()->route("admin.licenses.index")->with("success", "License created successfully.");
    }

    public function show(License $license)
    {
        $license->load("user"); // Eager load user
        return view("admin.licenses.show", compact("license"));
    }

    public function edit(License $license)
    {
        $users = User::orderBy("name")->get(); // Fetch users for dropdown
        return view("admin.licenses.edit", compact("license", "users"));
    }

    public function update(Request $request, License $license)
    {
        $validatedData = $request->validate([
            "user_id" => "nullable|exists:users,id",
            "type" => "required|string|in:standard,premium,lifetime,trial",
            "status" => "required|string|in:active,inactive,pending,expired",
            "expires_at" => "nullable|date",
            "allowed_domains" => "required|integer|min:1",
        ]);

        // Ensure expires_at is null if empty string is passed
        $validatedData["expires_at"] = $request->filled("expires_at") ? Carbon::parse($request->expires_at) : null;

        $license->update($validatedData);

        return redirect()->route("admin.licenses.index")->with("success", "License updated successfully.");
    }

    public function destroy(License $license)
    {
        $license->delete();
        return redirect()->route("admin.licenses.index")->with("success", "License deleted successfully.");
    }
}

