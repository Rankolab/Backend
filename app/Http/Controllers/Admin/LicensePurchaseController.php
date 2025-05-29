<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\License;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;

class LicensePurchaseController extends Controller
{
    public function showPlans()
    {
        return view('licenses.plans');
    }

    public function createCheckout(Request $request)
    {
        $user = Auth::user();

        Stripe::setApiKey(config('services.stripe.secret'));

        $checkout = CheckoutSession::create([
            'payment_method_types' => ['card'],
            'customer_email' => $user->email,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => 9900,
                    'product_data' => [
                        'name' => 'Rankolab Pro License',
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('license.success'),
            'cancel_url' => route('license.cancel'),
        ]);

        return redirect($checkout->url);
    }

    public function success()
    {
        $user = Auth::user();

        $key = strtoupper(Str::random(18));
        License::create([
            'user_id' => $user->id,
            'license_key' => $key,
            'status' => 'active',
            'type' => 'pro',
            'expiry_date' => now()->addYear(),
        ]);

        return view('licenses.success', compact('key'));
    }

    public function cancel()
    {
        return view('licenses.cancel');
    }
}
