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
        $query = Customer::query();

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by hasAccount
        if ($request->filled('hasAccount')) {
            $query->where('hasAccount', $request->boolean('hasAccount'));
        }

        // Sorting
        $allowedSorts = ['customer_id', 'name', 'email', 'contact', 'hasAccount', 'last_active', 'address', 'updated_at'];
        $sort = $request->get('sort', 'updated_at');
        $direction = $request->get('direction', 'desc');

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'updated_at';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        $customers = $query->orderBy($sort, $direction)
            ->paginate(10)
            ->appends([
                'search' => $request->search,
                'hasAccount' => $request->hasAccount,
                'sort' => $sort,
                'direction' => $direction,
            ]);

        return view('admin.customer', compact('customers', 'sort', 'direction'));
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
                'address' => 'nullable|string',
                'gender' => 'nullable|in:pria,wanita',
                'hasAccount' => 'boolean',
                'password' => 'required_if:hasAccount,1|nullable|min:6',
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

            Customer::create($validated);

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
                'address' => 'nullable|string',
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
                }
                // Don't update password if not provided and account already exists
                $validated['last_active'] = now();
            } else {
                $validated['password'] = null;
                $validated['last_active'] = null;
            }

            $customer->update($validated);

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
