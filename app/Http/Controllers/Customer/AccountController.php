<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{

    /**
     * Tampilkan halaman profil customer
     */
    public function profile()
    {
        $customer = Auth::guard('customer')->user();

        return view('customer.account.profile', compact('customer'));
    }

    /**
     * Update profil customer
     */
    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->customer_id . ',customer_id',
            'contact' => 'required|string|max:20',
            'gender' => 'nullable|in:pria,wanita',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh customer lain.',
            'contact.required' => 'Nomor handphone wajib diisi.',
            'gender.in' => 'Jenis kelamin harus pria atau wanita.',
        ]);

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'gender' => $request->gender,
        ]);

        return redirect()->route('customer.account.profile')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Tampilkan halaman ubah kata sandi
     */
    public function password()
    {
        return view('customer.account.password');
    }

    /**
     * Update kata sandi customer
     */
    public function updatePassword(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Kata sandi lama wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
        ]);

        // Verifikasi kata sandi lama
        if (!Hash::check($request->current_password, $customer->password)) {
            return back()->withErrors([
                'current_password' => 'Kata sandi lama tidak benar.'
            ]);
        }

        // Update kata sandi
        $customer->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('customer.account.password')
            ->with('success', 'Kata sandi berhasil diperbarui!');
    }

    /**
     * Tampilkan halaman alamat
     */
    public function addresses()
    {
        $customer = Auth::guard('customer')->user();
        $addresses = $customer->addresses()->orderBy('is_default', 'desc')->get();

        return view('customer.account.addresses', compact('addresses'));
    }
}
