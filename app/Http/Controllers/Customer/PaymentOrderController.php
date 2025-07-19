<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use App\Models\OrderService;
use App\Models\PaymentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Controller untuk mengelola halaman payment order pelanggan
 */
class PaymentOrderController extends Controller
{
    /**
     * Tampilkan halaman payment order
     */
    public function show($orderId)
    {
        // Pastikan customer sudah login
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login')
                ->with('message', 'Silakan login terlebih dahulu.');
        }

        $customerId = Auth::guard('customer')->id();

        // Coba cari di order product terlebih dahulu
        $order = OrderProduct::where('order_product_id', $orderId)
            ->where('customer_id', $customerId)
            ->with(['customer', 'items.product.images', 'items.product.brand', 'shipping'])
            ->first();

        // Jika tidak ditemukan, cari di order service
        if (!$order) {
            $order = OrderService::where('order_service_id', $orderId)
                ->where('customer_id', $customerId)
                ->with(['customer', 'serviceItems.service'])
                ->first();
        }

        if (!$order) {
            return redirect()->route('customer.orders.products')
                ->with('error', 'Pesanan tidak ditemukan.');
        }

        return view('customer.payment-order', compact('order'));
    }

    /**
     * Proses pembayaran
     */
    public function store(Request $request)
    {
        // Pastikan customer sudah login
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login')
                ->with('message', 'Silakan login terlebih dahulu.');
        }

        // Validasi input
        $request->validate([
            'order_id' => 'required|string',
            'order_type' => 'required|in:produk,servis',
            'payment_method' => 'required|string|max:255',
            'payment_option' => 'required|string|max:255',
            'sender_name' => 'required|string|max:255',
            'transfer_amount' => 'required|numeric|min:1',
            'payment_proof' => 'required|image|mimes:jpeg,jpg,png|max:2048', // 2MB max
        ], [
            'order_id.required' => 'ID pesanan diperlukan.',
            'order_type.required' => 'Tipe pesanan diperlukan.',
            'order_type.in' => 'Tipe pesanan tidak valid.',
            'payment_method.required' => 'Metode pembayaran harus dipilih.',
            'payment_option.required' => 'Pilihan pembayaran harus dipilih.',
            'sender_name.required' => 'Nama pengirim harus diisi.',
            'transfer_amount.required' => 'Nominal transfer harus diisi.',
            'transfer_amount.numeric' => 'Nominal transfer harus berupa angka.',
            'transfer_amount.min' => 'Nominal transfer minimal 1.',
            'payment_proof.required' => 'Bukti transfer harus diupload.',
            'payment_proof.image' => 'File harus berupa gambar.',
            'payment_proof.mimes' => 'Format file harus JPG, JPEG, atau PNG.',
            'payment_proof.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $customerId = Auth::guard('customer')->id();

        // Cari order berdasarkan tipe
        if ($request->order_type === 'produk') {
            $order = OrderProduct::where('order_product_id', $request->order_id)
                ->where('customer_id', $customerId)
                ->first();
        } else {
            $order = OrderService::where('order_service_id', $request->order_id)
                ->where('customer_id', $customerId)
                ->first();
        }

        if (!$order) {
            return redirect()->back()
                ->with('error', 'Pesanan tidak ditemukan.');
        }

        // Cek apakah pesanan masih bisa dibayar
        if ($order->status_payment === 'lunas') {
            return redirect()->back()
                ->with('error', 'Pesanan sudah lunas.');
        }

        try {
            // Generate payment ID with format pay-DDMMYY-XXX
            $paymentId = $this->generatePaymentId();

            // Upload bukti pembayaran ke private storage
            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $fileName = 'img-' . $paymentId . '.' . $file->getClientOriginalExtension();
                // Save to storage/app/private/payments/img-{payment_id}
                $proofPath = $file->storeAs('private/payments', $fileName);
            }

            // Buat record pembayaran
            $paymentDetail = PaymentDetail::create([
                'payment_id' => $paymentId,
                'order_product_id' => $request->order_type === 'produk' ? $request->order_id : null,
                'order_service_id' => $request->order_type === 'servis' ? $request->order_id : null,
                'method' => $request->payment_method . ' - ' . $request->payment_option,
                'amount' => $request->transfer_amount,
                'name' => $request->sender_name,
                'status' => 'menunggu',
                'payment_type' => 'full', // Bisa disesuaikan jika ada opsi DP
                'order_type' => $request->order_type,
                'proof_photo' => $proofPath,
            ]);

            // Redirect to order detail page after successful payment
            if ($request->order_type === 'produk') {
                return redirect()->route('customer.orders.products.show', $request->order_id)
                    ->with('success', 'Bukti pembayaran berhasil dikirim! Silakan tunggu konfirmasi dari admin.');
            } else {
                return redirect()->route('customer.orders.services.show', $request->order_id)
                    ->with('success', 'Bukti pembayaran berhasil dikirim! Silakan tunggu konfirmasi dari admin.');
            }
        } catch (\Exception $e) {
            // Hapus file jika ada error
            if ($proofPath && Storage::exists($proofPath)) {
                Storage::delete($proofPath);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Generate payment ID with format PAYDDMMYY001
     */
    private function generatePaymentId()
    {
        $date = now()->format('dmy'); // DDMMYY format
        $prefix = 'PAY' . $date;

        // Get the last payment ID for today
        $lastPayment = PaymentDetail::where('payment_id', 'like', $prefix . '%')
            ->orderBy('payment_id', 'desc')
            ->first();

        if ($lastPayment) {
            // Extract the increment number and add 1
            $lastIncrement = (int) substr($lastPayment->payment_id, -3);
            $newIncrement = $lastIncrement + 1;
        } else {
            // First payment of the day
            $newIncrement = 1;
        }

        // Format increment as 3-digit number (001, 002, etc.)
        $incrementFormatted = str_pad($newIncrement, 3, '0', STR_PAD_LEFT);

        return $prefix . $incrementFormatted;
    }
}
