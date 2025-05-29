<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = Plan::latest()->paginate(10);
        return view("admin.plans.index", compact("plans"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.plans.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "price" => "required|numeric|min:0",
            "interval" => "required|in:month,year",
            "stripe_id" => "nullable|string|unique:plans,stripe_id",
            "features" => "nullable|array",
            "is_active" => "boolean",
        ]);

        // TODO: Add logic to create the plan/price in Stripe if stripe_id is provided or needs generation

        Plan::create($request->all());

        return redirect()->route("admin.plans.index")
                         ->with("success", "Plan created successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan)
    {
        return view("admin.plans.show", compact("plan"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plan $plan)
    {
        return view("admin.plans.edit", compact("plan"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "price" => "required|numeric|min:0",
            "interval" => "required|in:month,year",
            "stripe_id" => "nullable|string|unique:plans,stripe_id," . $plan->id,
            "features" => "nullable|array",
            "is_active" => "boolean",
        ]);

        // TODO: Add logic to update the plan/price in Stripe if necessary

        $plan->update($request->all());

        return redirect()->route("admin.plans.index")
                         ->with("success", "Plan updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        // TODO: Add logic to potentially deactivate/archive the plan in Stripe
        // Consider preventing deletion if active subscriptions exist
        if ($plan->subscriptions()->count() > 0) {
            return redirect()->route("admin.plans.index")
                             ->with("error", "Cannot delete plan with active subscriptions.");
        }

        $plan->delete();

        return redirect()->route("admin.plans.index")
                         ->with("success", "Plan deleted successfully.");
    }
}
