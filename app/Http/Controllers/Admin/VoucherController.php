<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $query = Voucher::query();

        // Filter vouchers by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        // Sorting
        $allowedSorts = ['voucher_id', 'name', 'code', 'type', 'discount_percentage', 'discount_amount', 'minimum_order_amount', 'is_active', 'start_date', 'end_date', 'updated_at'];
        $sort = $request->get('sort', 'updated_at');
        $direction = $request->get('direction', 'desc');

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'updated_at';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        $vouchers = $query->orderBy($sort, $direction)->paginate(10)->appends([
            'search' => $request->search,
            'sort' => $sort,
            'direction' => $direction,
        ]);

        // Get deleted vouchers for recovery
        $deletedQuery = Voucher::onlyTrashed();

        // Filter deleted vouchers by name
        if ($request->filled('recovery_search')) {
            $deletedQuery->where('name', 'like', '%' . $request->recovery_search . '%')
                ->orWhere('code', 'like', '%' . $request->recovery_search . '%');
        }

        $deletedVouchers = $deletedQuery->orderBy($sort, $direction)->paginate(10)->appends([
            'search' => $request->search,
            'recovery_search' => $request->recovery_search,
            'sort' => $sort,
            'direction' => $direction,
        ]);

        return view('admin.voucher', compact('vouchers', 'deletedVouchers', 'sort', 'direction'));
    }

    public function create()
    {
        return view('admin.voucher.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'code' => 'required|max:50|unique:vouchers,code',
                'type' => 'required|in:amount,percentage',
                'discount_percentage' => 'nullable|numeric|min:0|max:100',
                'discount_amount' => 'nullable|integer|min:0',
                'minimum_order_amount' => 'nullable|integer|min:0',
                'is_active' => 'boolean',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $validated['is_active'] = $request->has('is_active');

            Voucher::create($validated);

            return redirect()->route('vouchers.index')
                ->with('success', 'Voucher berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating voucher: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menambahkan voucher: ' . $e->getMessage()]);
        }
    }

    public function edit(Voucher $voucher)
    {
        return view('admin.voucher.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'code' => 'required|max:50|unique:vouchers,code,' . $voucher->voucher_id . ',voucher_id',
                'type' => 'required|in:amount,percentage',
                'discount_percentage' => 'nullable|numeric|min:0|max:100',
                'discount_amount' => 'nullable|integer|min:0',
                'minimum_order_amount' => 'nullable|integer|min:0',
                'is_active' => 'boolean',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $validated['is_active'] = $request->has('is_active');

            $voucher->update($validated);

            return redirect()->route('vouchers.index')
                ->with('success', 'Voucher berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating voucher: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui voucher: ' . $e->getMessage()]);
        }
    }

    public function destroy(Voucher $voucher)
    {
        try {
            $voucher->delete();

            return redirect()->route('vouchers.index')
                ->with('success', 'Voucher berhasil dihapus sementara.');
        } catch (\Exception $e) {
            return redirect()->route('vouchers.index')
                ->with('error', 'Gagal menghapus voucher.');
        }
    }

    public function restore($id)
    {
        try {
            $voucher = Voucher::onlyTrashed()->findOrFail($id);
            $voucher->restore();

            return back()
                ->with('success', 'Voucher berhasil dipulihkan.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal memulihkan voucher: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $voucher = Voucher::withTrashed()->findOrFail($id);
            $voucher->forceDelete();

            return redirect()->route('vouchers.recovery')
                ->with('success', 'Voucher berhasil dihapus permanen.');
        } catch (\Exception $e) {
            return redirect()->route('vouchers.recovery')
                ->with('error', 'Gagal menghapus voucher secara permanen.');
        }
    }

    public function recovery(Request $request)
    {
        $vouchers = Voucher::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('admin.voucher.recovery', compact('vouchers'));
    }

    public function show(Voucher $voucher)
    {
        return view('admin.voucher.show', compact('voucher'));
    }

    public function validateVoucherCode(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $code = trim($request->input('voucher_code'));
        $subtotal = $request->input('subtotal');

        $voucher = Voucher::where('code', $code)
            ->where('is_active', true)
            ->valid()
            ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Kode voucher tidak valid atau sudah kedaluwarsa',
            ], 404);
        }

        if ($voucher->minimum_order_amount && $subtotal < $voucher->minimum_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal pembelian untuk voucher ini adalah Rp ' . number_format($voucher->minimum_order_amount, 0, ',', '.'),
            ], 400);
        }

        // Calculate discount based on voucher type
        $discount = 0;
        if ($voucher->type === 'percentage' && $voucher->discount_percentage) {
            $discount = intval(($subtotal * $voucher->discount_percentage) / 100);
        } elseif ($voucher->type === 'amount' && $voucher->discount_amount) {
            $discount = $voucher->discount_amount;
        }

        return response()->json([
            'success' => true,
            'voucher_id' => $voucher->voucher_id,
            'voucher_name' => $voucher->name,
            'discount' => $discount,
            'discount_type' => $voucher->type,
            'discount_value' => $voucher->type === 'percentage' ? $voucher->discount_percentage : $voucher->discount_amount,
            'minimum_order_amount' => $voucher->minimum_order_amount,
        ]);
    }
}
