<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ManajemenPenggunaController extends Controller
{
    /**
     * Tampilkan halaman daftar admin users
     */
    public function index()
    {
        return view('owner.manajemen-pengguna.index');
    }

    /**
     * Tampilkan detail admin user
     */
    public function show(Admin $admin)
    {
        return view('owner.manajemen-pengguna.show', compact('admin'));
    }

    /**
     * Tampilkan form edit admin user
     */
    public function edit(Admin $admin)
    {
        return view('owner.manajemen-pengguna.edit', compact('admin'));
    }

    /**
     * Update admin user
     */
    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('admins')->ignore($admin->id)
            ],
            'role' => 'required|in:admin,teknisi',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Update data admin
        $admin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        // Update password jika diisi
        if (!empty($validated['password'])) {
            $admin->update([
                'password' => Hash::make($validated['password'])
            ]);
        }

        return redirect()
            ->route('pemilik.manajemen-pengguna.index')
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    /**
     * Soft delete admin user
     */
    public function destroy(Admin $admin)
    {
        // Pastikan tidak menghapus diri sendiri
        if ($admin->id === auth('pemilik')->id()) {
            return redirect()
                ->route('pemilik.manajemen-pengguna.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $admin->delete();

        return redirect()
            ->route('pemilik.manajemen-pengguna.index')
            ->with('success', 'Admin berhasil dihapus.');
    }

    /**
     * Restore soft deleted admin user
     */
    public function restore($id)
    {
        $admin = Admin::withTrashed()->findOrFail($id);
        $admin->restore();

        return redirect()
            ->route('pemilik.manajemen-pengguna.index')
            ->with('success', 'Admin berhasil dipulihkan.');
    }
}
