<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminStripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();
        
        // Log the webhook event
        Log::info('Admin Stripe webhook received', ['payload' => $payload]);
        
        // Process different event types
        if (isset($payload['type'])) {
            switch ($payload['type']) {
                case 'payment_intent.succeeded':
                    $this->handlePaymentIntentSucceeded($payload);
                    break;
                case 'invoice.payment_succeeded':
                    $this->handleInvoicePaymentSucceeded($payload);
                    break;
                case 'customer.subscription.created':
                    $this->handleSubscriptionCreated($payload);
                    break;
                case 'customer.subscription.updated':
                    $this->handleSubscriptionUpdated($payload);
                    break;
                case 'customer.subscription.deleted':
                    $this->handleSubscriptionCancelled($payload);
                    break;
            }
        }
        
        return response()->json(['status' => 'success']);
    }
    
    private function handlePaymentIntentSucceeded($payload)
    {
        // Handle successful payment intent
        Log::info('Payment intent succeeded', ['payload' => $payload]);
    }
    
    private function handleInvoicePaymentSucceeded($payload)
    {
        // Handle successful invoice payment
        Log::info('Invoice payment succeeded', ['payload' => $payload]);
    }
    
    private function handleSubscriptionCreated($payload)
    {
        // Handle subscription creation
        Log::info('Subscription created', ['payload' => $payload]);
    }
    
    private function handleSubscriptionUpdated($payload)
    {
        // Handle subscription update
        Log::info('Subscription updated', ['payload' => $payload]);
    }
    
    private function handleSubscriptionCancelled($payload)
    {
        // Handle subscription cancellation
        Log::info('Subscription cancelled', ['payload' => $payload]);
    }
}
