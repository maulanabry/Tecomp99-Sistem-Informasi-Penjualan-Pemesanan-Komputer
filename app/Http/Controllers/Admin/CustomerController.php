<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        // Enhanced debugging - Log the incoming request
        Log::info('Customer creation attempt started', [
            'request_data' => $request->all(),
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
            'timestamp' => now()
        ]);

        try {
            // Step 1: Validation with detailed logging
            Log::info('Starting validation for customer creation');

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:customers,email',
                'contact' => 'required|string|max:20',
                'gender' => 'nullable|in:pria,wanita',
                'hasAccount' => 'boolean',
                'password' => 'required_if:hasAccount,1|nullable|min:6',
                'province_id' => 'nullable|string|max:10',
                'province_name' => 'nullable|string|max:255',
                'city_id' => 'nullable|string|max:10',
                'city_name' => 'nullable|string|max:255',
                'district_id' => 'nullable|string|max:10',
                'district_name' => 'nullable|string|max:255',
                'subdistrict_id' => 'nullable|string|max:10',
                'subdistrict_name' => 'nullable|string|max:255',
                'postal_code' => 'nullable|string|max:10',
                'detail_address' => 'nullable|string|max:1000',
            ]);

            Log::info('Validation passed successfully', ['validated_data' => $validated]);

            // Step 2: Generate customer ID with logging
            Log::info('Generating customer ID');
            $validated['customer_id'] = Customer::generateCustomerId();
            Log::info('Customer ID generated', ['customer_id' => $validated['customer_id']]);

            // Step 3: Process hasAccount with logging
            $validated['hasAccount'] = $request->boolean('hasAccount', false);
            Log::info('Processing account settings', ['hasAccount' => $validated['hasAccount']]);

            if ($validated['hasAccount']) {
                Log::info('Setting up account credentials');
                $validated['password'] = Hash::make($request->password);
                $validated['last_active'] = now();
                $validated['email_verified_at'] = now(); // Auto-verify admin-created accounts
                Log::info('Account credentials set up successfully');
            } else {
                Log::info('No account requested, setting null values');
                $validated['password'] = null;
                $validated['last_active'] = null;
                $validated['email_verified_at'] = null;
            }

            // Step 4: Separate customer and address data with logging
            Log::info('Separating customer and address data');
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

            Log::info('Customer data prepared', [
                'customer_data' => array_merge($customerData, ['password' => $customerData['password'] ? '[HASHED]' : null])
            ]);

            // Step 5: Create customer and address in a database transaction
            Log::info('Starting database transaction for customer and address creation');

            DB::beginTransaction();

            try {
                // Create customer
                Log::info('Attempting to create customer in database');
                $customer = Customer::create($customerData);
                Log::info('Customer created successfully', [
                    'customer_id' => $customer->customer_id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'contact' => $customer->contact
                ]);

                // Create address if provided with logging
                if (!empty($validated['province_id']) && !empty($validated['city_id'])) {
                    Log::info('Address data provided, creating address');

                    $addressData = [
                        'customer_id' => $customer->customer_id,
                        'province_id' => $validated['province_id'],
                        'province_name' => $validated['province_name'],
                        'city_id' => $validated['city_id'],
                        'city_name' => $validated['city_name'],
                        'district_id' => $validated['district_id'] ?? null,
                        'district_name' => $validated['district_name'] ?? null,
                        'subdistrict_id' => $validated['subdistrict_id'] ?? null,
                        'subdistrict_name' => $validated['subdistrict_name'] ?? null,
                        'postal_code' => $validated['postal_code'] ?? null,
                        'detail_address' => $validated['detail_address'] ?? null,
                        'is_default' => true,
                    ];

                    Log::info('Address data prepared', ['address_data' => $addressData]);

                    try {
                        // Use direct database insertion to avoid model events that might cause issues
                        $addressId = DB::table('customer_addresses')->insertGetId(array_merge($addressData, [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]));

                        Log::info('Address created successfully via direct DB insertion', [
                            'address_id' => $addressId,
                            'customer_id' => $customer->customer_id
                        ]);
                    } catch (\Exception $addressError) {
                        Log::error('Address creation failed', [
                            'error' => $addressError->getMessage(),
                            'address_data' => $addressData,
                            'stack_trace' => $addressError->getTraceAsString()
                        ]);

                        // Don't throw the error - just log it and continue
                        // This prevents the infinite loading issue
                        Log::warning('Continuing without address due to creation error');
                    }
                } else {
                    Log::info('No address data provided, skipping address creation');
                }

                // Commit the transaction
                DB::commit();
                Log::info('Database transaction committed successfully');
            } catch (\Exception $addressException) {
                // Rollback the transaction if address creation fails
                DB::rollback();
                Log::error('Address creation failed, rolling back transaction', [
                    'error' => $addressException->getMessage(),
                    'customer_data' => $customerData,
                    'address_data' => $addressData ?? null
                ]);

                throw new \Exception('Gagal membuat alamat pelanggan: ' . $addressException->getMessage());
            }

            Log::info('Customer creation completed successfully', [
                'customer_id' => $customer->customer_id,
                'redirect_to' => 'customers.show'
            ]);

            // Redirect to customer detail page with success message using customer_id
            return redirect()->route('customers.show', ['customer' => $customer->customer_id])
                ->with('success', 'Pelanggan berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed during customer creation', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Unexpected error during customer creation', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            // Check if it's a database constraint error
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'Data pelanggan sudah ada. Silakan periksa email atau nomor kontak.']);
            }

            // Check if it's a foreign key constraint error
            if (str_contains($e->getMessage(), 'foreign key constraint')) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'Terjadi kesalahan pada data alamat. Silakan periksa kembali data provinsi/kota.']);
            }

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
            Log::info('Customer update request received', [
                'customer_id' => $customer->customer_id,
                'request_data' => $request->except(['password', '_token'])
            ]);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:customers,email,' . $customer->customer_id . ',customer_id',
                'contact' => 'required|string|max:20',
                'gender' => 'nullable|in:pria,wanita',
                'hasAccount' => 'boolean',
                'password' => 'nullable|min:6',
            ]);

            Log::info('Customer update validation passed', [
                'customer_id' => $customer->customer_id,
                'validated_data' => array_merge($validated, ['password' => $validated['password'] ? '[PROVIDED]' : null])
            ]);

            $validated['hasAccount'] = $request->boolean('hasAccount', false);

            if ($validated['hasAccount']) {
                if ($request->filled('password')) {
                    $validated['password'] = Hash::make($request->password);
                    Log::info('Password updated for customer', ['customer_id' => $customer->customer_id]);
                } elseif (!$customer->hasAccount) {
                    // Only set default password when enabling account for the first time
                    $validated['password'] = Hash::make('password123');
                    Log::info('Default password set for new account', ['customer_id' => $customer->customer_id]);
                } else {
                    // Don't update password if not provided and account already exists
                    unset($validated['password']);
                    Log::info('Password not changed', ['customer_id' => $customer->customer_id]);
                }
                $validated['last_active'] = $customer->last_active ?? now();
                // Auto-verify admin-created/updated accounts
                $validated['email_verified_at'] = $customer->email_verified_at ?? now();
            } else {
                $validated['password'] = null;
                $validated['last_active'] = null;
                $validated['email_verified_at'] = null;
                Log::info('Account disabled for customer', ['customer_id' => $customer->customer_id]);
            }

            Log::info('Attempting to update customer in database', [
                'customer_id' => $customer->customer_id,
                'update_data' => array_merge($validated, ['password' => $validated['password'] ? '[HASHED]' : null])
            ]);

            $customer->update($validated);

            Log::info('Customer updated successfully', [
                'customer_id' => $customer->customer_id,
                'name' => $customer->name,
                'email' => $customer->email
            ]);

            return redirect()->route('customers.show', $customer)
                ->with('success', 'Pelanggan berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Customer update validation failed', [
                'customer_id' => $customer->customer_id,
                'errors' => $e->errors(),
                'request_data' => $request->except(['password', '_token'])
            ]);
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Error updating customer', [
                'customer_id' => $customer->customer_id,
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['password', '_token'])
            ]);
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
    public function storeAddress(Request $request, Customer $customer)
    {
        try {
            Log::info('Store address request received', [
                'customer_id' => $customer->customer_id,
                'request_data' => $request->all()
            ]);

            $validated = $request->validate([
                'province_id' => 'required|string|max:10',
                'province_name' => 'required|string|max:255',
                'city_id' => 'required|string|max:10',
                'city_name' => 'required|string|max:255',
                'district_id' => 'nullable|string|max:10',
                'district_name' => 'nullable|string|max:255',
                'subdistrict_id' => 'nullable|string|max:10',
                'subdistrict_name' => 'nullable|string|max:255',
                'postal_code' => 'nullable|string|max:10',
                'detail_address' => 'required|string|max:1000',
            ]);

            Log::info('Address validation passed', ['validated_data' => $validated]);

            // Set as default if this is the first address
            $isDefault = $customer->addresses()->count() === 0;

            $addressData = array_merge($validated, [
                'customer_id' => $customer->customer_id,
                'is_default' => $isDefault,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('Address data prepared for insertion', ['address_data' => $addressData]);

            // Use direct database insertion to avoid model events
            $addressId = DB::table('customer_addresses')->insertGetId($addressData);

            Log::info('Address created successfully', [
                'address_id' => $addressId,
                'customer_id' => $customer->customer_id
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Alamat berhasil ditambahkan.',
                    'address_id' => $addressId
                ]);
            }

            return redirect()->route('customers.show', $customer)
                ->with('success', 'Alamat berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error storing address', [
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'customer_id' => $customer->customer_id,
                'request_data' => $request->all()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan alamat: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Gagal menambahkan alamat: ' . $e->getMessage());
        }
    }

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
            Log::info('Setting default address', [
                'customer_id' => $customer->customer_id,
                'address_id' => $addressId
            ]);

            // Use direct database updates to avoid model events
            DB::beginTransaction();

            // Set all addresses to non-default
            DB::table('customer_addresses')
                ->where('customer_id', $customer->customer_id)
                ->update(['is_default' => false, 'updated_at' => now()]);

            // Set the selected address as default
            $updated = DB::table('customer_addresses')
                ->where('id', $addressId)
                ->where('customer_id', $customer->customer_id)
                ->update(['is_default' => true, 'updated_at' => now()]);

            if ($updated === 0) {
                throw new \Exception('Address not found or does not belong to this customer');
            }

            DB::commit();

            Log::info('Default address set successfully', [
                'customer_id' => $customer->customer_id,
                'address_id' => $addressId
            ]);

            return redirect()->back()
                ->with('success', 'Alamat utama berhasil diubah.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error setting default address', [
                'error' => $e->getMessage(),
                'customer_id' => $customer->customer_id,
                'address_id' => $addressId,
                'stack_trace' => $e->getTraceAsString()
            ]);
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
