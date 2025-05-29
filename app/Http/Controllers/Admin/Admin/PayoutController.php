<?php

namespace App\Http\Controllers\Admin\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payout;

class PayoutController extends Controller
{
    public function index()
    {
        $payouts = Payout::with('affiliate.user')->latest()->paginate(20);
        return view('admin.payouts.index', compact('payouts'));
    }

    public function updateStatus(Request $request, Payout $payout)
    {
        $request->validate(['status' => 'required|in:paid,rejected']);

        $payout->status = $request->status;
        $payout->save();

        return back()->with('success', 'Payout status updated.');
    }
}
