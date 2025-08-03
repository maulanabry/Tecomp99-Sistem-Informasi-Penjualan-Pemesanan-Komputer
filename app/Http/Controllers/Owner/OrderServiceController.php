<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\OrderService;
use App\Models\Product;
use App\Models\Service;
use App\Models\Admin;
use App\Models\User;
use App\Services\NotificationService;
use App\Enums\NotificationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                'status_order' => 'Menunggu',
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

            // Create notifications for all admins and owners after order service is saved
            $admins = Admin::all();
            $owners = User::where('role', 'pemilik')->get();

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

            // Notifikasi untuk Owner/Pemilik
            foreach ($owners as $owner) {
                $this->notificationService->create(
                    notifiable: $owner,
                    type: NotificationType::SERVICE_ORDER_CREATED,
                    subject: $orderService->fresh(),
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

    public function update(Request $request, OrderService $orderService)
    {
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

            return redirect()->route('pemilik.order-service.show', $orderService)
                ->with('success', 'Order servis berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->with('error', 'Gagal memperbarui order servis: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function cancel(OrderService $orderService)
    {
        try {
            DB::beginTransaction();

            if ($orderService->status_payment === 'lunas') {
                throw new \Exception('Order yang sudah lunas tidak dapat dibatalkan');
            }

            // If order was completed, revert the stock changes
            if ($orderService->status_order === 'Selesai') {
                foreach ($orderService->items as $item) {
                    $this->updateItemStock($item, false);
                }
            }

            // Cancel all related service tickets
            $orderService->tickets()->update([
                'status' => 'Dibatalkan'
            ]);

            // Update the status_order to 'Dibatalkan' instead of deleting
            $orderService->update([
                'status_order' => 'Dibatalkan',
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
}
