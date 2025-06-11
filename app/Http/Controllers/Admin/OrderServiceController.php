<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderService;
use Illuminate\Http\Request;

class OrderServiceController extends Controller
{
    public function index()
    {
        return view('admin.order-service');
    }

    public function show(OrderService $orderService)
    {
        $orderService->load(['customer.addresses', 'tickets', 'items']);
        return view('admin.order-service.show', compact('orderService'));
    }

    public function create()
    {
        $customers = \App\Models\Customer::select('customer_id', 'name', 'email', 'contact')
            ->with('addresses')
            ->get();
        return view('admin.order-service.create-order-service', compact('customers'));
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
            $date = now()->format('dmy');
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
                'grand_total_amount' => 0,
                'discount_amount' => 0,
            ]);

            // Increment service_orders_count for the customer
            $customer->increment('service_orders_count');

            return redirect()->route('order-services.show', $orderService)
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
        return view('admin.order-service.edit', compact('orderService'));
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
        ]);

        try {
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
                'grand_total_amount' => $grandTotal,
                'hasDevice' => $request->boolean('hasDevice'),
            ]);

            return redirect()->route('order-services.show', $orderService)
                ->with('success', 'Order servis berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal memperbarui order servis: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(OrderService $orderService)
    {
        try {
            if ($orderService->status_payment === 'lunas') {
                throw new \Exception('Order yang sudah lunas tidak dapat dihapus');
            }

            $orderService->delete();
            return redirect()->route('order-services.index')
                ->with('success', 'Order servis berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('order-services.index')
                ->with('error', 'Gagal menghapus order servis: ' . $e->getMessage());
        }
    }

    public function recovery()
    {
        return view('admin.order-service.recovery');
    }

    public function restore($id)
    {
        try {
            OrderService::withTrashed()->findOrFail($id)->restore();
            return redirect()->route('order-services.recovery')
                ->with('success', 'Order servis berhasil dipulihkan.');
        } catch (\Exception $e) {
            return redirect()->route('order-services.recovery')
                ->with('error', 'Gagal memulihkan order servis: ' . $e->getMessage());
        }
    }
}
