<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payout;
use App\Models\User;

class PayoutController extends Controller
{
    public function index()
    {
        $payouts = Payout::with('user')->latest()->paginate(10);
        return view('admin.payouts.index', compact('payouts'));
    }

    public function updateStatus(Request $request, Payout $payout)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,paid'
        ]);

        $payout->status = $request->status;
        
        if ($request->status === 'paid') {
            $payout->paid_at = now();
        }
        
        $payout->save();

        return redirect()->route('admin.payouts.index')->with('success', 'Payout status updated successfully');
    }
}
