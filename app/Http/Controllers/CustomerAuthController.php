<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Mail\CustomerEmailVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

        // Check if email is verified
        if (!$customer->hasVerifiedEmail()) {
            return back()->withErrors([
                'identifier' => 'Akun Anda belum diverifikasi. Silakan cek email Anda untuk verifikasi.'
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
            'email_verified_at' => null, // Email not verified yet
        ]);

        // Send email verification using custom mail class
        try {
            Mail::to($customer->email)->send(new CustomerEmailVerification($customer));
            // Redirect directly to verification notice without success message
            return redirect()->route('verification.notice');
        } catch (\Exception $e) {
            // If email fails, still redirect to verification notice but with error message
            return redirect()->route('verification.notice')->with('info', 'Akun berhasil dibuat! Namun terjadi masalah saat mengirim email verifikasi. Silakan gunakan fitur kirim ulang.');
        }
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

    /**
     * Show email verification notice
     */
    public function verificationNotice()
    {
        return view('customer.auth.verify-email');
    }

    /**
     * Handle email verification
     */
    public function verifyEmail(Request $request)
    {
        $customer = Customer::find($request->route('id'));

        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Link verifikasi tidak valid.');
        }

        if ($customer->hasVerifiedEmail()) {
            return redirect()->route('customer.login')->with('info', 'Email Anda sudah diverifikasi sebelumnya.');
        }

        if ($customer->markEmailAsVerified()) {
            return redirect()->route('customer.login')->with('success', 'Email Anda berhasil diverifikasi. Silakan login untuk melanjutkan.');
        }

        return redirect()->route('customer.login')->with('error', 'Link verifikasi tidak valid atau sudah kedaluwarsa.');
    }

    /**
     * Resend email verification
     */
    public function resendVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:customers,email',
        ], [
            'email.required' => 'Email diperlukan.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak ditemukan.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $customer = Customer::where('email', $request->email)->first();

        if ($customer->hasVerifiedEmail()) {
            return back()->with('info', 'Email Anda sudah diverifikasi.');
        }

        try {
            Mail::to($customer->email)->send(new CustomerEmailVerification($customer));
            return back()->with('success', 'Link verifikasi telah dikirim ulang ke email Anda.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi masalah saat mengirim email. Silakan coba lagi nanti.');
        }
    }
}
