<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Affiliate;
use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        // Handle referral logic
        $refCode = $request->cookie('referral_code');
        if ($refCode) {
            $affiliate = Affiliate::where('ref_code', $refCode)->first();
            if ($affiliate) {
                Referral::create([
                    'affiliate_id' => $affiliate->id,
                    'referred_user_id' => $user->id,
                    'ip_address' => $request->ip(),
                    'browser_info' => $request->userAgent(),
                    'commission' => 0,
                ]);
            }
        }

        return redirect()->route('home');
    }
}
