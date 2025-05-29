<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\License;
use App\Models\User;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['user', 'license'])->latest()->paginate(10);
        return view('admin.purchases.index', compact('purchases'));
    }

    public function show(Purchase $purchase)
    {
        return view('admin.purchases.show', compact('purchase'));
    }

    public function create()
    {
        $users = User::all();
        $licenses = License::all();
        return view('admin.purchases.create', compact('users', 'licenses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'license_id' => 'required|exists:licenses,id',
            'amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'status' => 'required|string',
        ]);

        Purchase::create($request->all());

        return redirect()->route('admin.purchases.index')->with('success', 'Purchase created successfully');
    }

    public function edit(Purchase $purchase)
    {
        $users = User::all();
        $licenses = License::all();
        return view('admin.purchases.edit', compact('purchase', 'users', 'licenses'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'license_id' => 'required|exists:licenses,id',
            'amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'status' => 'required|string',
        ]);

        $purchase->update($request->all());

        return redirect()->route('admin.purchases.index')->with('success', 'Purchase updated successfully');
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('admin.purchases.index')->with('success', 'Purchase deleted successfully');
    }
}
