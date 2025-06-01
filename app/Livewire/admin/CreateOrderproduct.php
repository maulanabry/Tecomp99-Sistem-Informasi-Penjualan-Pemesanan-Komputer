<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Promo;
use App\Models\OrderProduct;
use App\Models\OrderProductItem;
use Illuminate\Support\Facades\DB;

class CreateOrderProduct extends Component
{
    public $customers = [];
    public $promos = [];
    public $customer_id = null;
    public $order_type = 'langsung';
    public $payment_status = 'belum_dibayar';
    public $note = '';
    public $shipping_cost = 0;
    public $promo_id = null;
    public $discount = 0;

    public $orderItems = [];
    public $subtotal = 0;
    public $grandTotal = 0;

    // Modal properties
    public $showProductModal = false;
    public $searchQuery = '';
    public $selectedCategory = '';
    public $selectedBrand = '';
    public $filteredProducts = [];

    protected $rules = [
        'customer_id' => 'required|exists:customers,customer_id',
        'order_type' => 'required|in:langsung,pengiriman',
        'payment_status' => 'required|in:belum_dibayar,down_payment,lunas,dibatalkan',
        'note' => 'nullable|string',
        'discount' => 'nullable|integer|min:0',
        'orderItems' => 'required|array|min:1',
        'orderItems.*.product_id' => 'required|exists:products,product_id',
        'orderItems.*.quantity' => 'required|integer|min:1',
    ];

    protected $listeners = ['productSelected' => 'onProductSelected'];

    public function mount()
    {
        $this->customers = Customer::with('addresses')->get();
        $this->promos = Promo::where('is_active', true)->valid()->get();
    }

    public function openProductModal()
    {
        $this->dispatch('openModal');
    }

    public function onProductSelected($productId)
    {
        // Check if product already exists in order items
        $exists = collect($this->orderItems)->contains('product_id', $productId);
        if ($exists) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Produk sudah ada dalam daftar'
            ]);
            return;
        }

        $product = Product::find($productId);
        if ($product && $product->is_active && $product->stock > 5) {
            $item = new \stdClass();
            $item->product_id = $product->product_id;
            $item->name = $product->name;
            $item->weight = $product->weight;
            $item->unit_price = $product->price;
            $item->quantity = 1;
            $item->total = $product->price;

            $this->orderItems[] = $item;
            $this->calculateTotals();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Produk berhasil ditambahkan'
            ]);
        }
    }

    public function removeItem($index)
    {
        unset($this->orderItems[$index]);
        $this->orderItems = array_values($this->orderItems);
        $this->calculateTotals();
    }

    public function addProduct($productId)
    {
        // Check if product already exists in order items
        $exists = collect($this->orderItems)->contains('product_id', $productId);
        if ($exists) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Produk sudah ada dalam daftar'
            ]);
            return;
        }

        $product = Product::find($productId);
        if ($product && $product->is_active && $product->stock > 5) {
            $item = new \stdClass();
            $item->product_id = $product->product_id;
            $item->name = $product->name;
            $item->weight = $product->weight;
            $item->unit_price = $product->price;
            $item->quantity = 1;
            $item->total = $product->price;

            $this->orderItems[] = $item;
            $this->calculateTotals();
            $this->closeProductModal();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Produk berhasil ditambahkan'
            ]);
        }
    }

    public function updatedOrderItems($value, $key)
    {
        if (str_contains($key, 'quantity')) {
            $parts = explode('.', $key);
            if (isset($parts[1])) {
                $index = $parts[1];
                if (isset($this->orderItems[$index]->unit_price)) {
                    $quantity = max(1, intval($value));
                    $this->orderItems[$index]->quantity = $quantity;
                    $this->orderItems[$index]->total = $this->orderItems[$index]->unit_price * $quantity;
                    $this->calculateTotals();
                }
            }
        }
    }

    public function updatedDiscount()
    {
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = collect($this->orderItems)->sum('total');
        $discount = $this->discount ?? 0;
        $this->grandTotal = max(0, $this->subtotal - $discount);
    }

    public function submit()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            // Generate order_product_id
            $dateCode = now()->format('dmy');
            $lastOrder = OrderProduct::where('order_product_id', 'like', "OPRD{$dateCode}%")
                ->orderBy('order_product_id', 'desc')
                ->first();

            if ($lastOrder) {
                $lastIncrement = intval(substr($lastOrder->order_product_id, -3));
                $newIncrement = str_pad($lastIncrement + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newIncrement = '001';
            }

            $orderProductId = "OPRD{$dateCode}{$newIncrement}";

            $orderProduct = OrderProduct::create([
                'order_product_id' => $orderProductId,
                'customer_id' => $this->customer_id,
                'status_order' => 'menunggu',
                'status_payment' => $this->payment_status,
                'sub_total' => $this->subtotal,
                'discount_amount' => $this->discount,
                'grand_total' => $this->grandTotal,
                'type' => $this->order_type,
                'note' => $this->note,
            ]);

            foreach ($this->orderItems as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    OrderProductItem::create([
                        'order_product_id' => $orderProductId,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $product->price,
                        'item_total' => $product->price * $item->quantity,
                    ]);
                }
            }

            DB::commit();

            session()->flash('success', 'Pesanan berhasil dibuat.');
            return redirect()->route('order-products.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('submit', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.create-orderproduct');
    }
}
