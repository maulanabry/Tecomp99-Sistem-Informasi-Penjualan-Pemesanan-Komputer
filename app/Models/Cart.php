<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * Model Cart untuk mengelola keranjang belanja pelanggan
 * 
 * @property int $id
 * @property string $customer_id
 * @property string $product_id
 * @property int $quantity
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Customer $customer
 * @property-read Product $product
 */
class Cart extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke model Customer
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    /**
     * Relasi ke model Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    /**
     * Hitung total harga untuk item ini (harga × kuantitas)
     */
    public function getTotalPriceAttribute(): int
    {
        return $this->product ? $this->product->price * $this->quantity : 0;
    }

    /**
     * Format total harga dengan pemisah ribuan
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    /**
     * Format harga satuan dengan pemisah ribuan
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        return $this->product ? 'Rp ' . number_format($this->product->price, 0, ',', '.') : 'Rp 0';
    }

    /**
     * Cek apakah produk masih tersedia dalam stok
     */
    public function getIsAvailableAttribute(): bool
    {
        return $this->product && $this->product->stock >= $this->quantity && $this->product->is_active;
    }

    /**
     * Dapatkan URL gambar produk
     */
    public function getProductImageUrlAttribute(): ?string
    {
        if (!$this->product || !$this->product->images->count()) {
            return null;
        }

        $mainImage = $this->product->images->where('is_main', true)->first()
            ?? $this->product->images->first();

        return $mainImage ? asset('images/products/' . $mainImage->image_path) : null;
    }

    /**
     * Tambah item ke keranjang atau update kuantitas jika sudah ada
     */
    public static function addItem(string $customerId, string $productId, int $quantity = 1): self
    {
        return self::updateOrCreate(
            [
                'customer_id' => $customerId,
                'product_id' => $productId,
            ],
            [
                'quantity' => DB::raw("quantity + {$quantity}"),
            ]
        );
    }

    /**
     * Update kuantitas item di keranjang
     */
    public function updateQuantity(int $quantity): bool
    {
        if ($quantity <= 0) {
            return $this->delete();
        }

        $this->quantity = $quantity;
        return $this->save();
    }

    /**
     * Dapatkan total item di keranjang untuk customer tertentu
     */
    public static function getTotalItemsForCustomer(string $customerId): int
    {
        return self::where('customer_id', $customerId)->sum('quantity');
    }

    /**
     * Dapatkan total harga keranjang untuk customer tertentu
     */
    public static function getTotalPriceForCustomer(string $customerId): int
    {
        return self::where('customer_id', $customerId)
            ->with('product')
            ->get()
            ->sum(function ($cartItem) {
                return $cartItem->total_price;
            });
    }

    /**
     * Hapus semua item keranjang untuk customer tertentu
     */
    public static function clearCartForCustomer(string $customerId): bool
    {
        return self::where('customer_id', $customerId)->delete();
    }

    /**
     * Dapatkan item keranjang yang dipilih
     */
    public static function getSelectedItems(string $customerId, array $selectedIds): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('customer_id', $customerId)
            ->whereIn('id', $selectedIds)
            ->with(['product', 'product.images', 'product.brand'])
            ->get();
    }
}
