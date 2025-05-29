<?php

namespace App\Http\Controllers\Admin\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('user')->latest()->paginate(25);
        return view('admin.purchases.index', compact('purchases'));
    }
}
