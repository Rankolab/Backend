<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commissions = Commission::with(["affiliate.user", "user", "payment"])->latest()->paginate(20);
        // TODO: Create the actual view
        return view("admin.commissions.index", compact("commissions"));
    }

    /**
     * Display the specified resource.
     */
    public function show(Commission $commission)
    {
        $commission->load(["affiliate.user", "user", "payment"]);
        // TODO: Create the actual view
        return view("admin.commissions.show", compact("commission"));
    }

    /**
     * Update the specified resource in storage.
     * Typically used for approving/rejecting/marking as paid.
     */
    public function update(Request $request, Commission $commission)
    {
        // TODO: Add validation and update logic (e.g., changing status)
        $validated = $request->validate([
            "status" => ["required", "in:pending,approved,paid,rejected"],
        ]);

        $commission->update(["status" => $validated["status"]]);

        if ($validated["status"] === "paid") {
            $commission->update(["paid_at" => now()]);
            // Potentially trigger payout logic here or queue it
        }

        return redirect()->route("admin.commissions.index")->with("success", "Commission status updated.");
    }
}

