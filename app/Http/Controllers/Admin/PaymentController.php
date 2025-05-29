<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with(["user", "subscription.plan"])->latest()->paginate(20);
        // Return the actual view now that it exists
        return view("admin.payments.index", compact("payments"));
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(["user", "subscription.plan"]);
        // Return the actual view now that it exists
        return view("admin.payments.show", compact("payment"));
    }
}

