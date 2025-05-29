<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Affiliate;
use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Handle referral conversion
        if (session()->has('referral_code')) {
            $affiliate = Affiliate::where('ref_code', session('referral_code'))->first();
            if ($affiliate) {
                $referral = Referral::where('affiliate_id', $affiliate->id)
                    ->whereNull('referred_user_id')
                    ->latest()->first();

                if ($referral) {
                    $referral->converted = true;
                    $referral->converted_at = now();
                    $referral->referred_user_id = $user->id;
                    $referral->save();
                }
            }
        }

        auth()->login($user);

        return redirect()->route('dashboard')->with('success', 'Registration successful!');
    }
}
