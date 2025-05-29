<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Affiliate;
use App\Models\AffiliateClick;
use App\Models\AffiliatePayout;
use Illuminate\Support\Facades\Auth;

class UserAffiliateController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $affiliate = Affiliate::where('user_id', $user->id)->first();
        
        if (!$affiliate) {
            $affiliate = Affiliate::create([
                'user_id' => $user->id,
                'code' => $this->generateAffiliateCode($user),
                'commission_rate' => 10, // Default 10%
                'status' => 'active'
            ]);
        }
        
        $clicks = AffiliateClick::where('affiliate_id', $affiliate->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $payouts = AffiliatePayout::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $stats = [
            'total_clicks' => AffiliateClick::where('affiliate_id', $affiliate->id)->count(),
            'total_conversions' => AffiliateClick::where('affiliate_id', $affiliate->id)->where('converted', true)->count(),
            'total_earnings' => AffiliatePayout::where('user_id', $user->id)->sum('amount'),
            'pending_earnings' => AffiliatePayout::where('user_id', $user->id)->where('status', 'pending')->sum('amount')
        ];
        
        return view('user.affiliate.index', compact('affiliate', 'clicks', 'payouts', 'stats'));
    }
    
    public function requestPayout(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:50',
            'payment_method' => 'required|in:paypal,bank_transfer,stripe'
        ]);
        
        $user = Auth::user();
        
        // Check if user has enough pending earnings
        $pendingEarnings = AffiliatePayout::where('user_id', $user->id)
            ->where('status', 'pending')
            ->sum('amount');
            
        if ($pendingEarnings < $request->amount) {
            return redirect()->back()->with('error', 'Insufficient pending earnings for this payout request');
        }
        
        AffiliatePayout::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'notes' => $request->notes
        ]);
        
        return redirect()->back()->with('success', 'Payout request submitted successfully');
    }
    
    private function generateAffiliateCode($user)
    {
        $baseCode = strtolower(substr($user->name, 0, 3) . substr(md5($user->email), 0, 5));
        $code = $baseCode;
        $counter = 1;
        
        // Ensure code is unique
        while (Affiliate::where('code', $code)->exists()) {
            $code = $baseCode . $counter;
            $counter++;
        }
        
        return $code;
    }
}
