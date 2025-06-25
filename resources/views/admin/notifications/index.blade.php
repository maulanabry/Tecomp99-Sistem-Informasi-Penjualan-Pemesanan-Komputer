@extends('components.layout-admin')

@section('title', 'Notifikasi')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Notifikasi</h1>
            <p class="text-muted">Kelola semua notifikasi sistem</p>
        </div>
        
        @if($unreadCount > 0)
            <div class="d-flex gap-2">
                <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-check-double me-1"></i>
                        Tandai Semua Dibaca ({{ $unreadCount }})
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.notifications.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label for="type" class="form-label">Tipe</label>
                    <select name="type" id="type" class="form-select">
                        <option value="all">Semua Tipe</option>
                        <option value="product.order.created" {{ request('type') === 'product.order.created' ? 'selected' : '' }}>Pesanan Produk</option>
                        <option value="service.order.created" {{ request('type') === 'service.order.created' ? 'selected' : '' }}>Pesanan Servis</option>
                    </select>
                </div>
                
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="card">
        <div class="card-body p-0">
            @if($notifications->count() > 0)
                @foreach($notifications as $notification)
                    <div class="notification-item border-bottom p-4 {{ $notification->read_at ? '' : 'bg-light' }}">
                        <div class="d-flex align-items-start">
                            <!-- Icon -->
                            <div class="flex-shrink-0 me-3">
                                @php
                                    $typeConfig = $notification->type instanceof \App\Enums\NotificationType 
                                        ? $notification->type 
                                        : \App\Enums\NotificationType::from($notification->type);
                                    $iconClass = $typeConfig->icon();
                                    $colorClass = $typeConfig->color();
                                @endphp
                                <div class="rounded-circle {{ $colorClass }} d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="{{ $iconClass }} text-white"></i>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-1 {{ $notification->read_at ? 'text-muted' : 'text-dark fw-bold' }}">
                                        {{ $notification->message }}
                                    </h6>
                                    
                                    <!-- Actions -->
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if(!$notification->read_at)
                                                <li>
                                                    <form action="{{ route('admin.notifications.mark-read', $notification->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fas fa-check me-2"></i>Tandai Dibaca
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                            <li>
                                                <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus notifikasi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fas fa-trash me-2"></i>Hapus
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Additional Data -->
                                @if($notification->data)
                                    <div class="mb-2">
                                        @if(isset($notification->data['order_id']))
                                            <span class="badge bg-primary me-1">
                                                <i class="fas fa-hashtag me-1"></i>{{ $notification->data['order_id'] }}
                                            </span>
                                        @endif
                                        @if(isset($notification->data['customer_name']))
                                            <span class="badge bg-info me-1">
                                                <i class="fas fa-user me-1"></i>{{ $notification->data['customer_name'] }}
                                            </span>
                                        @endif
                                        @if(isset($notification->data['device']))
                                            <span class="badge bg-secondary me-1">
                                                <i class="fas fa-laptop me-1"></i>{{ $notification->data['device'] }}
                                            </span>
                                        @endif
                                        @if(isset($notification->data['total']))
                                            <span class="badge bg-success me-1">
                                                <i class="fas fa-money-bill me-1"></i>Rp {{ number_format($notification->data['total'], 0, ',', '.') }}
                                            </span>
                                        @endif
                                        @if(isset($notification->data['type']))
                                            <span class="badge bg-warning text-dark me-1">
                                                <i class="fas fa-tag me-1"></i>{{ ucfirst($notification->data['type']) }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <!-- Time and Status -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                    </small>
                                    
                                    @if($notification->read_at)
                                        <small class="text-success">
                                            <i class="fas fa-check-circle me-1"></i>Dibaca {{ $notification->read_at->diffForHumans() }}
                                        </small>
                                    @else
                                        <small class="text-primary fw-bold">
                                            <i class="fas fa-circle me-1" style="font-size: 8px;"></i>Belum dibaca
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="p-4">
                    {{ $notifications->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash text-muted mb-3" style="font-size: 4rem;"></i>
                    <h5 class="text-muted">Tidak ada notifikasi</h5>
                    <p class="text-muted">Notifikasi akan muncul di sini ketika ada aktivitas baru.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.notification-item:hover {
    background-color: #f8f9fa !important;
}

.notification-item:last-child {
    border-bottom: none !important;
}
</style>
@endsection
