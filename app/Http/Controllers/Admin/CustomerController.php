<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.customer');
    }

    public function create()
    {
        return view('admin.customer.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:customers,email',
                'contact' => 'required|string|max:20',
                'gender' => 'nullable|in:pria,wanita',
                'hasAccount' => 'boolean',
                'password' => 'required_if:hasAccount,1|nullable|min:6',
                'province_id' => 'nullable|integer',
                'province_name' => 'nullable|string|max:255',
                'city_id' => 'nullable|integer',
                'city_name' => 'nullable|string|max:255',
                'district_id' => 'nullable|integer',
                'district_name' => 'nullable|string|max:255',
                'subdistrict_id' => 'nullable|integer',
                'subdistrict_name' => 'nullable|string|max:255',
                'postal_code' => 'nullable|string|max:10',
                'detail_address' => 'nullable|string',
            ]);

            $validated['customer_id'] = Customer::generateCustomerId();
            $validated['hasAccount'] = $request->boolean('hasAccount', false);

            if ($validated['hasAccount']) {
                $validated['password'] = Hash::make($request->password);
                $validated['last_active'] = now();
                $validated['email_verified_at'] = now(); // Auto-verify admin-created accounts
            } else {
                $validated['password'] = null;
                $validated['last_active'] = null;
                $validated['email_verified_at'] = null;
            }

            $customerData = collect($validated)->except([
                'province_id',
                'province_name',
                'city_id',
                'city_name',
                'district_id',
                'district_name',
                'subdistrict_id',
                'subdistrict_name',
                'postal_code',
                'detail_address',
            ])->toArray();

            $customer = Customer::create($customerData);

            // Only create address if province_id and city_id are provided
            if ($validated['province_id'] && $validated['city_id']) {
                $customer->addresses()->create([
                    'province_id' => $validated['province_id'],
                    'province_name' => $validated['province_name'],
                    'city_id' => $validated['city_id'],
                    'city_name' => $validated['city_name'],
                    'district_id' => $validated['district_id'] ?? null,
                    'district_name' => $validated['district_name'] ?? null,
                    'subdistrict_id' => $validated['subdistrict_id'] ?? null,
                    'subdistrict_name' => $validated['subdistrict_name'] ?? null,
                    'postal_code' => $validated['postal_code'],
                    'detail_address' => $validated['detail_address'],
                    'is_default' => true,
                ]);
            }

            return redirect()->route('customers.index')
                ->with('success', 'Pelanggan berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating customer: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menambahkan pelanggan: ' . $e->getMessage()]);
        }
    }

    public function show(Customer $customer)
    {
        $customer->load(['addresses', 'orderProducts', 'orderServices']);
        return view('admin.customer.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $customer->load(['addresses']);
        return view('admin.customer.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:customers,email,' . $customer->customer_id . ',customer_id',
                'contact' => 'required|string|max:20',
                'gender' => 'nullable|in:pria,wanita',
                'hasAccount' => 'boolean',
                'password' => 'nullable|min:6',
            ]);

            $validated['hasAccount'] = $request->boolean('hasAccount', false);

            if ($validated['hasAccount']) {
                if ($request->filled('password')) {
                    $validated['password'] = Hash::make($request->password);
                } elseif (!$customer->hasAccount) {
                    // Only set default password when enabling account for the first time
                    $validated['password'] = Hash::make('password123');
                } else {
                    // Don't update password if not provided and account already exists
                    unset($validated['password']);
                }
                $validated['last_active'] = $customer->last_active ?? now();
                // Auto-verify admin-created/updated accounts
                $validated['email_verified_at'] = $customer->email_verified_at ?? now();
            } else {
                $validated['password'] = null;
                $validated['last_active'] = null;
                $validated['email_verified_at'] = null;
            }

            $customer->update($validated);

            return redirect()->route('customers.index')
                ->with('success', 'Pelanggan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating customer: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui pelanggan: ' . $e->getMessage()]);
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
            return redirect()->route('customers.index')
                ->with('success', 'Pelanggan berhasil dihapus sementara.');
        } catch (\Exception $e) {
            return redirect()->route('customers.index')
                ->with('error', 'Gagal menghapus pelanggan.');
        }
    }

    public function recovery(Request $request)
    {
        $query = Customer::onlyTrashed();

        // Filter customers by search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('contact', 'like', '%' . $request->search . '%')
                    ->orWhere('customer_id', 'like', '%' . $request->search . '%');
            });
        }

        $customers = $query->orderBy('deleted_at', 'desc')->paginate(10)->appends([
            'search' => $request->search,
        ]);

        return view('admin.customer.recovery', compact('customers'));
    }

    public function restore($id)
    {
        try {
            $customer = Customer::onlyTrashed()->findOrFail($id);
            $customer->restore();
            return back()->with('success', 'Pelanggan berhasil dipulihkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memulihkan pelanggan: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $customer = Customer::withTrashed()->findOrFail($id);
            $customer->forceDelete();
            return redirect()->route('customers.recovery')
                ->with('success', 'Pelanggan berhasil dihapus permanen.');
        } catch (\Exception $e) {
            return redirect()->route('customers.recovery')
                ->with('error', 'Gagal menghapus pelanggan secara permanen.');
        }
    }

    // Address management methods
    public function updateAddress(Request $request, Customer $customer, $addressId)
    {
        try {
            $validated = $request->validate([
                'province_id' => 'required|integer',
                'province_name' => 'required|string|max:255',
                'city_id' => 'required|integer',
                'city_name' => 'required|string|max:255',
                'district_id' => 'required|integer',
                'district_name' => 'required|string|max:255',
                'subdistrict_id' => 'required|integer',
                'subdistrict_name' => 'required|string|max:255',
                'postal_code' => 'nullable|string|max:10',
                'detail_address' => 'required|string',
            ]);

            $address = $customer->addresses()->findOrFail($addressId);
            $address->update($validated);

            return redirect()->route('customers.show', $customer)
                ->with('success', 'Alamat berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating address: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui alamat: ' . $e->getMessage());
        }
    }

    public function setDefaultAddress(Customer $customer, $addressId)
    {
        try {
            // Set all addresses to non-default
            $customer->addresses()->update(['is_default' => false]);

            // Set the selected address as default
            $address = $customer->addresses()->findOrFail($addressId);
            $address->update(['is_default' => true]);

            return redirect()->route('customers.show', $customer)
                ->with('success', 'Alamat utama berhasil diubah.');
        } catch (\Exception $e) {
            Log::error('Error setting default address: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengubah alamat utama: ' . $e->getMessage());
        }
    }

    // Legacy methods for backward compatibility
    public function createStep1()
    {
        return redirect()->route('customers.create');
    }

    public function storeStep1(Request $request)
    {
        return $this->store($request);
    }

    public function createStep2($customer_id)
    {
        $customer = Customer::findOrFail($customer_id);
        return redirect()->route('customers.edit', $customer);
    }

    public function storeStep2(Request $request, $customer_id)
    {
        $customer = Customer::findOrFail($customer_id);
        return $this->update($request, $customer);
    }
}
