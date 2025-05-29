<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{
    public function index()
    {
        $affiliate = auth()->user()->affiliate;
        return view('user.affiliate.index', compact('affiliate'));
    }

    public function requestPayout()
    {
        $affiliate = auth()->user()->affiliate;
        if ($affiliate && !$affiliate->payout_requested && $affiliate->total_earnings >= 50) {
            $affiliate->payout_requested = true;
            $affiliate->save();
            return back()->with('success', 'Payout request submitted.');
        }
        return back()->with('error', 'Payout not eligible or already requested.');
    }
}
