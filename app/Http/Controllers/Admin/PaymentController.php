<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentDetail;
use App\Models\OrderService;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        // Ensure storage directory exists
        if (!Storage::disk('public')->exists('payment-proofs')) {
            Storage::disk('public')->makeDirectory('payment-proofs');
        }

        $orderProducts = OrderProduct::with('customer')
            ->whereIn('status_payment', ['belum_dibayar', 'down_payment'])
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->order_product_id,
                    'customer_name' => $order->customer->name,
                    'sub_total' => (float) $order->sub_total,
                    'discount_amount' => (float) $order->discount_amount,
                    'grand_total' => (float) $order->grand_total,
                    'payment_status' => $order->status_payment
                ];
            });

        $orderServices = OrderService::with('customer')
            ->whereIn('status_payment', ['belum_dibayar', 'down_payment'])
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->order_service_id,
                    'customer_name' => $order->customer->name,
                    'sub_total' => (float) $order->sub_total,
                    'discount_amount' => (float) $order->discount_amount,
                    'grand_total' => (float) $order->grand_total,
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
            $request->validate([
                'order_type' => 'required|in:produk,servis',
                'order_id' => 'required|string',
                'method' => 'required|in:Tunai,Bank BCA',
                'amount' => 'required|integer|min:1',
                'payment_type' => 'required|in:full,down_payment',
                'proof_photo' => 'nullable|image|max:2048', // max 2MB
            ]);

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

            // Handle file upload
            $proofPhotoPath = null;
            if ($request->hasFile('proof_photo')) {
                // Ensure directory exists
                if (!Storage::disk('public')->exists('payment-proofs')) {
                    Storage::disk('public')->makeDirectory('payment-proofs');
                }
                $proofPhotoPath = $request->file('proof_photo')->store('payment-proofs', 'public');
            }

            // Create payment record
            $payment = new PaymentDetail();
            $payment->payment_id = $paymentId;
            $payment->order_type = $request->order_type;
            $payment->name = 'admin';
            $payment->method = $request->method;
            $payment->amount = $request->amount;
            $payment->payment_type = $request->payment_type;
            $payment->status = 'dibayar';
            $payment->proof_photo = $proofPhotoPath;

            // Update order and set payment details
            if ($request->order_type === 'produk') {
                $order = OrderProduct::findOrFail($request->order_id);
                $payment->order_product_id = $request->order_id;
            } else {
                $order = OrderService::findOrFail($request->order_id);
                $payment->order_service_id = $request->order_id;
            }

            // Update order payment status
            $order->status_payment = $request->payment_type === 'full' ? 'lunas' : 'down_payment';
            $order->save();

            $payment->save();

            return redirect()
                ->route('payments.show', $payment)
                ->with('success', 'Pembayaran berhasil disimpan.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan pembayaran. ' . $e->getMessage());
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
            ]);

            $payment = PaymentDetail::where('payment_id', $payment_id)->firstOrFail();

            // Handle file upload
            if ($request->hasFile('proof_photo')) {
                // Delete old photo if exists
                if ($payment->proof_photo) {
                    Storage::delete($payment->proof_photo);
                }

                $payment->proof_photo = $request->file('proof_photo')->store('payment-proofs', 'public');
            }

            // Update payment record
            $payment->method = $request->method;
            $payment->amount = $request->amount;
            $payment->status = $request->status;
            $payment->payment_type = $request->payment_type;
            $payment->save();

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

            // Update related order's payment status to belum_dibayar
            if ($payment->order_type === 'produk') {
                $order = OrderProduct::where('order_product_id', $payment->order_product_id)->first();
                if ($order) {
                    $order->status_payment = 'belum_dibayar';
                    $order->save();
                }
            } else {
                $order = OrderService::where('order_service_id', $payment->order_service_id)->first();
                if ($order) {
                    $order->status_payment = 'belum_dibayar';
                    $order->save();
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
}
