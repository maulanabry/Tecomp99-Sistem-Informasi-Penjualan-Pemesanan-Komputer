<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentDetail;
use App\Models\OrderService;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;

class PaymentController extends Controller
{
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

    public function create()
    {
        // Pastikan direktori bukti pembayaran ada
        $paymentDir = public_path('images/payment');
        if (!File::exists($paymentDir)) {
            File::makeDirectory($paymentDir, 0755, true);
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

        // Ambil order servis yang statusnya belum dibayar atau down_payment saja
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
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Basic request validation
            $request->validate([
                'order_type' => 'required|in:produk,servis',
                'order_id' => 'required|string',
                'method' => 'required|in:' . implode(',', array_keys(PaymentDetail::PAYMENT_METHODS)),
                'amount' => 'required|numeric|min:1',
                'payment_type' => 'required|in:' . implode(',', array_keys(PaymentDetail::PAYMENT_TYPES)),
                'proof_photo' => 'nullable|image|max:2048',
                'warranty_period_months' => 'nullable|integer|min:1|max:60',
            ]);

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
            $request->validate([
                'method' => 'required|in:Tunai,Bank BCA',
                'amount' => 'required|integer|min:1',
                'status' => 'required|in:pending,dibayar,gagal',
                'payment_type' => 'required|in:full,down_payment',
                'proof_photo' => 'nullable|image|max:2048', // max 2MB
                'change_returned' => 'nullable|numeric|min:0',
                'warranty_period_months' => 'nullable|integer|min:1|max:60',
            ]);

            // Validate change_returned only for cash payments
            if ($request->method === 'Tunai' && $request->change_returned !== null && $request->change_returned < 0) {
                return back()
                    ->withInput()
                    ->with('error', 'Kembalian tidak boleh bernilai negatif.');
            }

            $payment = PaymentDetail::where('payment_id', $payment_id)->firstOrFail();

            // Handle image deletion request
            if ($request->has('delete_current_image') && $payment->proof_photo) {
                $oldImagePath = public_path('images/payment/' . $payment->proof_photo);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
                $payment->proof_photo = null;
            }
            // Handle new file upload
            elseif ($request->hasFile('proof_photo')) {
                // Delete old photo if exists
                if ($payment->proof_photo) {
                    $oldImagePath = public_path('images/payment/' . $payment->proof_photo);
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }
                }

                $payment->proof_photo = $this->handleImageUpload($request->file('proof_photo'), $payment->payment_id);
            }

            // Update payment record
            $payment->method = $request->method;
            $payment->amount = $request->amount;
            $payment->status = $request->status;
            $payment->payment_type = $request->payment_type;

            // Set change_returned only for cash payments
            if ($request->method === 'Tunai') {
                $payment->change_returned = $request->change_returned ?? 0;
            } else {
                $payment->change_returned = null;
            }

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

            return redirect()
                ->route('payments.show', $payment)
                ->with('success', 'Payment berhasil diupdate.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan pembayaran. ' . $e->getMessage());
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

            // Create payment directory if not exists
            $paymentDir = public_path('images/payment');
            if (!File::exists($paymentDir)) {
                File::makeDirectory($paymentDir, 0755, true);
            }

            // Generate filename: PYMXXX-img.jpg
            $filename = $paymentId . '-img.jpg';
            $imagePath = $paymentDir . '/' . $filename;

            // Process and compress image using Intervention Image
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);

            // Resize if too large (max width/height: 1200px)
            if ($image->width() > 1200 || $image->height() > 1200) {
                $image->scale(width: 1200, height: 1200);
            }

            // Save with compression (quality 80%)
            $image->toJpeg(80)->save($imagePath);

            return $filename;
        } catch (\Exception $e) {
            throw new \Exception('Gagal mengunggah gambar: ' . $e->getMessage());
        }
    }

    /**
     * Delete payment image
     */
    public function deleteImage($payment_id)
    {
        try {
            $payment = PaymentDetail::where('payment_id', $payment_id)->firstOrFail();

            if ($payment->proof_photo) {
                $imagePath = public_path('images/payment/' . $payment->proof_photo);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }

                $payment->proof_photo = null;
                $payment->save();
            }

            return response()->json(['success' => true, 'message' => 'Gambar berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus gambar: ' . $e->getMessage()]);
        }
    }
}
