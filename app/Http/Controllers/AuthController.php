<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // Add Auth facade
use Illuminate\Support\Facades\Hash;  // For password hashing (if needed)
use Illuminate\Support\Facades\DB;   // Add DB facade
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    // Show login form
    public function index()
    {
        return view('login-admin');  // Return login view
    }

    // Handle login request
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email diperlukan',
            'password.required' => 'Password diperlukan',
        ]);

        $remember = $request->filled('remember');

        // Coba login sebagai admin
        if (Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'admin'
        ], $remember)) {
            // Update last_seen_at on successful login
            $userId = Auth::guard('admin')->id();
            DB::table('admins')->where('id', $userId)->update(['last_seen_at' => now()]);

            $request->session()->regenerate();
            return redirect()->intended('admin/dashboard');
        }

        // Coba login sebagai teknisi
        if (Auth::guard('teknisi')->attempt([
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'teknisi'
        ], $remember)) {
            // Update last_seen_at on successful login
            $userId = Auth::guard('teknisi')->id();
            DB::table('admins')->where('id', $userId)->update(['last_seen_at' => now()]);

            $request->session()->regenerate();
            return redirect()->intended('teknisi/dashboard');
        }

        // Coba login sebagai pemilik
        if (Auth::guard('pemilik')->attempt([
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'pemilik'
        ], $remember)) {
            // Update last_seen_at on successful login
            $userId = Auth::guard('pemilik')->id();
            DB::table('admins')->where('id', $userId)->update(['last_seen_at' => now()]);

            $request->session()->regenerate();
            return redirect()->intended('pemilik/dashboard');
        }

        // Jika login gagal
        return back()->withErrors([
            'email' => 'Email dan Password yang dimasukkan tidak sesuai',
        ]);
    }


    // Handle logout
    public function logout(): RedirectResponse
    {
        // Update last_seen_at before logout (optional final timestamp)
        if (Auth::guard('admin')->check()) {
            $userId = Auth::guard('admin')->id();
            DB::table('admins')->where('id', $userId)->update(['last_seen_at' => now()]);
            Auth::guard('admin')->logout();
        } elseif (Auth::guard('teknisi')->check()) {
            $userId = Auth::guard('teknisi')->id();
            DB::table('admins')->where('id', $userId)->update(['last_seen_at' => now()]);
            Auth::guard('teknisi')->logout();
        } elseif (Auth::guard('pemilik')->check()) {
            $userId = Auth::guard('pemilik')->id();
            DB::table('admins')->where('id', $userId)->update(['last_seen_at' => now()]);
            Auth::guard('pemilik')->logout();
        }

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/beranda');
    }
}
