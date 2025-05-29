<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $referrals = Referral::with(["affiliate.user", "referredUser"])->latest()->paginate(20);
        // TODO: Create the actual view
        return view("admin.referrals.index", compact("referrals"));
    }

    /**
     * Display the specified resource.
     */
    public function show(Referral $referral)
    {
        $referral->load(["affiliate.user", "referredUser"]);
        // TODO: Create the actual view
        return view("admin.referrals.show", compact("referral"));
    }
}

