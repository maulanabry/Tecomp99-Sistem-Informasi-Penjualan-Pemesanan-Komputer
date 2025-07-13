<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{
    /**
     * Show customer login form
     */
    public function showLoginForm()
    {
        return view('customer.auth.login');
    }

    /**
     * Handle customer login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string',
            'password' => 'required|string|min:8',
        ], [
            'identifier.required' => 'Email atau No. Handphone diperlukan.',
            'password.required' => 'Kata sandi diperlukan.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $identifier = $request->identifier;
        $password = $request->password;
        $remember = $request->filled('remember');

        // Find customer by email or phone
        $customer = Customer::findForAuth($identifier);

        if (!$customer) {
            return back()->withErrors([
                'identifier' => 'Akun tidak ditemukan.'
            ])->withInput();
        }

        // Check if customer has account
        if (!$customer->hasAccount || !$customer->password) {
            return back()->withErrors([
                'identifier' => 'Akun belum diaktifkan. Silakan hubungi admin.'
            ])->withInput();
        }

        // Verify password
        if (!Hash::check($password, $customer->password)) {
            return back()->withErrors([
                'password' => 'Kata sandi salah.'
            ])->withInput();
        }

        // Login customer
        Auth::guard('customer')->login($customer, $remember);

        // Update last active
        $customer->update(['last_active' => now()]);

        $request->session()->regenerate();

        return redirect()->intended('/')->with('success', 'Selamat datang, ' . $customer->name . '!');
    }

    /**
     * Show customer registration form
     */
    public function showRegistrationForm()
    {
        return view('customer.auth.register');
    }

    /**
     * Handle customer registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email',
            'contact' => 'required|string|max:20|unique:customers,contact',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
        ], [
            'name.required' => 'Nama lengkap diperlukan.',
            'email.required' => 'Email diperlukan.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'contact.required' => 'No. Handphone diperlukan.',
            'contact.unique' => 'No. Handphone sudah terdaftar.',
            'password.required' => 'Kata sandi diperlukan.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'terms.required' => 'Anda harus menyetujui syarat dan ketentuan.',
            'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Generate customer ID
        $customerId = Customer::generateCustomerId();

        // Create customer
        $customer = Customer::create([
            'customer_id' => $customerId,
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'password' => Hash::make($request->password),
            'hasAccount' => true,
            'last_active' => now(),
        ]);

        // Auto login after registration
        Auth::guard('customer')->login($customer);

        return redirect('/')->with('success', 'Akun berhasil dibuat! Selamat datang, ' . $customer->name . '!');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('customer.auth.forgot-password');
    }

    /**
     * Handle forgot password (placeholder for future implementation)
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string',
        ], [
            'identifier.required' => 'Email atau No. Handphone diperlukan.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // For now, just show a message that this feature will be implemented later
        return back()->with('info', 'Fitur reset kata sandi akan segera tersedia. Silakan hubungi admin untuk bantuan.');
    }

    /**
     * Handle customer logout
     */
    public function logout(Request $request)
    {
        // Update last active before logout
        if (Auth::guard('customer')->check()) {
            $customer = Auth::guard('customer')->user();
            $customer->update(['last_active' => now()]);
        }

        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Show customer dashboard (placeholder)
     */
    public function dashboard()
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect()->route('customer.login');
        }

        // For now, redirect to home page
        // In the future, this can be a dedicated customer dashboard
        return redirect('/')->with('info', 'Dashboard pelanggan akan segera tersedia.');
    }
}
