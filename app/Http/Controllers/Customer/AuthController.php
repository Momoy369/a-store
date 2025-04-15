<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('customer.auth.login');
    }

    public function login(Request $request)
    {
        if (Auth::guard('customer')->attempt($request->only('email', 'password'))) {
            return redirect()->route('customer.dashboard');
        }

        return back()->withErrors(['email' => 'Login gagal']);
    }

    public function showRegister()
    {
        return view('customer.auth.register');
    }

    public function register(Request $request)
    {
        Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('customer.login')->with('success', 'Registrasi berhasil');
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect()->route('customer.login')->with('success', 'Logout berhasil');
    }
}
