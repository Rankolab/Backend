<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAffiliate;
use App\Models\Commission;
use App\Models\Payout;

class UserAffiliateController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $affiliate = UserAffiliate::where('user_id', $user->id)->first();
        
        if (!$affiliate) {
            // Create affiliate account if it doesn't exist
            $affiliate = UserAffiliate::create([
                'user_id' => $user->id,
                'referral_code' => $this->generateReferralCode($user),
                'status' => 'active'
            ]);
        }
        
        $commissions = Commission::where('affiliate_id', $affiliate->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $pendingCommissions = Commission::where('affiliate_id', $affiliate->id)
            ->where('status', 'pending')
            ->sum('amount');
            
        $approvedCommissions = Commission::where('affiliate_id', $affiliate->id)
            ->where('status', 'approved')
            ->sum('amount');
            
        $payouts = Payout::where('affiliate_id', $affiliate->id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);
            
        $referrals = User::where('referred_by', $affiliate->referral_code)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('user.affiliate.index', compact(
            'affiliate', 
            'commissions', 
            'pendingCommissions', 
            'approvedCommissions', 
            'payouts', 
            'referrals'
        ));
    }
    
    private function generateReferralCode($user)
    {
        $baseCode = strtolower(substr($user->name, 0, 3) . substr(md5($user->email), 0, 5));
        return $baseCode;
    }
    
    public function updatePaymentDetails(Request $request)
    {
        $request->validate([
            'payment_email' => 'required|email',
            'payment_method' => 'required|in:paypal,bank_transfer,stripe',
            'bank_details' => 'required_if:payment_method,bank_transfer'
        ]);
        
        $user = auth()->user();
        $affiliate = UserAffiliate::where('user_id', $user->id)->firstOrFail();
        
        $affiliate->update([
            'payment_email' => $request->payment_email,
            'payment_method' => $request->payment_method,
            'bank_details' => $request->bank_details
        ]);
        
        return redirect()->route('user.affiliate')->with('success', 'Payment details updated successfully');
    }
    
    public function requestPayout(Request $request)
    {
        $user = auth()->user();
        $affiliate = UserAffiliate::where('user_id', $user->id)->firstOrFail();
        
        $availableBalance = Commission::where('affiliate_id', $affiliate->id)
            ->where('status', 'approved')
            ->whereNull('payout_id')
            ->sum('amount');
            
        if ($availableBalance < 50) {
            return redirect()->route('user.affiliate')->with('error', 'Minimum payout amount is $50');
        }
        
        $payout = Payout::create([
            'affiliate_id' => $affiliate->id,
            'amount' => $availableBalance,
            'status' => 'pending',
            'payment_method' => $affiliate->payment_method,
            'payment_email' => $affiliate->payment_email,
            'bank_details' => $affiliate->bank_details
        ]);
        
        // Update commissions to link to this payout
        Commission::where('affiliate_id', $affiliate->id)
            ->where('status', 'approved')
            ->whereNull('payout_id')
            ->update(['payout_id' => $payout->id]);
            
        return redirect()->route('user.affiliate')->with('success', 'Payout request submitted successfully');
    }
}
