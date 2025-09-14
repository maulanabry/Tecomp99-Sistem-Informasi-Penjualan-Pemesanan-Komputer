<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\OrderProduct;
use App\Models\OrderProductItem;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\Shipping;
use App\Models\Admin;
use App\Services\NotificationService;
use App\Enums\NotificationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderProductController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Tampilkan daftar order produk
     */
    public function index()
    {
        return view('owner.order-produk.index');
    }

    /**
     * Tampilkan form untuk membuat order produk baru
     */
    public function create()
    {
        return view('owner.order-produk.create');
    }

    /**
     * Simpan order produk baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'order_type' => 'required|in:Pengiriman,Langsung',
            'items' => 'required|json',
            'shipping_cost' => 'nullable|integer|min:0',
            'voucher_code' => 'nullable|string',
            'voucher_id' => 'nullable|exists:vouchers,voucher_id',
            'note' => 'nullable|string',
            'warranty_period_months' => 'nullable|integer|min:0|max:60',
        ]);

        try {
            Log::debug('Validated order_type: ' . $validated['order_type']);
            // Mapping tipe order ke nilai enum database
            $typeMapping = [
                'Pengiriman' => 'pengiriman',
                'Langsung' => 'langsung',
            ];
            $dbType = $typeMapping[$validated['order_type']] ?? 'langsung';
            Log::debug('Mapped dbType: ' . $dbType);

            $items = json_decode($validated['items'], true);
            if (empty($items)) {
                throw new \Exception('Minimal satu produk harus dipilih');
            }

            $subtotal = 0;
            $totalWeight = 0;

            foreach ($items as $item) {
                $product = \App\Models\Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok tidak mencukupi untuk produk {$product->name}");
                }

                $subtotal += $item['quantity'] * $product->price;
                $totalWeight += $item['quantity'] * $product->weight;
            }

            $discount = 0;
            if (!empty($validated['voucher_code'])) {
                // Cari voucher berdasarkan kode, lalu validasi
                $voucher = \App\Models\Voucher::where('code', $validated['voucher_code'])
                    ->where('is_active', true)
                    ->first();

                if (!$voucher) {
                    throw new \Exception('Kode voucher tidak ditemukan atau tidak aktif');
                }

                // Validasi kondisi voucher
                if (
                    now() < $voucher->start_date ||
                    now() > $voucher->end_date
                ) {
                    throw new \Exception('Voucher sudah kedaluwarsa atau belum berlaku');
                }

                if ($voucher->minimum_order_amount && $subtotal < $voucher->minimum_order_amount) {
                    throw new \Exception('Minimal pembelian untuk voucher ini adalah Rp ' . number_format($voucher->minimum_order_amount, 0, ',', '.'));
                }

                // Hitung diskon
                if ($voucher->type === 'percentage') {
                    $discount = intval(($subtotal * $voucher->discount_percentage) / 100);
                } else {
                    $discount = $voucher->discount_amount;
                }

                if ($discount > $subtotal) {
                    $discount = $subtotal;
                }

                $voucher->increment('used_count');
            }

            $shippingCost = $validated['order_type'] === 'Pengiriman' ? ($validated['shipping_cost'] ?? 0) : 0;
            $grandTotal = $subtotal - $discount + $shippingCost;

            $orderId = 'ORD' . date('dmy') . str_pad(
                \App\Models\OrderProduct::withTrashed()->count() + 1, // Hitung semua order, termasuk yang dihapus
                3,
                '0',
                STR_PAD_LEFT
            );

            $order = \App\Models\OrderProduct::create([
                'order_product_id' => $orderId,
                'customer_id' => $validated['customer_id'],
                'status_order' => 'menunggu',
                'status_payment' => 'belum_dibayar',
                'sub_total' => $subtotal,
                'discount_amount' => $discount,
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
                'type' => $dbType,
                'note' => $validated['note'] ?? null,
            ]);

            foreach ($items as $item) {
                $product = \App\Models\Product::findOrFail($item['product_id']);

                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'item_total' => $item['quantity'] * $product->price,
                ]);

                $product->decrement('stock', $item['quantity']);
                $product->increment('sold_count', $item['quantity']);
            }

            if ($validated['order_type'] === 'Pengiriman') {
                $order->shipping()->create([
                    'courier_name' => 'JNE',
                    'courier_service' => 'REG',
                    'status' => 'menunggu',
                    'shipping_cost' => $shippingCost,
                    'total_weight' => $totalWeight,
                ]);
            }

            $customer = \App\Models\Customer::findOrFail($validated['customer_id']);
            $customer->increment('product_orders_count');

            if ($grandTotal >= 100000) {
                $customer->increment('total_points', 100);
            }

            // Buat notifikasi untuk semua admin setelah order disimpan
            $admins = Admin::all();
            foreach ($admins as $admin) {
                $this->notificationService->create(
                    notifiable: $admin,
                    type: NotificationType::PRODUCT_ORDER_CREATED,
                    subject: $order->fresh(), // Pastikan kita memiliki model yang tersimpan dengan ID
                    message: "Pesanan produk baru #{$orderId} dari {$customer->name}",
                    data: [
                        'order_id' => $orderId,
                        'customer_name' => $customer->name,
                        'total' => $grandTotal,
                        'items_count' => count($items),
                        'type' => $dbType
                    ]
                );
            }

            return redirect()
                ->route('pemilik.order-produk.index')
                ->with('success', 'Order produk berhasil dibuat');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat order: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail order produk
     */
    public function show(OrderProduct $orderProduct)
    {
        return view('owner.order-produk.show', compact('orderProduct'));
    }

    /**
     * Tampilkan invoice order produk
     */
    public function showInvoice(OrderProduct $orderProduct)
    {
        // Eager load relasi yang diperlukan untuk mencegah N+1 queries
        $orderProduct->load([
            'customer.addresses',
            'customer.defaultAddress',
            'items.product',
            'shipping',
            'payments'
        ]);

        return view('owner.order-produk.show-invoice', compact('orderProduct'));
    }

    /**
     * Tampilkan form untuk mengedit order produk
     */
    public function edit(OrderProduct $orderProduct)
    {
        // Load relasi untuk tampilan edit
        $orderProduct->load(['customer.addresses', 'items.product', 'shipping']);

        return view('owner.order-produk.edit', compact('orderProduct'));
    }

    /**
     * Perbarui order produk di database
     */
    public function update(Request $request, OrderProduct $orderProduct)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'order_type' => 'required|in:Pengiriman,Langsung',
            'items' => 'required|json',
            'shipping_cost' => 'nullable|integer|min:0',
            'discount_amount' => 'nullable|integer|min:0',
            'voucher_code' => 'nullable|string',
            'voucher_id' => 'nullable|exists:vouchers,voucher_id',
            'note' => 'nullable|string',
            'warranty_period_months' => 'nullable|integer|min:0|max:60',
        ]);

        try {
            DB::beginTransaction();

            // Mapping tipe order ke nilai enum database
            $typeMapping = [
                'Pengiriman' => 'pengiriman',
                'Langsung' => 'langsung',
            ];
            $dbType = $typeMapping[$validated['order_type']] ?? 'langsung';

            $items = json_decode($validated['items'], true);
            if (empty($items)) {
                throw new \Exception('Minimal satu produk harus dipilih');
            }

            // Kembalikan stok untuk item yang ada sebelum update
            foreach ($orderProduct->items as $existingItem) {
                $product = Product::find($existingItem->product_id);
                if ($product) {
                    $product->increment('stock', $existingItem->quantity);
                    $product->decrement('sold_count', $existingItem->quantity);
                }
            }

            // Hitung total baru
            $subtotal = 0;
            $totalWeight = 0;

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok tidak mencukupi untuk produk {$product->name}");
                }

                $subtotal += $item['quantity'] * $product->price;
                $totalWeight += $item['quantity'] * $product->weight;
            }

            // Calculate shipping cost first
            $shippingCost = $validated['order_type'] === 'Pengiriman' ? ($validated['shipping_cost'] ?? 0) : 0;

            // Calculate discount - prioritize manual discount amount
            $discount = $validated['discount_amount'] ?? 0;

            // If no manual discount but voucher code provided, calculate voucher discount
            if ($discount == 0 && !empty($validated['voucher_code'])) {
                // Cari voucher berdasarkan kode, lalu validasi
                $voucher = Voucher::where('code', $validated['voucher_code'])
                    ->where('is_active', true)
                    ->first();

                if (!$voucher) {
                    throw new \Exception('Kode voucher tidak ditemukan atau tidak aktif');
                }

                // Validasi kondisi voucher
                if (
                    now() < $voucher->start_date ||
                    now() > $voucher->end_date
                ) {
                    throw new \Exception('Voucher sudah kedaluwarsa atau belum berlaku');
                }

                if ($voucher->minimum_order_amount && $subtotal < $voucher->minimum_order_amount) {
                    throw new \Exception('Minimal pembelian untuk voucher ini adalah Rp ' . number_format($voucher->minimum_order_amount, 0, ',', '.'));
                }

                // Hitung diskon
                if ($voucher->type === 'percentage') {
                    $discount = intval(($subtotal * $voucher->discount_percentage) / 100);
                } else {
                    $discount = $voucher->discount_amount;
                }

                if ($discount > $subtotal) {
                    $discount = $subtotal;
                }
            }

            // Ensure discount doesn't exceed subtotal + shipping
            $maxDiscount = $subtotal + $shippingCost;
            if ($discount > $maxDiscount) {
                $discount = $maxDiscount;
            }
            $grandTotal = $subtotal - $discount + $shippingCost;

            // Update order produk
            $orderProduct->update([
                'customer_id' => $validated['customer_id'],
                'sub_total' => $subtotal,
                'discount_amount' => $discount,
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
                'type' => $dbType,
                'note' => $validated['note'] ?? null,
                'warranty_period_months' => $validated['warranty_period_months'] ?? null,
            ]);

            // Update status pembayaran
            $orderProduct->updatePaymentStatus();

            // Hapus item yang ada
            $orderProduct->items()->delete();

            // Buat item baru dan update stok
            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);

                $orderProduct->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'item_total' => $item['quantity'] * $product->price,
                ]);

                $product->decrement('stock', $item['quantity']);
                $product->increment('sold_count', $item['quantity']);
            }

            // Update atau buat record pengiriman
            if ($validated['order_type'] === 'Pengiriman') {
                $orderProduct->shipping()->updateOrCreate(
                    ['order_product_id' => $orderProduct->order_product_id],
                    [
                        'courier_name' => 'JNE',
                        'courier_service' => 'REG',
                        'status' => 'menunggu',
                        'shipping_cost' => $shippingCost,
                        'total_weight' => $totalWeight,
                    ]
                );
            } else {
                // Hapus record pengiriman jika tipe order bukan pengiriman
                $orderProduct->shipping()->delete();
            }

            DB::commit();

            return redirect()
                ->route('pemilik.order-produk.show', $orderProduct)
                ->with('success', 'Order produk berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui order: ' . $e->getMessage());
        }
    }

    /**
     * Update status order produk
     */
    public function updateStatus(Request $request, OrderProduct $orderProduct)
    {
        $validated = $request->validate([
            'status_order' => 'required|in:menunggu,diproses,dikirim,selesai,dibatalkan',
        ]);

        $oldStatus = $orderProduct->status_order;
        $orderProduct->update($validated);

        // Create notification for status update if status changed
        if ($oldStatus !== $orderProduct->status_order) {
            try {
                $type = NotificationType::CUSTOMER_ORDER_PRODUCT_STATUS_UPDATED;

                $message = match ($orderProduct->status_order) {
                    'selesai' => "Order produk #{$orderProduct->order_product_id} telah selesai",
                    'diproses' => "Order produk #{$orderProduct->order_product_id} sedang diproses",
                    'dikirim' => "Order produk #{$orderProduct->order_product_id} sedang dikirim",
                    'menunggu' => "Order produk #{$orderProduct->order_product_id} menunggu konfirmasi",
                    default => "Status order produk #{$orderProduct->order_product_id} diubah menjadi {$orderProduct->status_order}"
                };

                // Notify customer
                if ($orderProduct->customer) {
                    $this->notificationService->create(
                        notifiable: $orderProduct->customer,
                        type: $type,
                        subject: $orderProduct,
                        message: $message,
                        data: [
                            'order_id' => $orderProduct->order_product_id,
                            'status' => $orderProduct->status_order,
                            'total' => $orderProduct->grand_total,
                            'type' => $orderProduct->type
                        ]
                    );
                }

                // Notify all admins
                $admins = Admin::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $adminType = match ($orderProduct->status_order) {
                        'selesai' => NotificationType::PRODUCT_ORDER_SHIPPED,
                        'dikirim' => NotificationType::PRODUCT_ORDER_SHIPPED,
                        default => NotificationType::PRODUCT_ORDER_CREATED
                    };

                    $this->notificationService->create(
                        notifiable: $admin,
                        type: $adminType,
                        subject: $orderProduct,
                        message: "Status order produk #{$orderProduct->order_product_id} diubah menjadi {$orderProduct->status_order} oleh Owner",
                        data: [
                            'order_id' => $orderProduct->order_product_id,
                            'customer_name' => $orderProduct->customer->name,
                            'status' => $orderProduct->status_order,
                            'total' => $orderProduct->grand_total,
                            'type' => $orderProduct->type
                        ]
                    );
                }
            } catch (\Exception $e) {
                Log::error('Failed to create order product status update notification: ' . $e->getMessage());
            }
        }

        return redirect()->back()
            ->with('success', 'Status order produk berhasil diperbarui.');
    }

    /**
     * Batalkan order produk
     */
    public function cancel(OrderProduct $orderProduct)
    {
        try {
            // Kembalikan stok produk dan kurangi jumlah terjual untuk setiap item order
            foreach ($orderProduct->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                    $product->decrement('sold_count', $item->quantity);
                }
            }

            // Update status_order menjadi 'dibatalkan'
            $orderProduct->update([
                'status_order' => 'dibatalkan',
                'status_payment' => 'dibatalkan',
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan order: ' . $e->getMessage());
        }
        return redirect()->route('pemilik.order-produk.index')->with('success', 'Order produk berhasil dibatalkan.');
    }

    /**
     * Validasi kode voucher
     */
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

        // Hitung diskon berdasarkan tipe voucher
        $discount = 0;
        if ($voucher->type === 'percentage' && $voucher->discount_percentage) {
            $discount = intval(($subtotal * $voucher->discount_percentage) / 100);
        } elseif ($voucher->type === 'amount' && $voucher->discount_amount) {
            $discount = $voucher->discount_amount;
        }

        // Batasi diskon maksimal sesuai subtotal
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
     * Tampilkan form edit pengiriman
     */
    public function editShipping(OrderProduct $orderProduct)
    {
        if ($orderProduct->type !== 'pengiriman') {
            return redirect()->route('pemilik.order-produk.index')
                ->with('error', 'Pesanan ini bukan tipe pengiriman.');
        }

        return view('owner.order-produk.edit-shipping', compact('orderProduct'));
    }

    /**
     * Update informasi pengiriman
     */
    public function updateShipping(Request $request, OrderProduct $orderProduct)
    {
        if ($orderProduct->type !== 'pengiriman') {
            return redirect()->route('pemilik.order-produk.index')
                ->with('error', 'Pesanan ini bukan tipe pengiriman.');
        }

        $validated = $request->validate([
            'kurir' => 'required|string|max:100',
            'nomor_resi' => 'nullable|string|max:100',
            'status_pengiriman' => 'required|in:menunggu,dikirim,diterima,dibatalkan',
            'tanggal_pengiriman' => 'nullable|date',
        ]);

        try {
            // Dapatkan record pengiriman yang terkait dengan order produk
            $shipping = $orderProduct->shipping;

            // Jika tidak ada record pengiriman, buat yang baru
            if (!$shipping) {
                $shipping = new Shipping();
                $shipping->order_product_id = $orderProduct->order_product_id;
            }

            // Update atribut pengiriman dengan data yang divalidasi
            $shipping->courier_name = $validated['kurir'];
            $shipping->tracking_number = $validated['nomor_resi'];
            $shipping->status = $validated['status_pengiriman'];
            $shipping->shipped_at = $validated['tanggal_pengiriman'];

            // Simpan perubahan ke record pengiriman
            $shipping->save();

            // Update status order jika status pengiriman berubah
            if ($validated['status_pengiriman'] === 'dikirim' && $orderProduct->status_order !== 'dikirim') {
                $orderProduct->update(['status_order' => 'dikirim']);
            } elseif ($validated['status_pengiriman'] === 'diterima' && $orderProduct->status_order !== 'selesai') {
                $orderProduct->update(['status_order' => 'selesai']);
            }

            return redirect()->route('pemilik.order-produk.show', $orderProduct)
                ->with('success', 'Informasi pengiriman berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui informasi pengiriman: ' . $e->getMessage());
        }
    }
}
