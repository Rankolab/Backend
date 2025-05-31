<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    // Define plans internally for validation and reference
    private $plans = [
        [
            'id' => 'trial',
            'name' => '7-Day Trial',
            'price' => 1.00,
            'currency' => 'USD',
            'interval' => 'day',
            'interval_count' => 7,
            'description' => 'Access all features for 7 days.'
        ],
        [
            'id' => 'monthly',
            'name' => 'Monthly Subscription',
            'price' => 29.00,
            'currency' => 'USD',
            'interval' => 'month',
            'interval_count' => 1,
            'description' => 'Full access billed monthly.'
        ],
        [
            'id' => 'annual',
            'name' => 'Annual Subscription',
            'price' => 300.00,
            'currency' => 'USD',
            'interval' => 'year',
            'interval_count' => 1,
            'description' => 'Full access billed annually (Save $48).'
        ],
    ];

    /**
     * List available subscription plans.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listPlans()
    {
        return response()->json($this->plans);
    }

    /**
     * Get the current user's subscription status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatus(Request $request)
    {
        $user = $request->user();

        // Placeholder: In a real application, retrieve subscription details
        // from the database or a payment provider (e.g., Stripe).
        // For now, assume the user has no active subscription.
        $subscription = null; // Example: $user->subscription();

        if ($subscription) {
            // ... (existing logic)
        } else {
            return response()->json([
                'status' => 'inactive',
                'message' => 'No active subscription found.'
            ], 200); // Changed to 200 as it's a valid state, not an error
        }
    }

    /**
     * Create a new subscription for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $user = $request->user();

        $validPlanIds = array_column($this->plans, 'id');

        $validator = Validator::make($request->all(), [
            'plan_id' => ['required', 'string', Rule::in($validPlanIds)],
            // Add payment_method_id validation if integrating with Stripe/etc.
            // 'payment_method_id' => ['required_unless:plan_id,trial', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $planId = $request->input('plan_id');

        // Placeholder: Check if user already has an active subscription
        // if ($user->hasActiveSubscription()) {
        //     return response()->json(['message' => 'User already has an active subscription.'], 400);
        // }

        // Placeholder: Interact with payment gateway (e.g., Stripe)
        // to create the subscription and handle payment.
        // For now, just return a success message.

        // Simulate subscription creation
        $newSubscription = [
            'plan_id' => $planId,
            'status' => ($planId === 'trial') ? 'trialing' : 'active',
            'start_date' => now()->toIso8601String(),
            'current_period_end' => ($planId === 'trial') ? now()->addDays(7)->toIso8601String() : now()->addMonth()->toIso8601String(), // Simplified end date
        ];

        // Placeholder: Save subscription details to the database
        // $user->subscriptions()->create($newSubscription);

        return response()->json([
            'message' => 'Subscription created successfully.',
            'subscription' => $newSubscription
        ], 201);
    }

    /**
     * Cancel the user's active subscription.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(Request $request)
    {
        $user = $request->user();

        // Placeholder: Find the user's active subscription
        // $subscription = $user->activeSubscription(); // Assuming a method exists
        $subscription = ['status' => 'active']; // Simulate finding an active subscription for placeholder logic

        if (!$subscription) {
            return response()->json(['message' => 'No active subscription to cancel.'], 404);
        }

        // Placeholder: Interact with payment gateway (e.g., Stripe) to cancel.
        // $subscription->cancel(); // Example Stripe cancellation

        // Placeholder: Update subscription status in the database
        // $subscription->status = 'canceled';
        // $subscription->canceled_at = now();
        // $subscription->save();

        return response()->json(['message' => 'Subscription canceled successfully.'], 200);
    }

    // Other subscription methods will be added here...
}

