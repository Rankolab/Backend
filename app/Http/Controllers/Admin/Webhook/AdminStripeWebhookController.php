<?php

namespace App\Http\Controllers\Admin\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\License;
use App\Models\Purchase;
use Stripe\Stripe;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Response;

class AdminStripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\UnexpectedValueException $e) {
            return new Response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return new Response('Invalid signature', 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                // Example logic: You should replace this with your actual handling
                $user = User::where('email', $session->customer_email)->first();
                if ($user) {
                    // Create purchase record
                    Purchase::create([
                        'user_id' => $user->id,
                        'stripe_session_id' => $session->id,
                        'amount' => $session->amount_total / 100,
                        'status' => 'completed',
                    ]);
                }
                break;

            // Handle other event types...

            default:
                Log::info('Received unknown event type: ' . $event->type);
        }

        return new Response('Webhook handled', 200);
    }
}
