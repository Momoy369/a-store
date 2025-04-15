<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->update($request->only('phone', 'address'));

        return redirect()->route('profile.edit')->with('status', 'Profil berhasil diperbarui.');
    }

    public function showCustomers()
    {
        $customers = User::where('role', 'customer')->get();
        return view('admin.customers.index', compact('customers'));
    }
}
