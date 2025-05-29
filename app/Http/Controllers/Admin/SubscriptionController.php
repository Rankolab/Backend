<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscriptions = Subscription::with(["user", "plan"])->latest()->paginate(15);
        return view("admin.subscriptions.index", compact("subscriptions"));
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        $subscription->load(["user", "plan", "payments"]);
        return view("admin.subscriptions.show", compact("subscription"));
    }

    /**
     * Remove the specified resource from storage.
     * This typically means canceling the subscription in Stripe.
     */
    public function destroy(Subscription $subscription)
    {
        // TODO: Implement Stripe subscription cancellation logic
        // Example (requires Stripe SDK setup and API keys):
        /*
        try {
            $stripe = new \Stripe\StripeClient(config("services.stripe.secret"));
            $stripe->subscriptions->cancel($subscription->stripe_id, []);

            // Update local status immediately or wait for webhook
            $subscription->update(["stripe_status" => "canceled", "ends_at" => now()]); // Or use Stripe's cancel_at_period_end

            return redirect()->route("admin.subscriptions.index")
                             ->with("success", "Subscription canceled successfully.");
        } catch (\Exception $e) {
            return redirect()->route("admin.subscriptions.index")
                             ->with("error", "Failed to cancel subscription: " . $e->getMessage());
        }
        */

        // Placeholder until Stripe logic is added
        return redirect()->route("admin.subscriptions.index")
                         ->with("info", "Subscription cancellation logic not yet implemented.");
    }
}
