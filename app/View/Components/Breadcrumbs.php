<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;

class Breadcrumbs extends Component
{
    public $breadcrumbs;

    public function __construct()
    {
        $this->breadcrumbs = $this->generateBreadcrumbs();
    }

    private function generateBreadcrumbs()
    {
        $routeName = Route::currentRouteName();
        $breadcrumbs = [];

        // Always start with Dashboard - determine which dashboard based on current route
        if (str_contains($routeName, 'pemilik.') || str_contains($routeName, 'manajemen-pengguna')) {
            $breadcrumbs[] = [
                'title' => 'Dashboard',
                'url' => route('pemilik.dashboard.index'),
                'active' => false
            ];
        } else {
            $breadcrumbs[] = [
                'title' => 'Dashboard',
                'url' => route('admin.dashboard.index'),
                'active' => false
            ];
        }

        // Generate breadcrumbs based on current route
        switch (true) {
            // Order routes (check these first to avoid conflicts with products/services)
            case str_contains($routeName, 'order-services'):
                $breadcrumbs[] = ['title' => 'Order', 'url' => null, 'active' => false];
                $breadcrumbs[] = $this->getOrderServiceBreadcrumb($routeName);
                break;

            case str_contains($routeName, 'order-products'):
                $breadcrumbs[] = ['title' => 'Order', 'url' => null, 'active' => false];
                $breadcrumbs[] = $this->getOrderProductBreadcrumb($routeName);
                break;

            // Jadwal Servis (check before service tickets)
            case str_contains($routeName, 'jadwal-servis'):
                $breadcrumbs[] = $this->getJadwalServisBreadcrumb($routeName);
                break;

            // Service Tickets (check before services)
            case str_contains($routeName, 'service-tickets'):
                $breadcrumbs[] = $this->getServiceTicketBreadcrumb($routeName);
                break;

            // Data Master routes
            case str_contains($routeName, 'categories'):
                $breadcrumbs[] = ['title' => 'Data Master', 'url' => null, 'active' => false];
                $breadcrumbs[] = $this->getCategoryBreadcrumb($routeName);
                break;

            case str_contains($routeName, 'brands'):
                $breadcrumbs[] = ['title' => 'Data Master', 'url' => null, 'active' => false];
                $breadcrumbs[] = $this->getBrandBreadcrumb($routeName);
                break;

            case str_contains($routeName, 'products'):
                $breadcrumbs[] = ['title' => 'Data Master', 'url' => null, 'active' => false];
                $breadcrumbs[] = $this->getProductBreadcrumb($routeName);
                break;

            case str_contains($routeName, 'services'):
                $breadcrumbs[] = ['title' => 'Data Master', 'url' => null, 'active' => false];
                $breadcrumbs[] = $this->getServiceBreadcrumb($routeName);
                break;

            case str_contains($routeName, 'vouchers'):
                $breadcrumbs[] = ['title' => 'Data Master', 'url' => null, 'active' => false];
                $breadcrumbs[] = $this->getVoucherBreadcrumb($routeName);
                break;

            case str_contains($routeName, 'customers'):
                $breadcrumbs[] = ['title' => 'Data Master', 'url' => null, 'active' => false];
                $breadcrumbs[] = $this->getCustomerBreadcrumb($routeName);
                break;

            // Manajemen Pengguna (Pemilik)
            case str_contains($routeName, 'manajemen-pengguna'):
                $breadcrumbs[] = $this->getManajemenPenggunaBreadcrumb($routeName);
                break;

            // Payments
            case str_contains($routeName, 'payments'):
                $breadcrumbs[] = $this->getPaymentBreadcrumb($routeName);
                break;

            // Settings
            case str_contains($routeName, 'settings'):
                if (str_contains($routeName, 'general') || str_contains($routeName, 'system') || str_contains($routeName, 'notification')) {
                    $breadcrumbs[] = ['title' => 'Pengaturan', 'url' => route('settings.index'), 'active' => false];
                    $breadcrumbs[] = $this->getSettingsBreadcrumb($routeName);
                } else {
                    $breadcrumbs[] = $this->getSettingsBreadcrumb($routeName);
                }
                break;

            // Dashboard (current page)
            case $routeName === 'admin.dashboard.index' || $routeName === 'pemilik.dashboard.index':
                $breadcrumbs[0]['active'] = true;
                break;
        }

        return $breadcrumbs;
    }

