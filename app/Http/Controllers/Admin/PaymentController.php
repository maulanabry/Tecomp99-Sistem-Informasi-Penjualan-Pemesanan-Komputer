<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentDetail;
use App\Models\OrderService;
use App\Models\OrderProduct;
use App\Models\Admin;
use App\Services\NotificationService;
use App\Enums\NotificationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function index()
    {
        return view('admin.payment');
    }

    public function show($payment_id)
    {
        $payment = PaymentDetail::with(['orderProduct.customer', 'orderService.customer'])
            ->where('payment_id', $payment_id)
            ->firstOrFail();
        return view('admin.payment.show', compact('payment'));
    }

    public function create(Request $request)
    {
        // Cek apakah ada order_service_id atau order_product_id yang dikirim dari halaman detail order
        $preSelectedOrder = null;
        $preSelectedOrderType = null;

        if ($request->has('order_service_id')) {
            $orderService = OrderService::with('customer')->where('order_service_id', $request->order_service_id)->first();
            if ($orderService && !in_array($orderService->status_payment, ['dibatalkan', 'lunas', 'selesai'])) {
                $preSelectedOrder = [
                    'id' => $orderService->order_service_id,
                    'type' => 'servis',
                    'customer_name' => $orderService->customer->name,
                    'customer_id' => $orderService->customer_id,
                    'sub_total' => (float) $orderService->sub_total,
                    'discount_amount' => (float) $orderService->discount_amount,
                    'grand_total' => (float) $orderService->grand_total,
                    'paid_amount' => (float) $orderService->paid_amount,
                    'remaining_balance' => (float) $orderService->remaining_balance,
                    'status_order' => $orderService->status_order,
                    'status_payment' => $orderService->status_payment,
                    'last_payment_at' => $orderService->last_payment_at,
                    'created_at' => $orderService->created_at,
                    'device' => $orderService->device,
                    'order_type_display' => 'Servis',
                ];
                $preSelectedOrderType = 'servis';
            }
        } elseif ($request->has('order_product_id')) {
            $orderProduct = OrderProduct::with('customer')->where('order_product_id', $request->order_product_id)->first();
            if ($orderProduct && !in_array($orderProduct->status_payment, ['dibatalkan', 'lunas'])) {
                $preSelectedOrder = [
                    'id' => $orderProduct->order_product_id,
                    'type' => 'produk',
                    'customer_name' => $orderProduct->customer->name,
                    'customer_id' => $orderProduct->customer_id,
                    'sub_total' => (float) $orderProduct->sub_total,
                    'discount_amount' => (float) $orderProduct->discount_amount,
                    'grand_total' => (float) $orderProduct->grand_total,
                    'paid_amount' => (float) $orderProduct->paid_amount,
                    'remaining_balance' => (float) $orderProduct->remaining_balance,
                    'status_order' => $orderProduct->status_order,
                    'status_payment' => $orderProduct->status_payment,
                    'last_payment_at' => $orderProduct->last_payment_at,
                    'created_at' => $orderProduct->created_at,
                    'shipping_cost' => (float) $orderProduct->shipping_cost,
                    'order_type_display' => 'Produk',
                ];
                $preSelectedOrderType = 'produk';
            }
        }

        // Ambil order produk yang statusnya belum dibayar atau down_payment saja
        $orderProducts = OrderProduct::with('customer')
            ->whereNotIn('status_payment', ['dibatalkan', 'lunas', 'selesai'])
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->order_product_id,
                    'customer_name' => $order->customer->name,
                    'sub_total' => (float) $order->sub_total,
                    'discount_amount' => (float) $order->discount_amount,
                    'grand_total' => (float) $order->grand_total,
                    'paid_amount' => (float) $order->paid_amount,
                    'remaining_balance' => (float) $order->remaining_balance,
                    'last_payment_at' => $order->last_payment_at ? $order->last_payment_at->toISOString() : null,
                    'payment_status' => $order->status_payment
                ];
            });

        // Ambil order servis yang statusnya belum dibayar atau cicilan saja
        $orderServices = OrderService::with('customer')
            ->whereNotIn('status_payment', ['dibatalkan', 'lunas', 'selesai'])
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->order_service_id,
                    'customer_name' => $order->customer->name,
                    'sub_total' => (float) $order->sub_total,
                    'discount_amount' => (float) $order->discount_amount,
                    'grand_total' => (float) $order->grand_total,
                    'paid_amount' => (float) $order->paid_amount,
                    'remaining_balance' => (float) $order->remaining_balance,
                    'last_payment_at' => $order->last_payment_at ? $order->last_payment_at->toISOString() : null,
                    'payment_status' => $order->status_payment
                ];
            });

        return view('admin.payment.create', [
            'orderProducts' => $orderProducts,
            'orderServices' => $orderServices,
            'preSelectedOrder' => $preSelectedOrder,
            'preSelectedOrderType' => $preSelectedOrderType,
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Basic request validation
            $validationRules = [
                'order_type' => 'required|in:produk,servis',
                'order_id' => 'required|string',
                'method' => 'required|in:' . implode(',', array_keys(PaymentDetail::PAYMENT_METHODS)),
                'amount' => 'required|numeric|min:1',
                'payment_type' => 'required|in:' . implode(',', array_keys(PaymentDetail::PAYMENT_TYPES)),
                'proof_photo' => 'nullable|image|max:2048',
                'warranty_period_months' => 'nullable|integer|min:1|max:60',
            ];

            // Add cash_received validation only for cash payments
            if ($request->method === 'Tunai') {
                $validationRules['cash_received'] = 'required|numeric|min:1';
            } else {
                $validationRules['cash_received'] = 'nullable|numeric';
            }

            $request->validate($validationRules);

            // Get the order
            $order = $request->order_type === 'produk'
                ? OrderProduct::findOrFail($request->order_id)
                : OrderService::findOrFail($request->order_id);

            // Generate payment ID
            $date = now()->format('dmY');
            $lastPayment = PaymentDetail::whereDate('created_at', today())
                ->orderBy('payment_id', 'desc')
                ->first();

            $sequence = '001';
            if ($lastPayment) {
                $lastSequence = substr($lastPayment->payment_id, -3);
                $sequence = str_pad((int)$lastSequence + 1, 3, '0', STR_PAD_LEFT);
            }

            $paymentId = "PYM{$date}{$sequence}";

            // Create payment instance
            $payment = new PaymentDetail([
                'payment_id' => $paymentId,
                'order_type' => $request->order_type,
                'name' => 'admin',
                'method' => $request->method,
                'amount' => $request->amount,
                'cash_received' => $request->method === 'Tunai' ? $request->cash_received : null,
                'payment_type' => $request->payment_type,
                'status' => 'pending', // Start as pending until validation passes
            ]);

            // Set the appropriate order relationship
            if ($request->order_type === 'produk') {
                $payment->order_product_id = $request->order_id;
            } else {
                $payment->order_service_id = $request->order_id;
            }

            // Validate payment business rules
            $validationErrors = $payment->validate();
            if (!empty($validationErrors)) {
                return back()
                    ->withInput()
                    ->with('error', implode(' ', $validationErrors));
            }

            // Handle file upload if provided
            if ($request->hasFile('proof_photo')) {
                $payment->proof_photo = $this->handleImageUpload($request->file('proof_photo'), $paymentId);
            }

            // Set payment as dibayar after all validations pass
            $payment->status = 'dibayar';

            // Save the payment
            DB::beginTransaction();
            try {
                $payment->save();

                // Update warranty information if provided and payment is successful
                if ($payment->status === 'dibayar' && $request->warranty_period_months) {
                    $warrantyMonths = (int) $request->warranty_period_months;
                    $order->warranty_period_months = $warrantyMonths;
                    $order->warranty_expired_at = now()->addMonths($warrantyMonths);
                    $order->save();
                }

                // Create notification for payment received
                if ($payment->status === 'dibayar') {
                    $this->createPaymentNotification($payment, NotificationType::PAYMENT_RECEIVED);
                }

                DB::commit();

                // Redirect to payment details with success message
                return redirect()
                    ->route('payments.show', $payment)
                    ->with('success', 'Pembayaran berhasil disimpan.');
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan pembayaran: ' . $e->getMessage());
        }
    }

    public function edit($payment_id)
    {
        $payment = PaymentDetail::where('payment_id', $payment_id)->firstOrFail();
        return view('admin.payment.edit', compact('payment'));
    }

    public function update(Request $request, $payment_id)
    {
        try {
            // Use PaymentDetail constants for validation
            $validationRules = [
                'method' => 'required|in:' . implode(',', array_keys(PaymentDetail::PAYMENT_METHODS)),
                'amount' => 'required|numeric|min:1',
                'status' => 'required|in:' . implode(',', array_keys(PaymentDetail::PAYMENT_STATUSES)),
                'payment_type' => 'required|in:' . implode(',', array_keys(PaymentDetail::PAYMENT_TYPES)),
                'proof_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
                'change_returned' => 'nullable|numeric|min:0',
                'warranty_period_months' => 'nullable|integer|min:1|max:60',
            ];

            // Add cash_received validation only for cash payments
            if ($request->method === 'Tunai') {
                $validationRules['cash_received'] = 'required|numeric|min:1';
            } else {
                $validationRules['cash_received'] = 'nullable|numeric';
            }

            // Custom validation messages
            $validationMessages = [
                'method.required' => 'Metode pembayaran harus dipilih.',
                'method.in' => 'Metode pembayaran tidak valid.',
                'amount.required' => 'Jumlah pembayaran harus diisi.',
                'amount.numeric' => 'Jumlah pembayaran harus berupa angka.',
                'amount.min' => 'Jumlah pembayaran minimal 1.',
                'status.required' => 'Status pembayaran harus dipilih.',
                'status.in' => 'Status pembayaran tidak valid.',
                'payment_type.required' => 'Tipe pembayaran harus dipilih.',
                'payment_type.in' => 'Tipe pembayaran tidak valid.',
                'proof_photo.image' => 'File bukti pembayaran harus berupa gambar.',
                'proof_photo.mimes' => 'Format file harus JPG, JPEG, atau PNG.',
                'proof_photo.max' => 'Ukuran file maksimal 2MB.',
                'change_returned.numeric' => 'Kembalian harus berupa angka.',
                'change_returned.min' => 'Kembalian tidak boleh negatif.',
                'warranty_period_months.integer' => 'Masa garansi harus berupa angka bulat.',
                'warranty_period_months.min' => 'Masa garansi minimal 1 bulan.',
                'warranty_period_months.max' => 'Masa garansi maksimal 60 bulan.',
            ];

            // Add cash_received messages only for cash payments
            if ($request->method === 'Tunai') {
                $validationMessages['cash_received.required'] = 'Uang diterima harus diisi untuk pembayaran tunai.';
                $validationMessages['cash_received.numeric'] = 'Uang diterima harus berupa angka.';
                $validationMessages['cash_received.min'] = 'Uang diterima minimal 1.';
            } else {
                $validationMessages['cash_received.numeric'] = 'Uang diterima harus berupa angka.';
            }

            $request->validate($validationRules, $validationMessages);

            // Additional validation for cash payments
            if ($request->method === 'Tunai' && $request->change_returned !== null && $request->change_returned < 0) {
                return back()
                    ->withInput()
                    ->withErrors(['change_returned' => 'Kembalian tidak boleh bernilai negatif.']);
            }

            $payment = PaymentDetail::where('payment_id', $payment_id)->firstOrFail();

            // Handle image deletion request
            if ($request->has('delete_current_image') && $payment->proof_photo) {
                // Check if it's the new format (private storage path) or legacy (public storage)
                if (str_contains($payment->proof_photo, '/')) {
                    // New format: private storage path
                    if (Storage::exists($payment->proof_photo)) {
                        Storage::delete($payment->proof_photo);
                    }
                } else {
                    // Legacy format: public storage filename only
                    $oldImagePath = public_path('images/payment/' . $payment->proof_photo);
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }
                }
                $payment->proof_photo = null;
            }
            // Handle new file upload
            elseif ($request->hasFile('proof_photo')) {
                // Delete old photo if exists
                if ($payment->proof_photo) {
                    // Check if it's the new format (private storage path) or legacy (public storage)
                    if (str_contains($payment->proof_photo, '/')) {
                        // New format: private storage path
                        if (Storage::exists($payment->proof_photo)) {
                            Storage::delete($payment->proof_photo);
                        }
                    } else {
                        // Legacy format: public storage filename only
                        $oldImagePath = public_path('images/payment/' . $payment->proof_photo);
                        if (File::exists($oldImagePath)) {
                            File::delete($oldImagePath);
                        }
                    }
                }

                $payment->proof_photo = $this->handleImageUpload($request->file('proof_photo'), $payment->payment_id);
            }

            // Update payment record
            $payment->method = $request->method;
            $payment->amount = $request->amount;
            $payment->cash_received = $request->method === 'Tunai' ? $request->cash_received : null;
            $payment->status = $request->status;
            $payment->payment_type = $request->payment_type;

            // Set change_returned only for cash payments
            if ($request->method === 'Tunai') {
                $payment->change_returned = $request->change_returned ?? 0;
            } else {
                $payment->change_returned = null;
            }

            $oldStatus = $payment->status;
            $payment->save();

            // Get the related order
            if ($payment->order_type === 'produk') {
                $order = OrderProduct::where('order_product_id', $payment->order_product_id)->first();
            } else {
                $order = OrderService::where('order_service_id', $payment->order_service_id)->first();
            }

            // Update warranty information if provided and payment is successful
            if ($order && $payment->status === 'dibayar' && $request->warranty_period_months) {
                $warrantyMonths = (int) $request->warranty_period_months;
                $order->warranty_period_months = $warrantyMonths;
                $order->warranty_expired_at = now()->addMonths($warrantyMonths);
                $order->save();
            }

            // Auto-update payment status for related order
            if ($order) {
                $order->updatePaymentStatus();
            }

            // Create notification if payment status changed
            if ($oldStatus !== $payment->status) {
                if ($payment->status === 'dibayar') {
                    $this->createPaymentNotification($payment, NotificationType::PAYMENT_RECEIVED);
                } elseif ($payment->status === 'gagal') {
                    $this->createPaymentNotification($payment, NotificationType::PAYMENT_FAILED);
                }
            }

            return redirect()
                ->route('payments.show', $payment)
                ->with('success', 'Payment berhasil diupdate.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors specifically
            Log::error('Payment update validation failed: ' . json_encode($e->errors()));
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            // Log the full error for debugging
            Log::error('Payment update failed: ' . $e->getMessage(), [
                'payment_id' => $payment_id,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->withErrors(['general' => 'Terjadi kesalahan saat menyimpan pembayaran: ' . $e->getMessage()]);
        }
    }

    /**
     * Cancel a payment and update related order status
     */
    public function cancel($payment_id)
    {
        try {
            // Find and update payment status
            PaymentDetail::where('payment_id', $payment_id)
                ->update(['status' => 'gagal']);

            $payment = PaymentDetail::where('payment_id', $payment_id)->firstOrFail();

            // Auto-update payment status for related order
            if ($payment->order_type === 'produk') {
                $order = OrderProduct::where('order_product_id', $payment->order_product_id)->first();
                if ($order) {
                    $order->updatePaymentStatus();
                }
            } else {
                $order = OrderService::where('order_service_id', $payment->order_service_id)->first();
                if ($order) {
                    $order->updatePaymentStatus();
                }
            }

            // Create notification for payment failure
            $this->createPaymentNotification($payment, NotificationType::PAYMENT_FAILED);

            return redirect()
                ->back()
                ->with('success', 'Pembayaran berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan saat membatalkan pembayaran. ' . $e->getMessage());
        }
    }

    /**
     * Cancel/destroy a payment (same as cancel method for consistency)
     */
    public function destroy($payment_id)
    {
        return $this->cancel($payment_id);
    }

    /**
     * Handle image upload with compression and organized naming
     * Now saves to private storage like Customer payments
     */
    private function handleImageUpload($file, $paymentId)
    {
        try {
            // Validate file
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                throw new \Exception('Tipe file tidak didukung. Gunakan JPG, JPEG, atau PNG.');
            }

            // Check file size (max 2MB)
            if ($file->getSize() > 2 * 1024 * 1024) {
                throw new \Exception('Ukuran file terlalu besar. Maksimal 2MB.');
            }

            // Generate filename: img-{payment_id}.{extension}
            $fileName = 'img-' . $paymentId . '.' . $file->getClientOriginalExtension();

            // Save to private storage like Customer payments
            $proofPath = $file->storeAs('private/payments', $fileName);

            return $proofPath;
        } catch (\Exception $e) {
            throw new \Exception('Gagal mengunggah gambar: ' . $e->getMessage());
        }
    }

    /**
     * Delete payment image
     * Now works with both private storage (new) and public storage (legacy)
     */
    public function deleteImage($payment_id)
    {
        try {
            $payment = PaymentDetail::where('payment_id', $payment_id)->firstOrFail();

            if ($payment->proof_photo) {
                // Check if it's the new format (private storage path)
                if (str_contains($payment->proof_photo, '/')) {
                    // New format: private storage path
                    if (Storage::exists($payment->proof_photo)) {
                        Storage::delete($payment->proof_photo);
                    }
                } else {
                    // Legacy format: public storage filename only
                    $imagePath = public_path('images/payment/' . $payment->proof_photo);
                    if (File::exists($imagePath)) {
                        File::delete($imagePath);
                    }
                }

                $payment->proof_photo = null;
                $payment->save();
            }

            return response()->json(['success' => true, 'message' => 'Gambar berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus gambar: ' . $e->getMessage()]);
        }
    }

    /**
     * Serve private payment images with proper authorization
     */
    public function servePrivateImage($payment_id)
    {
        try {
            // Find the payment
            $payment = PaymentDetail::where('payment_id', $payment_id)->firstOrFail();

            // Check if payment has a proof photo
            if (!$payment->proof_photo) {
                abort(404, 'Image not found');
            }

            // Check if it's a private storage file (contains /)
            if (!str_contains($payment->proof_photo, '/')) {
                // Legacy public file - redirect to public URL
                return redirect(asset('images/payment/' . $payment->proof_photo));
            }

            // Check if file exists in private storage
            if (!Storage::exists($payment->proof_photo)) {
                abort(404, 'Image file not found');
            }

            // Get file contents and mime type
            $fileContents = Storage::get($payment->proof_photo);
            $mimeType = Storage::mimeType($payment->proof_photo);

            // Return the file with proper headers
            return response($fileContents)
                ->header('Content-Type', $mimeType)
                ->header('Cache-Control', 'public, max-age=3600')
                ->header('Content-Disposition', 'inline; filename="' . basename($payment->proof_photo) . '"');
        } catch (\Exception $e) {
            Log::error('Failed to serve private payment image: ' . $e->getMessage(), [
                'payment_id' => $payment_id
            ]);
            abort(404, 'Image not found');
        }
    }

    /**
     * Create payment notification for all admins
     */
    private function createPaymentNotification(PaymentDetail $payment, NotificationType $type)
    {
        try {
            // Get the related order
            $order = $payment->order_type === 'produk' ? $payment->orderProduct : $payment->orderService;

            if (!$order) {
                return;
            }

            // Get customer information
            $customer = $order->customer;

            // Prepare notification message
            $message = match ($type) {
                NotificationType::PAYMENT_RECEIVED => "Pembayaran diterima untuk {$payment->order_type} #{$order->getKey()}",
                NotificationType::PAYMENT_FAILED => "Pembayaran gagal untuk {$payment->order_type} #{$order->getKey()}",
                default => "Update pembayaran untuk {$payment->order_type} #{$order->getKey()}"
            };

            // Prepare notification data
            $data = [
                'payment_id' => $payment->payment_id,
                'order_id' => $order->getKey(),
                'order_type' => $payment->order_type,
                'customer_name' => $customer->name,
                'amount' => $payment->amount,
                'method' => $payment->method,
                'payment_type' => $payment->payment_type,
                'status' => $payment->status
            ];

            // Add device info for service orders
            if ($payment->order_type === 'servis' && isset($order->device)) {
                $data['device'] = $order->device;
            }

            // Create notifications for all admins
            $admins = Admin::all();
            foreach ($admins as $admin) {
                $this->notificationService->create(
                    notifiable: $admin,
                    type: $type,
                    subject: $payment,
                    message: $message,
                    data: $data
                );
            }
        } catch (\Exception $e) {
            // Log error but don't break the payment flow
            Log::error('Failed to create payment notification: ' . $e->getMessage());
        }
    }
}
