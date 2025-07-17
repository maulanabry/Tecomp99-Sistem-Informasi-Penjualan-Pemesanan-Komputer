<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\OrderService;
use App\Models\Product;
use App\Models\Service;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderServiceController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of order services assigned to the logged-in teknisi.
     */
    public function index()
    {
        return view('teknisi.order-service.index');
    }

    /**
     * Display the specified order service.
     */
    public function show(OrderService $orderService)
    {
        // Check if the order service has tickets assigned to the current teknisi
        $hasAssignedTicket = $orderService->tickets()
            ->where('admin_id', Auth::id())
            ->exists();

        if (!$hasAssignedTicket) {
            abort(403, 'Unauthorized access to this order service.');
        }

        return view('teknisi.order-service.show', compact('orderService'));
    }

    /**
     * Show the form for editing the specified order service.
     */
    public function edit(OrderService $orderService)
    {
        // Check if the order service has tickets assigned to the current teknisi
        $hasAssignedTicket = $orderService->tickets()
            ->where('admin_id', Auth::id())
            ->exists();

        if (!$hasAssignedTicket) {
            abort(403, 'Unauthorized access to this order service.');
        }

        // Check if order can be edited based on status
        if (in_array($orderService->status_order, ['Selesai', 'Dibatalkan'])) {
            return redirect()->route('teknisi.order-services.show', $orderService)
                ->with('error', 'Order yang sudah selesai atau dibatalkan tidak dapat diubah.');
        }

        $orderService->load(['customer.addresses']);
        return view('teknisi.order-service.edit', compact('orderService'));
    }

    /**
     * Update the specified order service.
     */
    public function update(Request $request, OrderService $orderService)
    {
        // Check if the order service has tickets assigned to the current teknisi
        $hasAssignedTicket = $orderService->tickets()
            ->where('admin_id', Auth::id())
            ->exists();

        if (!$hasAssignedTicket) {
            abort(403, 'Unauthorized access to this order service.');
        }

        // Check if order can be edited based on status
        if (in_array($orderService->status_order, ['Selesai', 'Dibatalkan'])) {
            return redirect()->route('teknisi.order-services.show', $orderService)
                ->with('error', 'Order yang sudah selesai atau dibatalkan tidak dapat diubah.');
        }

        $request->validate([
            'status_order' => 'required|in:Menunggu,Diproses,Konfirmasi,Diantar,Perlu Diambil,Dibatalkan,Selesai',
            'status_payment' => 'required|in:belum_dibayar,down_payment,lunas,dibatalkan',
            'type' => 'required|in:reguler,onsite',
            'device' => 'required|string',
            'complaints' => 'required|string',
            'note' => 'nullable|string',
            'sub_total' => 'required|integer|min:0',
            'discount_amount' => 'required|integer|min:0',
            'hasDevice' => 'nullable|boolean',
            'items' => 'required|json',
        ]);

        try {
            DB::beginTransaction();

            // Store previous status for comparison
            $previousStatus = $orderService->status_order;

            // Calculate grand total
            $grandTotal = $request->sub_total - $request->discount_amount;
            if ($grandTotal < 0) {
                throw new \Exception('Total setelah diskon tidak boleh kurang dari 0');
            }

            $orderService->update([
                'status_order' => $request->status_order,
                'status_payment' => $request->status_payment,
                'type' => $request->type,
                'device' => $request->device,
                'complaints' => $request->complaints,
                'note' => $request->note,
                'sub_total' => $request->sub_total,
                'discount_amount' => $request->discount_amount,
                'grand_total' => $grandTotal,
                'hasDevice' => $request->boolean('hasDevice'),
                'warranty_period_months' => $request->warranty_period_months,
            ]);

            // If order is being completed, set warranty expiration
            if ($previousStatus !== 'Selesai' && $request->status_order === 'Selesai') {
                $orderService->updateWarrantyExpiration(now());
            }

            // Update payment status
            $orderService->updatePaymentStatus();

            // Update order service items
            $items = json_decode($request->items, true);

            // Collect existing item IDs to track which to delete
            $existingItemIds = $orderService->items()->pluck('order_service_item_id')->toArray();
            $updatedItemIds = [];

            // Handle stock changes based on status transition
            if ($previousStatus !== 'Selesai' && $request->status_order === 'Selesai') {
                // Order is being completed - decrease stock and increase sold count
                foreach ($orderService->items as $item) {
                    $this->updateItemStock($item, true);
                }
            } elseif ($previousStatus !== 'Dibatalkan' && $request->status_order === 'Dibatalkan') {
                // Order is being cancelled - restore stock and decrease sold count
                foreach ($orderService->items as $item) {
                    $this->updateItemStock($item, false);
                }
            }

            foreach ($items as $itemData) {
                $itemId = $itemData['order_service_item_id'] ?? null;
                $quantity = $itemData['quantity'] ?? 1;
                $price = $itemData['price'] ?? 0;
                $itemType = $itemData['item_type'] ?? null;
                $itemIdValue = $itemData['item_id'] ?? null;
                $itemTotal = $price * $quantity;

                // Validate item_type and item_id
                if (empty($itemType) || empty($itemIdValue)) {
                    continue; // Skip invalid item
                }

                if ($itemId && in_array($itemId, $existingItemIds)) {
                    // Update existing item
                    $orderItem = $orderService->items()->where('order_service_item_id', $itemId)->first();
                    $orderItem->update([
                        'quantity' => $quantity,
                        'price' => $price,
                        'item_type' => $itemType,
                        'item_id' => $itemIdValue,
                        'item_total' => $itemTotal,
                    ]);
                    $updatedItemIds[] = $itemId;
                } else {
                    // Create new item
                    $newItemData = [
                        'quantity' => $quantity,
                        'price' => $price,
                        'item_type' => $itemType,
                        'item_id' => $itemIdValue,
                        'item_total' => $itemTotal,
                    ];
                    $orderService->items()->create($newItemData);
                }
            }

            // Delete removed items
            $itemsToDelete = array_diff($existingItemIds, $updatedItemIds);
            if (!empty($itemsToDelete)) {
                $orderService->items()->whereIn('order_service_item_id', $itemsToDelete)->delete();
            }

            DB::commit();

            return redirect()->route('teknisi.order-services.show', $orderService)
                ->with('success', 'Order servis berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->with('error', 'Gagal memperbarui order servis: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Validate voucher code for teknisi
     */
    public function validateVoucherCode(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $code = trim($request->input('voucher_code'));
        $subtotal = $request->input('subtotal');

        $voucher = \App\Models\Voucher::where('code', $code)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
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

        // Cap discount at subtotal
        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        return response()->json([
            'success' => true,
            'voucher_id' => $voucher->voucher_id,
            'voucher_name' => $voucher->name,
            'discount' => $discount,
            'discount_type' => $voucher->type,
            'discount_value' => $voucher->type === 'percentage' ? $voucher->discount_percentage : $voucher->discount_amount,
        ]);
    }

    /**
     * Helper method to update stock and sold count for items
     */
    private function updateItemStock($item, $isIncrease = true)
    {
        if ($item->item_type === 'App\\Models\\Product') {
            $product = Product::find($item->item_id);
            if ($product) {
                if ($isIncrease) {
                    $product->decrement('stock', $item->quantity);
                    $product->increment('sold_count', $item->quantity);
                } else {
                    $product->increment('stock', $item->quantity);
                    $product->decrement('sold_count', $item->quantity);
                }
            }
        } elseif ($item->item_type === 'App\\Models\\Service') {
            $service = Service::find($item->item_id);
            if ($service) {
                if ($isIncrease) {
                    $service->increment('sold_count', $item->quantity);
                } else {
                    $service->decrement('sold_count', $item->quantity);
                }
            }
        }
    }
}
