<?php

namespace App\Http\Controllers\Admin\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;

class AffiliateAdminController extends Controller
{
    public function index()
    {
        $affiliates = Affiliate::with('user')->latest()->paginate(20);
        return view('admin.affiliates.index', compact('affiliates'));
    }

    public function approve(Affiliate $affiliate)
    {
        $affiliate->payout_requested = false;
        $affiliate->payout_completed = true;
        $affiliate->save();
        return back()->with('success', 'Payout marked as completed.');
    }
}
