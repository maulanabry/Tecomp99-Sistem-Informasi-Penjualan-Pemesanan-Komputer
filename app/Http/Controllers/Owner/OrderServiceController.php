<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\OrderService;
use App\Models\Product;
use App\Models\Service;
use App\Models\Admin;
use App\Services\NotificationService;
use App\Enums\NotificationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OrderServiceController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        return view('owner.order-service.index');
    }

    public function show(OrderService $orderService)
    {
        $orderService->load(['customer.addresses', 'tickets', 'items', 'paymentDetails']);
        return view('owner.order-service.show', compact('orderService'));
    }

    public function showInvoice(OrderService $orderService)
    {
        $orderService->load(['customer.addresses', 'items.item', 'paymentDetails']);
        return view('owner.order-service.show-invoice', compact('orderService'));
    }

    public function showTandaTerima(OrderService $orderService)
    {
        // Hanya tampilkan tanda terima jika device tidak null
        if (!$orderService->device) {
            return redirect()->route('pemilik.order-service.show', $orderService)
                ->with('error', 'Tanda terima hanya tersedia untuk order servis yang memiliki perangkat.');
        }

        $orderService->load(['customer.addresses', 'tickets.admin']);
        return view('owner.order-service.show-tanda-terima', compact('orderService'));
    }

    public function create()
    {
        $customers = \App\Models\Customer::select('customer_id', 'name', 'email', 'contact')
            ->with('addresses')
            ->get();
        return view('owner.order-service.create', compact('customers'));
    }

    public function store(Request $request)
    {
        // Validate based on whether it's a new or existing customer
        if ($request->customer_id === 'new') {
            $request->validate([
                'name' => 'required|string|max:255',
                'contact' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'address' => 'required|string',
                'type' => 'required|in:reguler,onsite',
                'device' => 'required|string',
                'complaints' => 'required|string',
                'note' => 'nullable|string',
                'hasDevice' => 'nullable|boolean',
            ]);
        } else {
            $request->validate([
                'customer_id' => 'required|exists:customers,customer_id',
                'type' => 'required|in:reguler,onsite',
                'device' => 'required|string',
                'complaints' => 'required|string',
                'note' => 'nullable|string',
                'hasDevice' => 'nullable|boolean',
            ]);
        }

        try {
            // Handle customer creation or selection
            if ($request->customer_id === 'new') {
                $customer = \App\Models\Customer::create([
                    'customer_id' => \App\Models\Customer::generateCustomerId(),
                    'name' => $request->name,
                    'email' => $request->email,
                    'contact' => $request->contact,
                ]);

                // Create customer address
                $customer->addresses()->create([
                    'detail_address' => $request->address,
                    'is_default' => true,
                ]);
            } else {
                $customer = \App\Models\Customer::findOrFail($request->customer_id);
            }

            // Generate order service ID
            $date = date('dmy');
            $lastOrder = OrderService::withTrashed()
                ->where('order_service_id', 'like', "ORS{$date}%")
                ->orderBy('order_service_id', 'desc')
                ->first();

            $orderNumber = $lastOrder
                ? (int)substr($lastOrder->order_service_id, -3) + 1
                : 1;

            $orderServiceId = sprintf("ORS%s%03d", $date, $orderNumber);

            // Create order service
            $orderService = OrderService::create([
                'order_service_id' => $orderServiceId,
                'customer_id' => $customer->customer_id,
                'status_order' => 'menunggu',
                'status_payment' => 'belum_dibayar',
                'complaints' => $request->complaints,
                'type' => $request->type,
                'device' => $request->device,
                'note' => $request->note,
                'hasDevice' => $request->boolean('hasDevice'),
                'hasTicket' => false,
                'sub_total' => 0,
                'grand_total' => 0,
                'discount_amount' => 0,
            ]);

            // Increment service_orders_count for the customer
            $customer->increment('service_orders_count');

            // Create notifications for all admins after order service is saved
            $admins = Admin::all();

            // Notifikasi untuk Admin
            foreach ($admins as $admin) {
                $this->notificationService->create(
                    notifiable: $admin,
                    type: NotificationType::SERVICE_ORDER_CREATED,
                    subject: $orderService->fresh(), // Ensure we have the saved model with ID
                    message: "Pesanan servis baru #{$orderServiceId} dari {$customer->name}",
                    data: [
                        'order_id' => $orderServiceId,
                        'customer_name' => $customer->name,
                        'device' => $request->device,
                        'type' => $request->type,
                        'complaints' => $request->complaints
                    ]
                );
            }

            return redirect()->route('pemilik.order-service.show', $orderService)
                ->with('success', 'Order servis berhasil dibuat.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal membuat order servis: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(OrderService $orderService)
    {
        $orderService->load(['customer.addresses']);
        return view('owner.order-service.edit', compact('orderService'));
    }

    /**
     * Helper method to update stock and sold count for items
     */
    private function updateItemStock($item, $isIncrease = true, $quantity = null)
    {
        $qty = $quantity ?? $item->quantity;
        if ($item->item_type === 'App\\Models\\Product') {
            $product = Product::find($item->item_id);
            if ($product) {
                if ($isIncrease) {
                    $product->decrement('stock', $qty);
                    $product->increment('sold_count', $qty);
                } else {
                    $product->increment('stock', $qty);
                    $product->decrement('sold_count', $qty);
                }
            }
        } elseif ($item->item_type === 'App\\Models\\Service') {
            $service = Service::find($item->item_id);
            if ($service) {
                if ($isIncrease) {
                    $service->increment('sold_count', $qty);
                } else {
                    $service->decrement('sold_count', $qty);
                }
            }
        }
    }

    public function update(Request $request, OrderService $orderService)
    {
        $request->validate([
            'status_payment' => 'required|in:belum_dibayar,cicilan,lunas,dibatalkan',
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

            // Validate discount amount doesn't exceed subtotal
            if ($request->discount_amount > $request->sub_total) {
                throw new \Exception('Jumlah diskon tidak boleh melebihi subtotal');
            }

            // Calculate grand total
            $grandTotal = $request->sub_total - $request->discount_amount;
            if ($grandTotal < 0) {
                throw new \Exception('Total setelah diskon tidak boleh kurang dari 0');
            }

            $orderService->update([
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

            // Update payment status
            $orderService->updatePaymentStatus();

            // Update order service items
            $items = json_decode($request->items, true);

            // Collect existing item IDs to track which to delete
            $existingItemIds = $orderService->items()->pluck('order_service_item_id')->toArray();
            $updatedItemIds = [];

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
                    $oldQuantity = $orderItem->quantity;
                    $orderItem->update([
                        'quantity' => $quantity,
                        'price' => $price,
                        'item_type' => $itemType,
                        'item_id' => $itemIdValue,
                        'item_total' => $itemTotal,
                    ]);

                    // Adjust stock based on quantity change
                    $quantityDiff = $quantity - $oldQuantity;
                    if ($quantityDiff > 0) {
                        // Quantity increased, decrease stock
                        $this->updateItemStock($orderItem, true, abs($quantityDiff));
                    } elseif ($quantityDiff < 0) {
                        // Quantity decreased, increase stock
                        $this->updateItemStock($orderItem, false, abs($quantityDiff));
                    }

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
                    $newItem = $orderService->items()->create($newItemData);

                    // Decrease stock for new item
                    $this->updateItemStock($newItem, true, $quantity);
                }
            }

            // Delete removed items
            $itemsToDelete = array_diff($existingItemIds, $updatedItemIds);
            if (!empty($itemsToDelete)) {
                $itemsToDeleteModels = $orderService->items()->whereIn('order_service_item_id', $itemsToDelete)->get();
                foreach ($itemsToDeleteModels as $itemToDelete) {
                    // Increase stock for deleted item
                    $this->updateItemStock($itemToDelete, false, $itemToDelete->quantity);
                }
                $orderService->items()->whereIn('order_service_item_id', $itemsToDelete)->delete();
            }

            DB::commit();

            return redirect()->route('pemilik.order-service.show', $orderService)
                ->with('success', 'Order servis berhasil diperbarui.');
        } catch (ValidationException $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Validasi gagal: ' . collect($e->errors())->flatten()->implode(', '));
        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->with('error', 'Gagal memperbarui order servis: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function cancel(OrderService $orderService)
    {
        $orderService->load('items');

        try {
            DB::beginTransaction();

            if ($orderService->status_payment === 'lunas') {
                throw new \Exception('Order yang sudah lunas tidak dapat dibatalkan');
            }

            // Revert the stock changes for all items
            foreach ($orderService->items as $item) {
                $this->updateItemStock($item, false);
            }

            // Cancel all related service tickets
            $orderService->tickets()->update([
                'status' => 'Dibatalkan'
            ]);

            // Update the status_order to 'dibatalkan' instead of deleting
            $orderService->update([
                'status_order' => 'dibatalkan',
                'status_payment' => 'dibatalkan',
            ]);

            DB::commit();

            return redirect()->route('pemilik.order-service.index')
                ->with('success', 'Order servis dan tiket terkait berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('pemilik.order-service.index')
                ->with('error', 'Gagal membatalkan order servis: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, OrderService $orderService)
    {
        $validated = $request->validate([
            'status_order' => 'required|in:menunggu,dijadwalkan,menuju_lokasi,diproses,menunggu_sparepart,siap_diambil,diantar,selesai,dibatalkan',
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $orderService->status_order;
            $orderService->update($validated);

            // Adjust stock based on status change
            if ($oldStatus !== $orderService->status_order) {
                if ($orderService->status_order === 'dibatalkan') {
                    // Order is being cancelled - increase stock
                    foreach ($orderService->items as $item) {
                        $this->updateItemStock($item, false);
                    }
                }
            }

            // Update related service tickets if status changed to dibatalkan or selesai
            if ($oldStatus !== $orderService->status_order && $orderService->tickets()->exists()) {
                if ($orderService->status_order === 'dibatalkan') {
                    // Cancel all related service tickets
                    $orderService->tickets()->update([
                        'status' => 'Dibatalkan'
                    ]);
                } elseif ($orderService->status_order === 'selesai') {
                    // Complete all related service tickets that are not already completed or cancelled
                    $orderService->tickets()
                        ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
                        ->update([
                            'status' => 'Dibatalkan'
                        ]);
                }
            }

            // Create notification for status update if status changed
            if ($oldStatus !== $orderService->status_order) {
                try {
                    $type = match ($orderService->status_order) {
                        'selesai' => NotificationType::CUSTOMER_ORDER_SERVICE_STATUS_UPDATED,
                        default => NotificationType::CUSTOMER_ORDER_SERVICE_STATUS_UPDATED
                    };

                    $message = match ($orderService->status_order) {
                        'selesai' => "Order servis #{$orderService->order_service_id} telah selesai",
                        'diproses' => "Order servis #{$orderService->order_service_id} sedang diproses",
                        'diantar' => "Order servis #{$orderService->order_service_id} sedang diantar",
                        'siap_diambil' => "Order servis #{$orderService->order_service_id} siap diambil",
                        'dijadwalkan' => "Order servis #{$orderService->order_service_id} telah dijadwalkan",
                        'menuju_lokasi' => "Order servis #{$orderService->order_service_id} sedang menuju lokasi",
                        'menunggu_sparepart' => "Order servis #{$orderService->order_service_id} menunggu sparepart",
                        default => "Status order servis #{$orderService->order_service_id} diubah menjadi {$orderService->status_order}"
                    };

                    // Notify customer
                    if ($orderService->customer) {
                        $this->notificationService->create(
                            notifiable: $orderService->customer,
                            type: $type,
                            subject: $orderService,
                            message: $message,
                            data: [
                                'order_id' => $orderService->order_service_id,
                                'status' => $orderService->status_order,
                                'device' => $orderService->device,
                                'type' => $orderService->type
                            ]
                        );
                    }

                    // Notify all admins
                    $admins = Admin::where('role', 'admin')->get();
                    foreach ($admins as $admin) {
                        $adminType = match ($orderService->status_order) {
                            'selesai' => NotificationType::SERVICE_ORDER_COMPLETED,
                            default => NotificationType::SERVICE_ORDER_STARTED
                        };

                        $this->notificationService->create(
                            notifiable: $admin,
                            type: $adminType,
                            subject: $orderService,
                            message: "Status order servis #{$orderService->order_service_id} diubah menjadi {$orderService->status_order} oleh Owner",
                            data: [
                                'order_id' => $orderService->order_service_id,
                                'customer_name' => $orderService->customer->name,
                                'status' => $orderService->status_order,
                                'device' => $orderService->device,
                                'type' => $orderService->type
                            ]
                        );
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to create order service status update notification: ' . $e->getMessage());
                }
            }

            DB::commit();

            $ticketMessage = '';
            if ($orderService->tickets()->exists()) {
                $ticketCount = $orderService->tickets()->count();
                if ($orderService->status_order === 'dibatalkan') {
                    $ticketMessage = " dan {$ticketCount} tiket servis terkait telah dibatalkan";
                } elseif ($orderService->status_order === 'selesai') {
                    $ticketMessage = " dan {$ticketCount} tiket servis terkait telah diselesaikan";
                }
            }

            return redirect()->back()
                ->with('success', "Status order servis berhasil diperbarui{$ticketMessage}.");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui status order servis: ' . $e->getMessage());
        }
    }

    public function destroy(OrderService $orderService)
    {
        return $this->cancel($orderService);
    }

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
     * Remove voucher/discount from order
     */
    public function removeVoucher(Request $request, OrderService $orderService)
    {
        try {
            DB::beginTransaction();

            // Calculate new grand total without discount
            $newGrandTotal = $orderService->sub_total;

            $orderService->update([
                'discount_amount' => 0,
                'grand_total' => $newGrandTotal,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Voucher berhasil dihapus',
                'new_grand_total' => $newGrandTotal,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus voucher: ' . $e->getMessage(),
            ], 500);
        }
    }
}