    private function getCategoryBreadcrumb($routeName)
    {
        $base = ['title' => 'Data Kategori', 'url' => route('categories.index'), 'active' => false];

        if (str_contains($routeName, 'create')) {
            return [
                $base,
                ['title' => 'Tambah Kategori', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'edit')) {
            return [
                $base,
                ['title' => 'Edit Kategori', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'show')) {
            return [
                $base,
                ['title' => 'Detail Kategori', 'url' => null, 'active' => true]
            ];
        } else {
            $base['active'] = true;
            return $base;
        }
    }

    private function getBrandBreadcrumb($routeName)
    {
        $base = ['title' => 'Data Brand', 'url' => route('brands.index'), 'active' => false];

        if (str_contains($routeName, 'create')) {
            return [
                $base,
                ['title' => 'Tambah Brand', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'edit')) {
            return [
                $base,
                ['title' => 'Edit Brand', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'show')) {
            return [
                $base,
                ['title' => 'Detail Brand', 'url' => null, 'active' => true]
            ];
        } else {
            $base['active'] = true;
            return $base;
        }
    }

    private function getProductBreadcrumb($routeName)
    {
        $base = ['title' => 'Data Produk', 'url' => route('products.index'), 'active' => false];

        if (str_contains($routeName, 'create')) {
            return [
                $base,
                ['title' => 'Tambah Produk', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'edit')) {
            return [
                $base,
                ['title' => 'Edit Produk', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'show')) {
            return [
                $base,
                ['title' => 'Detail Produk', 'url' => null, 'active' => true]
            ];
        } else {
            $base['active'] = true;
            return $base;
        }
    }

    private function getServiceBreadcrumb($routeName)
    {
        $base = ['title' => 'Data Servis', 'url' => route('services.index'), 'active' => false];

        if (str_contains($routeName, 'create')) {
            return [
                $base,
                ['title' => 'Tambah Servis', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'edit')) {
            return [
                $base,
                ['title' => 'Edit Servis', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'show')) {
            return [
                $base,
                ['title' => 'Detail Servis', 'url' => null, 'active' => true]
            ];
        } else {
            $base['active'] = true;
            return $base;
        }
    }

    private function getVoucherBreadcrumb($routeName)
    {
        $base = ['title' => 'Data Voucher', 'url' => route('vouchers.index'), 'active' => false];

        if (str_contains($routeName, 'create')) {
            return [
                $base,
                ['title' => 'Tambah Voucher', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'edit')) {
            return [
                $base,
                ['title' => 'Edit Voucher', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'show')) {
            return [
                $base,
                ['title' => 'Detail Voucher', 'url' => null, 'active' => true]
            ];
        } else {
            $base['active'] = true;
            return $base;
        }
    }

    private function getCustomerBreadcrumb($routeName)
    {
        $base = ['title' => 'Data Customer', 'url' => route('customers.index'), 'active' => false];

        if (str_contains($routeName, 'create')) {
            return [
                $base,
                ['title' => 'Tambah Customer', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'edit')) {
            return [
                $base,
                ['title' => 'Edit Customer', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'show')) {
            return [
                $base,
                ['title' => 'Detail Customer', 'url' => null, 'active' => true]
            ];
        } else {
            $base['active'] = true;
            return $base;
        }
    }

    private function getOrderServiceBreadcrumb($routeName)
    {
        $base = ['title' => 'Order Servis', 'url' => route('order-services.index'), 'active' => false];

        if (str_contains($routeName, 'create')) {
            return [
                $base,
                ['title' => 'Tambah Order Servis', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'edit')) {
            return [
                $base,
                ['title' => 'Edit Order Servis', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'show')) {
            return [
                $base,
                ['title' => 'Detail Order Servis', 'url' => null, 'active' => true]
            ];
        } else {
            $base['active'] = true;
            return $base;
        }
    }

    private function getOrderProductBreadcrumb($routeName)
    {
        $base = ['title' => 'Order Produk', 'url' => route('order-products.index'), 'active' => false];

        if (str_contains($routeName, 'create')) {
            return [
                $base,
                ['title' => 'Tambah Order Produk', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'edit')) {
            return [
                $base,
                ['title' => 'Edit Order Produk', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'show')) {
            return [
                $base,
                ['title' => 'Detail Order Produk', 'url' => null, 'active' => true]
            ];
        } else {
            $base['active'] = true;
            return $base;
        }
    }

    private function getJadwalServisBreadcrumb($routeName)
    {
        $base = ['title' => 'Jadwal Servis', 'url' => route('teknisi.jadwal-servis.index'), 'active' => false];

        if (str_contains($routeName, 'calendar')) {
            return [
                $base,
                ['title' => 'Kalender', 'url' => null, 'active' => true]
            ];
        } else {
            $base['active'] = true;
            return $base;
        }
    }

    private function getServiceTicketBreadcrumb($routeName)
    {
        $base = ['title' => 'Tiket Servis', 'url' => route('service-tickets.index'), 'active' => false];

        if (str_contains($routeName, 'create')) {
            return [
                $base,
                ['title' => 'Tambah Tiket Servis', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'edit')) {
            return [
                $base,
                ['title' => 'Edit Tiket Servis', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'show')) {
            return [
                $base,
                ['title' => 'Detail Tiket Servis', 'url' => null, 'active' => true]
            ];
        } else {
            $base['active'] = true;
            return $base;
        }
    }

    private function getPaymentBreadcrumb($routeName)
    {
        $base = ['title' => 'Pembayaran', 'url' => route('payments.index'), 'active' => false];

        if (str_contains($routeName, 'create')) {
            return [
                $base,
                ['title' => 'Tambah Pembayaran', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'edit')) {
            return [
                $base,
                ['title' => 'Edit Pembayaran', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'show')) {
            return [
                $base,
                ['title' => 'Detail Pembayaran', 'url' => null, 'active' => true]
            ];
        } else {
            $base['active'] = true;
            return $base;
        }
    }

    private function getManajemenPenggunaBreadcrumb($routeName)
    {
        $base = ['title' => 'Manajemen Pengguna', 'url' => route('pemilik.manajemen-pengguna.index'), 'active' => false];

        if (str_contains($routeName, 'create')) {
            return [
                $base,
                ['title' => 'Tambah Pengguna', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'edit')) {
            return [
                $base,
                ['title' => 'Edit Pengguna', 'url' => null, 'active' => true]
            ];
        } elseif (str_contains($routeName, 'show')) {
            return [
                $base,
                ['title' => 'Detail Pengguna', 'url' => null, 'active' => true]
            ];
        } else {
            $base['active'] = true;
            return $base;
        }
    }

    private function getSettingsBreadcrumb($routeName)
    {
        if (str_contains($routeName, 'general')) {
            return ['title' => 'Pengaturan Umum', 'url' => null, 'active' => true];
        } elseif (str_contains($routeName, 'system')) {
            return ['title' => 'Pengaturan Sistem', 'url' => null, 'active' => true];
        } elseif (str_contains($routeName, 'notification')) {
            return ['title' => 'Pengaturan Notifikasi', 'url' => null, 'active' => true];
        } else {
            return ['title' => 'Pengaturan', 'url' => route('settings.index'), 'active' => true];
        }
    }

    public function render()
    {
        return view('components.breadcrumbs');
    }
}
