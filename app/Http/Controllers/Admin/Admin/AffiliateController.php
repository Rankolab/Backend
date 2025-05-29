<?php

namespace App\Http\Controllers\Admin\Admin;

use App/Http/Controllers/Admin/Admin/AffiliateController.php;
use Illuminate\Http\Request;
use App\Models\Affiliate;
use App\Models\Referral;
use App\Models\User;

class AffiliateController extends Controller
{
    public function index()
    {
        $affiliates = Affiliate::with('user')->latest()->paginate(20);
        return view('admin.affiliates.index', compact('affiliates'));
    }

    public function show(Affiliate $affiliate)
    {
        $referrals = Referral::with('referredUser')->where('affiliate_id', $affiliate->id)->latest()->paginate(15);
        return view('admin.affiliates.show', compact('affiliate', 'referrals'));
    }

    public function toggle(Affiliate $affiliate)
    {
        $affiliate->is_active = !$affiliate->is_active;
        $affiliate->save();

        return back()->with('success', 'Affiliate status updated.');
    }
}
