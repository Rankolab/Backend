<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\License;
use App\Models\LicensePlan;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LicensePurchaseController extends Controller
{
    public function showPlans()
    {
        $plans = LicensePlan::where('is_active', true)->get();
        return view('user.licenses.plans', compact('plans'));
    }
    
    public function createCheckout(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:license_plans,id'
        ]);
        
        $plan = LicensePlan::findOrFail($request->plan_id);
        $user = Auth::user();
        
        // Create a checkout session
        $checkout_id = 'cs_' . Str::random(24);
        
        // Store checkout information in session
        session([
            'checkout' => [
                'id' => $checkout_id,
                'plan_id' => $plan->id,
                'amount' => $plan->price,
                'user_id' => $user->id
            ]
        ]);
        
        // In a real application, this would redirect to a payment gateway
        // For demo purposes, we'll redirect to success directly
        return redirect()->route('license.success');
    }
    
    public function success()
    {
        // Get checkout information from session
        $checkout = session('checkout');
        
        if (!$checkout) {
            return redirect()->route('licenses.plans')->with('error', 'Invalid checkout session');
        }
        
        $plan = LicensePlan::findOrFail($checkout['plan_id']);
        $user = Auth::user();
        
        // Create license
        $license = License::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'key' => 'LIC-' . Str::upper(Str::random(16)),
            'status' => 'active',
            'expires_at' => now()->addDays($plan->duration_days),
        ]);
        
        // Create purchase record
        Purchase::create([
            'user_id' => $user->id,
            'license_id' => $license->id,
            'amount' => $plan->price,
            'payment_method' => 'credit_card',
            'status' => 'completed',
            'transaction_id' => 'tr_' . Str::random(24)
        ]);
        
        // Clear checkout session
        session()->forget('checkout');
        
        return view('user.licenses.success', compact('license', 'plan'));
    }
    
    public function cancel()
    {
        // Clear checkout session
        session()->forget('checkout');
        
        return redirect()->route('licenses.plans')->with('info', 'Purchase cancelled');
    }
}
