<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;
use App\Models\Service;
use App\Models\Customer;
use App\Models\OrderProduct;
use App\Models\OrderService;
use Illuminate\Support\Collection;

class GlobalSearch extends Component
{
    public $query = '';
    public $searchResults = [];
    public $showResults = false;

    public function updatedQuery()
    {
        if (strlen($this->query) < 2) {
            $this->searchResults = [];
            $this->showResults = false;
            return;
        }

        $this->searchResults = $this->performSearch();
        $this->showResults = true;
    }

    private function performSearch(): array
    {
        $results = [];
        $query = '%' . $this->query . '%';

        // Search Products
        $products = Product::where('name', 'like', $query)
            ->orWhere('product_id', 'like', $query)
            ->limit(3)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->product_id,
                    'title' => $product->name,
                    'subtitle' => 'ID: ' . $product->product_id . ' - Stok: ' . $product->stock,
                    'type' => 'product',
                    'url' => route('products.show', $product->product_id)
                ];
            });
        if ($products->isNotEmpty()) {
            $results['Produk'] = $products;
        }

        // Search Services
        $services = Service::where('name', 'like', $query)
            ->orWhere('service_id', 'like', $query)
            ->limit(3)
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->service_id,
                    'title' => $service->name,
                    'subtitle' => 'ID: ' . $service->service_id . ' - Harga: Rp ' . number_format($service->price, 0, ',', '.'),
                    'type' => 'service',
                    'url' => route('services.show', $service->service_id)
                ];
            });
        if ($services->isNotEmpty()) {
            $results['Layanan'] = $services;
        }

        // Search Customers
        $customers = Customer::where('name', 'like', $query)
            ->orWhere('contact', 'like', $query)
            ->orWhere('customer_id', 'like', $query)
            ->limit(3)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->customer_id,
                    'title' => $customer->name,
                    'subtitle' => $customer->contact ?: 'Tidak ada kontak',
                    'type' => 'customer',
                    'url' => route('customers.show', $customer->customer_id)
                ];
            });
        if ($customers->isNotEmpty()) {
            $results['Pelanggan'] = $customers;
        }

        // Search Order Products
        $orderProducts = OrderProduct::where('order_product_id', 'like', $query)
            ->orWhereHas('customer', function ($q) use ($query) {
                $q->where('name', 'like', $query);
            })
            ->limit(3)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->order_product_id,
                    'title' => 'Pesanan #' . $order->order_product_id,
                    'subtitle' => 'Status: ' . $order->status_order . ' - ' . $order->customer->name,
                    'type' => 'order_product',
                    'url' => route('order-products.show', $order->order_product_id)
                ];
            });
        if ($orderProducts->isNotEmpty()) {
            $results['Pesanan Produk'] = $orderProducts;
        }

        // Search Service Orders
        $serviceOrders = OrderService::where('order_service_id', 'like', $query)
            ->orWhereHas('customer', function ($q) use ($query) {
                $q->where('name', 'like', $query);
            })
            ->orWhere('device', 'like', $query)
            ->limit(3)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->order_service_id,
                    'title' => 'Servis #' . $order->order_service_id,
                    'subtitle' => 'Status: ' . $order->status_order . ' - ' . $order->customer->name,
                    'type' => 'order_service',
                    'url' => route('order-services.show', $order->order_service_id)
                ];
            });
        if ($serviceOrders->isNotEmpty()) {
            $results['Pesanan Servis'] = $serviceOrders;
        }

        return $results;
    }

    public function render()
    {
        return view('livewire.admin.global-search');
    }
}
