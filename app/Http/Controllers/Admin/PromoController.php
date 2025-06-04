<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $query = Promo::query();
        $deletedQuery = Promo::onlyTrashed();

        // Filter promos by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Sorting
        $allowedSorts = ['promo_id', 'name', 'code', 'type', 'discount_percentage', 'discount_amount', 'minimum_order_amount', 'is_active', 'start_date', 'end_date', 'updated_at'];
        $sort = $request->get('sort', 'updated_at');
        $direction = $request->get('direction', 'desc');

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'updated_at';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        // Filter deleted promos by name
        if ($request->filled('recovery_search')) {
            $deletedQuery->where('name', 'like', '%' . $request->recovery_search . '%');
        }

        $promos = $query->orderBy($sort, $direction)->paginate(10)->appends([
            'search' => $request->search,
            'type' => $request->type,
            'sort' => $sort,
            'direction' => $direction,
        ]);

        $deletedPromos = $deletedQuery
            ->orderBy('deleted_at', 'desc')
            ->paginate(10)
            ->appends([
                'recovery_search' => $request->recovery_search
            ]);

        return view('admin.promo', compact('promos', 'deletedPromos', 'sort', 'direction'));
    }

    public function create()
    {
        return view('admin.promo.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'code' => 'required|max:50|unique:promos,code',
                'is_active' => 'boolean',
                'type' => 'required|in:amount,percentage',
                'discount_percentage' => 'required_if:type,percentage|nullable|numeric|min:0|max:100',
                'discount_amount' => 'required_if:type,amount|nullable|integer|min:0',
                'minimum_order_amount' => 'nullable|integer|min:0',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $validated['is_active'] = (bool) $request->input('is_active', false);

            Promo::create($validated);

            return redirect()->route('promos.index')
                ->with('success', 'Promo berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menambahkan promo: ' . $e->getMessage()]);
        }
    }

    public function edit(Promo $promo)
    {
        return view('admin.promo.edit', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'code' => 'required|max:50|unique:promos,code,' . $promo->promo_id . ',promo_id',
                'is_active' => 'boolean',
                'type' => 'required|in:amount,percentage',
                'discount_percentage' => 'required_if:type,percentage|nullable|numeric|min:0|max:100',
                'discount_amount' => 'required_if:type,amount|nullable|integer|min:0',
                'minimum_order_amount' => 'nullable|integer|min:0',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $validated['is_active'] = (bool) $request->input('is_active', false);

            $promo->update($validated);

            return redirect()->route('promos.index')
                ->with('success', 'Promo berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui promo: ' . $e->getMessage()]);
        }
    }

    public function destroy(Promo $promo)
    {
        try {
            $promo->delete();
            return redirect()->route('promos.index')
                ->with('success', 'Promo berhasil dihapus sementara.');
        } catch (\Exception $e) {
            return redirect()->route('promos.index')
                ->with('error', 'Gagal menghapus promo.');
        }
    }

    public function restore($id)
    {
        try {
            $promo = Promo::onlyTrashed()->findOrFail($id);
            $promo->restore();
            return back()
                ->with('success', 'Promo berhasil dipulihkan.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal memulihkan promo: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $promo = Promo::withTrashed()->findOrFail($id);
            $promo->forceDelete();
            return redirect()->route('promos.recovery')
                ->with('success', 'Promo berhasil dihapus permanen.');
        } catch (\Exception $e) {
            return redirect()->route('promos.recovery')
                ->with('error', 'Gagal menghapus promo secara permanen.');
        }
    }

    public function recovery()
    {
        $promos = Promo::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('admin.promo.recovery', compact('promos'));
    }

    public function show(Promo $promo)
    {
        return view('admin.promo.show', compact('promo'));
    }
    public function validatePromo(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = $request->input('code');

        $promo = Promo::where('code', $code)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        if (!$promo) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak valid atau sudah kedaluwarsa',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'promo_id' => $promo->promo_id,
            'promo_name' => $promo->name,
            'discount_type' => $promo->type,
            'discount_value' => $promo->type === 'percentage' ? $promo->discount_percentage : $promo->discount_amount,
            'minimum_order_amount' => $promo->minimum_order_amount,
        ]);
    }
}
