<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Product;
use App\Models\Service;
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
        $products = Product::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', $query)
                    ->orWhere('description', 'like', $query);
            })
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('name', 'like', $query);
            })
            ->orWhereHas('brand', function ($q) use ($query) {
                $q->where('name', 'like', $query);
            })
            ->limit(5)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->product_id,
                    'title' => $product->name,
                    'subtitle' => 'Kategori: ' . ($product->category->name ?? 'Tidak ada') .
                        ' | Brand: ' . ($product->brand->name ?? 'Tidak ada') .
                        ' | Rp ' . number_format($product->price, 0, ',', '.'),
                    'type' => 'product',
                    'url' => route('product.overview', $product->slug)
                ];
            });
        if ($products->isNotEmpty()) {
            $results['🛍️ Produk'] = $products;
        }

        // Search Services
        $services = Service::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', $query)
                    ->orWhere('description', 'like', $query);
            })
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('name', 'like', $query);
            })
            ->limit(5)
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->service_id,
                    'title' => $service->name,
                    'subtitle' => 'Kategori: ' . ($service->category->name ?? 'Tidak ada') .
                        ' | Rp ' . number_format($service->price, 0, ',', '.'),
                    'type' => 'service',
                    'url' => route('service.overview', $service->slug)
                ];
            });
        if ($services->isNotEmpty()) {
            $results['🔧 Layanan Servis'] = $services;
        }

        // Search Pages/Routes
        $pages = $this->searchPages();
        if (!empty($pages)) {
            $results['📄 Halaman'] = $pages;
        }

        return $results;
    }

    private function searchPages(): array
    {
        $pages = [
            [
                'name' => 'Beranda',
                'url' => route('home'),
                'keywords' => ['beranda', 'home', 'utama', 'depan', 'awal'],
                'description' => 'Halaman utama website'
            ],
            [
                'name' => 'Produk',
                'url' => route('products.public'),
                'keywords' => ['produk', 'product', 'barang', 'jual', 'beli', 'laptop', 'komputer'],
                'description' => 'Katalog produk komputer dan laptop'
            ],
            [
                'name' => 'Layanan Servis',
                'url' => route('services.public'),
                'keywords' => ['servis', 'service', 'layanan', 'perbaikan', 'repair', 'maintenance'],
                'description' => 'Layanan perbaikan komputer dan laptop'
            ],
            [
                'name' => 'Tentang Kami',
                'url' => route('tentang-kami'),
                'keywords' => ['tentang', 'about', 'profil', 'perusahaan', 'alamat', 'kontak', 'lokasi', 'kantor', 'info'],
                'description' => 'Informasi tentang perusahaan, alamat, dan kontak'
            ],
            [
                'name' => 'Lacak Pesanan',
                'url' => route('tracking.search'),
                'keywords' => ['lacak', 'tracking', 'pesanan', 'order', 'cek', 'status', 'pengiriman', 'resi'],
                'description' => 'Lacak status pesanan produk atau servis'
            ]
        ];

        // Add authenticated customer pages
        if (auth('customer')->check()) {
            $customerPages = [
                [
                    'name' => 'Pesan Servis Onsite',
                    'url' => route('customer.service-order'),
                    'keywords' => ['pesan', 'servis', 'onsite', 'panggil', 'rumah', 'kantor', 'teknisi'],
                    'description' => 'Pesan layanan servis di lokasi Anda'
                ],
                [
                    'name' => 'Keranjang Belanja',
                    'url' => route('customer.cart.index'),
                    'keywords' => ['keranjang', 'cart', 'belanja', 'beli', 'checkout'],
                    'description' => 'Keranjang belanja Anda'
                ],
                [
                    'name' => 'Pesanan Produk',
                    'url' => route('customer.orders.products'),
                    'keywords' => ['pesanan', 'produk', 'order', 'history', 'riwayat', 'pembelian'],
                    'description' => 'Riwayat pesanan produk Anda'
                ],
                [
                    'name' => 'Pesanan Servis',
                    'url' => route('customer.orders.services'),
                    'keywords' => ['pesanan', 'servis', 'order', 'history', 'riwayat', 'layanan'],
                    'description' => 'Riwayat pesanan servis Anda'
                ],
                [
                    'name' => 'Profil Akun',
                    'url' => route('customer.account.profile'),
                    'keywords' => ['profil', 'akun', 'profile', 'account', 'data', 'pribadi'],
                    'description' => 'Kelola profil dan data pribadi'
                ],
                [
                    'name' => 'Alamat Pengiriman',
                    'url' => route('customer.account.addresses'),
                    'keywords' => ['alamat', 'address', 'pengiriman', 'delivery', 'lokasi'],
                    'description' => 'Kelola alamat pengiriman'
                ]
            ];
            $pages = array_merge($pages, $customerPages);
        }

        $matchedPages = [];
        $searchTerm = strtolower($this->query);

        foreach ($pages as $page) {
            $nameMatch = stripos($page['name'], $searchTerm) !== false;
            $keywordMatch = false;

            foreach ($page['keywords'] as $keyword) {
                if (stripos($keyword, $searchTerm) !== false) {
                    $keywordMatch = true;
                    break;
                }
            }

            if ($nameMatch || $keywordMatch) {
                $matchedPages[] = [
                    'id' => strtolower(str_replace(' ', '_', $page['name'])),
                    'title' => $page['name'],
                    'subtitle' => $page['description'],
                    'type' => 'page',
                    'url' => $page['url']
                ];
            }
        }

        return array_slice($matchedPages, 0, 4); // Limit to 4 pages
    }

    public function render()
    {
        return view('livewire.customer.global-search');
    }
}
