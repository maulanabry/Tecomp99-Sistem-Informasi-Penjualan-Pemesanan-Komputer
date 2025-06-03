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
    public $promo_code = '';
    public $promoError = null;

    public $orderItems = [];
    public $subtotal = 0;
    public $grandTotal = 0;

    // Customer details properties
    public $selectedCustomer = null;
    public $customerAddress = null;

    // Modal properties
    public $showProductModal = false;
    public $searchQuery = '';
    public $selectedCategory = '';
    public $selectedBrand = '';
    public $filteredProducts = [];

    //Shipping
    public $shipper_destination_id = 69278; // Default to Manyar, Surabaya (id)

    protected $rules = [
        'customer_id' => 'required|exists:customers,customer_id',
        'order_type' => 'required|in:langsung,pengiriman',
        'payment_status' => 'required|in:belum_dibayar,down_payment,lunas,dibatalkan',
        'note' => 'nullable|string',
        'discount' => 'nullable|integer|min:0',
        'orderItems' => 'required|array|min:1',
        'orderItems.*.product_id' => 'required|exists:products,product_id',
        'orderItems.*.quantity' => 'required|integer|min:1',
        'promo_code' => 'nullable|string|max:50',
    ];

    protected $listeners = ['productSelected' => 'onProductSelected'];

    public function mount()
    {
        $this->customers = Customer::with('addresses')->get();
        $this->promos = Promo::where('is_active', true)->valid()->get();

        // Initialize customer details if customer_id is set
        if ($this->customer_id) {
            $this->selectedCustomer = collect($this->customers)->firstWhere('customer_id', $this->customer_id);
            $this->customerAddress = $this->selectedCustomer ? $this->selectedCustomer->addresses->first() : null;
        }
    }

    public function updatedCustomerId($value)
    {
        if ($value) {
            // Fetch fresh customer data with addresses to ensure we have the latest data
            $this->selectedCustomer = Customer::with('addresses')->find($value);

            if ($this->selectedCustomer) {
                // Filter addresses to ensure the customer_id matches
                if ($this->selectedCustomer->addresses) {
                    $this->customerAddress = $this->selectedCustomer->addresses
                        ->where('customer_id', $value)
                        ->where('is_default', true)
                        ->first();

                    // If no default address exists, get the first address for the customer
                    if (!$this->customerAddress) {
                        $this->customerAddress = $this->selectedCustomer->addresses
                            ->where('customer_id', $value)
                            ->first();
                    }
                } else {
                    $this->customerAddress = null;
                }

                // Notify the user about the address status
                $message = $this->customerAddress
                    ? 'Alamat pelanggan berhasil dimuat.'
                    : 'Pelanggan tidak memiliki alamat yang tersedia.';
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => $message,
                ]);
            } else {
                $this->selectedCustomer = null;
                $this->customerAddress = null;

                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Pelanggan tidak ditemukan.',
                ]);
            }
        } else {
            $this->selectedCustomer = null;
            $this->customerAddress = null;
        }
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
                }
            }
        }
        $this->calculateTotals();
    }

    public function updatedDiscount()
    {
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = collect($this->orderItems)->sum('total');
        $discount = $this->discount ?? 0;
        $this->grandTotal = max(0, $this->subtotal - $discount + $this->shipping_cost);
    }

    public function applyPromoCode()
    {
        $this->promoError = null;
        $this->discount = 0;
        $this->promo_id = null;

        $code = trim($this->promo_code);
        if (empty($code)) {
            $this->promoError = 'Kode promo tidak boleh kosong.';
            return;
        }

        $promo = Promo::where('code', $code)
            ->where('is_active', true)
            ->valid()
            ->first();

        if (!$promo) {
            $this->promoError = 'Kode promo tidak valid atau sudah tidak aktif.';
            return;
        }

        if ($promo->minimum_order_amount && $this->subtotal < $promo->minimum_order_amount) {
            $this->promoError = "Minimal pembelian untuk promo ini adalah Rp " . number_format($promo->minimum_order_amount, 0, ',', '.');
            return;
        }

        // Calculate discount based on promo type
        if ($promo->type === 'percentage' && $promo->discount_percentage) {
            $this->discount = intval(($this->subtotal * $promo->discount_percentage) / 100);
        } elseif ($promo->type === 'amount' && $promo->discount_amount) {
            $this->discount = $promo->discount_amount;
        } else {
            $this->discount = 0;
        }

        $this->promo_id = $promo->promo_id;
        $this->calculateTotals();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Promo berhasil diterapkan.'
        ]);
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
