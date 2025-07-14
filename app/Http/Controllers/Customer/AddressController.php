<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Simpan alamat baru
     */
    public function store(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $request->validate([
            'province_id' => 'required|string',
            'province_name' => 'required|string',
            'city_id' => 'required|string',
            'city_name' => 'required|string',
            'district_id' => 'required|string',
            'district_name' => 'required|string',
            'subdistrict_id' => 'required|string',
            'subdistrict_name' => 'required|string',
            'postal_code' => 'required|string|max:10',
            'detail_address' => 'required|string|max:500',
            'is_default' => 'boolean',
        ], [
            'province_id.required' => 'Provinsi wajib dipilih.',
            'city_id.required' => 'Kota/Kabupaten wajib dipilih.',
            'district_id.required' => 'Kecamatan wajib dipilih.',
            'subdistrict_id.required' => 'Kelurahan/Desa wajib dipilih.',
            'postal_code.required' => 'Kode pos wajib diisi.',
            'detail_address.required' => 'Alamat lengkap wajib diisi.',
        ]);

        CustomerAddress::create([
            'customer_id' => $customer->customer_id,
            'province_id' => $request->province_id,
            'province_name' => $request->province_name,
            'city_id' => $request->city_id,
            'city_name' => $request->city_name,
            'district_id' => $request->district_id,
            'district_name' => $request->district_name,
            'subdistrict_id' => $request->subdistrict_id,
            'subdistrict_name' => $request->subdistrict_name,
            'postal_code' => $request->postal_code,
            'detail_address' => $request->detail_address,
            'is_default' => $request->boolean('is_default'),
        ]);

        return redirect()->route('customer.account.addresses')
            ->with('success', 'Alamat berhasil ditambahkan!');
    }

    /**
     * Update alamat
     */
    public function update(Request $request, CustomerAddress $address)
    {
        $customer = Auth::guard('customer')->user();

        // Pastikan alamat milik customer yang sedang login
        if ($address->customer_id !== $customer->customer_id) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'province_id' => 'required|string',
            'province_name' => 'required|string',
            'city_id' => 'required|string',
            'city_name' => 'required|string',
            'district_id' => 'required|string',
            'district_name' => 'required|string',
            'subdistrict_id' => 'required|string',
            'subdistrict_name' => 'required|string',
            'postal_code' => 'required|string|max:10',
            'detail_address' => 'required|string|max:500',
            'is_default' => 'boolean',
        ], [
            'province_id.required' => 'Provinsi wajib dipilih.',
            'city_id.required' => 'Kota/Kabupaten wajib dipilih.',
            'district_id.required' => 'Kecamatan wajib dipilih.',
            'subdistrict_id.required' => 'Kelurahan/Desa wajib dipilih.',
            'postal_code.required' => 'Kode pos wajib diisi.',
            'detail_address.required' => 'Alamat lengkap wajib diisi.',
        ]);

        $address->update([
            'province_id' => $request->province_id,
            'province_name' => $request->province_name,
            'city_id' => $request->city_id,
            'city_name' => $request->city_name,
            'district_id' => $request->district_id,
            'district_name' => $request->district_name,
            'subdistrict_id' => $request->subdistrict_id,
            'subdistrict_name' => $request->subdistrict_name,
            'postal_code' => $request->postal_code,
            'detail_address' => $request->detail_address,
            'is_default' => $request->boolean('is_default'),
        ]);

        return redirect()->route('customer.account.addresses')
            ->with('success', 'Alamat berhasil diperbarui!');
    }

    /**
     * Hapus alamat
     */
    public function destroy(CustomerAddress $address)
    {
        $customer = Auth::guard('customer')->user();

        // Pastikan alamat milik customer yang sedang login
        if ($address->customer_id !== $customer->customer_id) {
            abort(403, 'Akses ditolak.');
        }

        // Tidak bisa hapus alamat default jika masih ada alamat lain
        if ($address->is_default && $customer->addresses()->count() > 1) {
            return redirect()->route('customer.account.addresses')
                ->with('error', 'Tidak dapat menghapus alamat utama. Silakan jadikan alamat lain sebagai alamat utama terlebih dahulu.');
        }

        $address->delete();

        return redirect()->route('customer.account.addresses')
            ->with('success', 'Alamat berhasil dihapus!');
    }

    /**
     * Set alamat sebagai default
     */
    public function setDefault(CustomerAddress $address)
    {
        $customer = Auth::guard('customer')->user();

        // Pastikan alamat milik customer yang sedang login
        if ($address->customer_id !== $customer->customer_id) {
            abort(403, 'Akses ditolak.');
        }

        $address->setAsDefault();

        return redirect()->route('customer.account.addresses')
            ->with('success', 'Alamat utama berhasil diperbarui!');
    }
}
