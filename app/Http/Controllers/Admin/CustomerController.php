<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.customer');
    }

    public function createStep1()
    {
        return view('admin.customer.create_customer_info');
    }

    public function storeStep1(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:customers,email',
                'contact' => 'required|string|max:20',
                'gender' => 'nullable|in:pria,wanita',
            ]);

            $validated['customer_id'] = Customer::generateCustomerId();
            $validated['hasAccount'] = false;
            $validated['password'] = null;
            $validated['last_active'] = null;

            $customer = Customer::create($validated);

            return redirect()->route('customers.create.step2', ['customer' => $customer->customer_id])
                ->with('success', 'Data pelanggan berhasil disimpan. Silakan lengkapi alamat.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menambahkan data pelanggan: ' . $e->getMessage()]);
        }
    }

    public function createStep2($customer_id)
    {
        $customer = Customer::findOrFail($customer_id);
        return view('admin.customer.create_customer_address', compact('customer'));
    }

    public function storeStep2(Request $request, $customer_id)
    {
        $customer = Customer::findOrFail($customer_id);

        try {
            if ($request->input('action') === 'skip') {
                return redirect()->route('customers.index')
                    ->with('success', 'Input alamat dilewati.');
            }

            $validated = $request->validate([
                'province_id' => 'required|integer',
                'province_name' => 'required|string|max:255',
                'city_id' => 'required|integer',
                'city_name' => 'required|string|max:255',
                'postal_code' => 'required|string|max:10',
                'detail_address' => 'required|string',
            ]);

            $customer->addresses()->create([
                'province_id' => $validated['province_id'],
                'province_name' => $validated['province_name'],
                'city_id' => $validated['city_id'],
                'city_name' => $validated['city_name'],
                'postal_code' => $validated['postal_code'],
                'detail_address' => $validated['detail_address'],
            ]);

            return redirect()->route('customers.index')
                ->with('success', 'Alamat berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan alamat: ' . $e->getMessage()]);
        }
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
                'subdistrict_id' => 'nullable|integer',
                'postal_code' => 'nullable|string|max:10',
                'detail_address' => 'nullable|string',
            ]);

            $validated['customer_id'] = Customer::generateCustomerId();
            $validated['hasAccount'] = $request->boolean('hasAccount', false);

            if ($validated['hasAccount']) {
                $validated['password'] = Hash::make($request->password);
                $validated['last_active'] = now();
            } else {
                $validated['password'] = null;
                $validated['last_active'] = null;
            }

            $customerData = collect($validated)->except([
                'province_id',
                'city_id',
                'subdistrict_id',
                'postal_code',
                'detail_address',
                'is_default'
            ])->toArray();

            $customer = Customer::create($customerData);

            // Only create address if province_id and city_id are provided
            if ($validated['province_id'] && $validated['city_id']) {
                $customer->addresses()->create([
                    'province_id' => $validated['province_id'],
                    'province_name' => $validated['province_name'],
                    'city_id' => $validated['city_id'],
                    'city_name' => $validated['city_name'],
                    'subdistrict_id' => $validated['subdistrict_id'] ?? null,
                    'postal_code' => $validated['postal_code'],
                    'detail_address' => $validated['detail_address'],
                ]);
            }

            return redirect()->route('customers.index')
                ->with('success', 'Data pelanggan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menambahkan data pelanggan: ' . $e->getMessage()]);
        }
    }

    public function show(Customer $customer)
    {
        return view('admin.customer.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
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
                'province_id' => 'nullable|integer',
                'province_name' => 'nullable|string|max:255',
                'city_id' => 'nullable|integer',
                'city_name' => 'nullable|string|max:255',
                'subdistrict_id' => 'nullable|integer',
                'postal_code' => 'nullable|string|max:10',
                'detail_address' => 'nullable|string',
            ]);

            $validated['hasAccount'] = $request->boolean('hasAccount', false);

            if ($validated['hasAccount']) {
                if ($request->filled('password')) {
                    $validated['password'] = Hash::make($request->password);
                } elseif (!$customer->hasAccount) {
                    // Only set default password when enabling account for the first time
                    $validated['password'] = Hash::make('password123');
                }
                // Don't update password if not provided and account already exists
                $validated['last_active'] = now();
            } else {
                $validated['password'] = null;
                $validated['last_active'] = null;
            }

            $customerData = collect($validated)->except([
                'province_id',
                'city_id',
                'subdistrict_id',
                'postal_code',
                'detail_address',
                'is_default'
            ])->toArray();

            $customer->update($customerData);

            // Only update/create address if province_id and city_id are provided
            if ($validated['province_id'] && $validated['city_id']) {
                $addressData = [
                    'province_id' => $validated['province_id'],
                    'province_name' => $validated['province_name'],
                    'city_id' => $validated['city_id'],
                    'city_name' => $validated['city_name'],
                    'subdistrict_id' => $validated['subdistrict_id'] ?? null,
                    'postal_code' => $validated['postal_code'],
                    'detail_address' => $validated['detail_address'],
                ];

                $existingAddress = $customer->addresses()->where('is_default', true)->first();
                if ($existingAddress) {
                    $existingAddress->update($addressData);
                } else {
                    $customer->addresses()->create($addressData);
                }
            }

            return redirect()->route('customers.index')
                ->with('success', 'Data pelanggan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui data pelanggan: ' . $e->getMessage()]);
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
            return redirect()->route('customers.index')
                ->with('success', 'Data pelanggan berhasil dihapus sementara.');
        } catch (\Exception $e) {
            return redirect()->route('customers.index')
                ->with('error', 'Gagal menghapus data pelanggan.');
        }
    }

    public function recovery()
    {
        $customers = Customer::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('admin.customer.recovery', compact('customers'));
    }

    public function restore($id)
    {
        try {
            $customer = Customer::onlyTrashed()->findOrFail($id);
            $customer->restore();
            return back()->with('success', 'Data pelanggan berhasil dipulihkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memulihkan data pelanggan: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $customer = Customer::withTrashed()->findOrFail($id);
            $customer->forceDelete();
            return redirect()->route('customers.recovery')
                ->with('success', 'Data pelanggan berhasil dihapus permanen.');
        } catch (\Exception $e) {
            return redirect()->route('customers.recovery')
                ->with('error', 'Gagal menghapus data pelanggan secara permanen.');
        }
    }
}
