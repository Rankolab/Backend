<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\User; // Import User model
use Illuminate\Support\Str; // Import Str for generating code
use Illuminate\Validation\Rule; // Import Rule for validation

class AffiliateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $affiliates = Affiliate::with("user")->latest()->paginate(20);
        // View already created
        return view("admin.affiliates.index", compact("affiliates"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch users who are not already affiliates
        $users = User::whereDoesntHave("affiliate")->orderBy("name")->get();
        return view("admin.affiliates.create", compact("users"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "user_id" => [
                "required",
                "exists:users,id",
                Rule::unique("affiliates", "user_id"), // Ensure user is not already an affiliate
            ],
            "commission_rate" => "required|numeric|min:0|max:100",
            "status" => ["required", Rule::in(["active", "inactive"])],
        ]);

        // Generate a unique affiliate code
        $code = strtoupper(Str::random(10));
        while (Affiliate::where("code", $code)->exists()) {
            $code = strtoupper(Str::random(10));
        }

        Affiliate::create([
            "user_id" => $request->user_id,
            "code" => $code,
            "commission_rate" => $request->commission_rate,
            "status" => $request->status,
        ]);

        return redirect()->route("admin.affiliates.index")->with("success", "Affiliate created successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Affiliate $affiliate)
    {
        $affiliate->load(["user", "commissions", "referrals"]);
        // View already created
        return view("admin.affiliates.show", compact("affiliate"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Affiliate $affiliate)
    {
        // View already created
        return view("admin.affiliates.edit", compact("affiliate"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Affiliate $affiliate)
    {
        $request->validate([
            // user_id is typically not changed
            "commission_rate" => "required|numeric|min:0|max:100",
            "status" => ["required", Rule::in(["active", "inactive"])],
        ]);

        $affiliate->update([
            "commission_rate" => $request->commission_rate,
            "status" => $request->status,
        ]);

        return redirect()->route("admin.affiliates.index")->with("success", "Affiliate updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Affiliate $affiliate)
    {
        // Consider implications: What happens to commissions/referrals?
        // For now, just delete the affiliate record.
        // Add proper constraints or cleanup logic if needed.
        $affiliate->delete();

        return redirect()->route("admin.affiliates.index")->with("success", "Affiliate deleted successfully.");
    }
}

