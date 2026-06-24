@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Notifikasi</h1>
            <p class="text-muted">{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</p>
        </div>
        <div class="d-flex gap-2">
            @if($unreadCount > 0)
            <a href="{{ route('notifications.mark-all-read') }}" class="btn btn-outline-primary">
                <i class="fas fa-check-double me-2"></i>Tandai Semua Dibaca
            </a>
            @endif
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-bell me-2"></i>Semua Notifikasi
                    @if($unreadCount > 0)
                    <span class="badge bg-danger ms-2">{{ $unreadCount }} belum dibaca</span>
                    @endif
                </h5>
            </div>
        </div>
        <div class="card-body p-0">
            @forelse($notifications as $notification)
            <div class="notification-item d-flex align-items-start gap-3 p-3 border-bottom {{ $notification->is_read ? '' : 'bg-light' }}">
                <div class="notification-icon mt-1">
                    @if($notification->type == 'warning')
                        <i class="fas fa-exclamation-triangle text-warning fa-lg"></i>
                    @elseif($notification->type == 'success')
                        <i class="fas fa-check-circle text-success fa-lg"></i>
                    @elseif($notification->type == 'error')
                        <i class="fas fa-times-circle text-danger fa-lg"></i>
                    @else
                        <i class="fas fa-info-circle text-primary fa-lg"></i>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1 fw-semibold {{ $notification->is_read ? '' : 'text-primary' }}">
                                {{ $notification->title }}
                            </h6>
                            <p class="mb-1 text-muted small">{{ $notification->message }}</p>
                            <small class="text-muted">
                                {{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <div class="d-flex gap-2">
                            @if(!$notification->is_read)
                            <button class="btn btn-sm btn-outline-primary mark-read" data-id="{{ $notification->id }}">
                                <i class="fas fa-check"></i>
                            </button>
                            @endif
                            @if($notification->link)
                            <a href="{{ $notification->link }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-4x text-muted mb-3 d-block opacity-25"></i>
                <h5 class="text-muted">Tidak ada notifikasi</h5>
                <p class="text-muted">Semua notifikasi akan muncul di sini</p>
            </div>
            @endforelse
        </div>
        <div class="card-footer bg-white">
            {{ $notifications->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.mark-read').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.closest('.notification-item').classList.remove('bg-light');
                    this.remove();
                    const badge = document.querySelector('.badge.bg-danger');
                    if (badge) {
                        const count = parseInt(badge.textContent) - 1;
                        if (count > 0) {
                            badge.textContent = count;
                        } else {
                            badge.remove();
                        }
                    }
                }
            });
        });
    });
});
</script>
@endpush
@endsection