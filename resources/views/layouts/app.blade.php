<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UsahaKu - @yield('title', 'Kelola Usaha dengan Mudah')</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-dark: #1a2a4a;
            --primary-light: #2a3a6a;
            --success-green: #22c55e;
            --danger-red: #ef4444;
            --warning-yellow: #f59e0b;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--gray-50);
            color: var(--gray-800);
            display: flex;
            min-height: 100vh;
        }

        /* ============================================
           SIDEBAR
           ============================================ */
        .sidebar {
            width: 260px;
            background: var(--primary-dark);
            min-height: 100vh;
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .sidebar .brand {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar .brand h3 {
            color: white;
            font-weight: 800;
            font-size: 24px;
            margin: 0;
        }

        .sidebar .brand h3 span {
            color: var(--success-green);
        }

        .sidebar .brand small {
            color: rgba(255,255,255,0.5);
            display: block;
            margin-top: 4px;
            font-size: 12px;
        }

        .sidebar .nav-section {
            padding: 16px 20px 8px;
            color: rgba(255,255,255,0.3);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            transition: all 0.3s;
            font-size: 14px;
            font-weight: 500;
            margin: 2px 8px;
            border-radius: 8px;
        }

        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.08);
            color: white;
        }

        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.12);
            color: white;
        }

        .sidebar .nav-link i {
            width: 22px;
            font-size: 16px;
            margin-right: 12px;
            text-align: center;
        }

        .sidebar .nav-link .badge {
            margin-left: auto;
            background: var(--danger-red);
            color: white;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
        }

        /* ============================================
           MAIN WRAPPER
           ============================================ */
        .main-wrapper {
            margin-left: 260px;
            flex: 1;
            min-height: 100vh;
        }

        /* ============================================
           TOPBAR
           ============================================ */
        .topbar {
            background: white;
            padding: 12px 24px;
            border-bottom: 1px solid var(--gray-200);
            position: sticky;
            top: 0;
            z-index: 999;
            backdrop-filter: blur(12px);
            background: rgba(255,255,255,0.95);
        }

        .topbar .search-box {
            background: var(--gray-100);
            border-radius: 50px;
            padding: 6px 16px;
            border: 1px solid transparent;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }

        .topbar .search-box:focus-within {
            background: white;
            border-color: var(--primary-dark);
            box-shadow: 0 0 0 3px rgba(26,42,74,0.08);
        }

        .topbar .search-box input {
            border: none;
            background: transparent;
            outline: none;
            padding: 6px 12px;
            font-size: 14px;
            width: 250px;
            font-family: inherit;
        }

        .topbar .search-box i {
            color: var(--gray-400);
        }

        .topbar .user-dropdown {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 12px 4px 4px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .topbar .user-dropdown:hover {
            background: var(--gray-100);
        }

        .topbar .user-dropdown img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        .topbar .user-dropdown .name {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-800);
        }

        .topbar .user-dropdown .role {
            font-size: 11px;
            color: var(--gray-500);
        }

        /* ============================================
           PAGE CONTENT
           ============================================ */
        .page-content {
            padding: 24px 28px;
        }

        /* ============================================
           CARDS
           ============================================ */
        .card {
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            transition: all 0.3s;
            background: white;
        }

        .card:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.06);
        }

        /* ============================================
           TOAST
           ============================================ */
        .toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
        }

        .toast-custom {
            background: var(--gray-900);
            color: white;
            padding: 14px 20px;
            border-radius: 10px;
            margin-top: 8px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            animation: slideUp 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 500;
            min-width: 280px;
        }

        .toast-custom.success { background: var(--success-green); }
        .toast-custom.error { background: var(--danger-red); }
        .toast-custom.warning { background: var(--warning-yellow); }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* ============================================
           MOBILE - HAMBURGER & OVERLAY
           ============================================ */
        .hamburger {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--gray-700);
            cursor: pointer;
            padding: 4px;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 999;
            backdrop-filter: blur(2px);
        }

        /* ============================================
           SCROLLBAR
           ============================================ */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: var(--gray-100);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }

        /* ============================================
           BADGE STOK
           ============================================ */
        .badge-stock {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-stock.safe {
            background: #dcfce7;
            color: #15803d;
        }
        .badge-stock.low {
            background: #fef3c7;
            color: #b45309;
        }
        .badge-stock.out {
            background: #fee2e2;
            color: #dc2626;
        }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 992px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            .sales-layout {
                grid-template-columns: 1fr 1fr;
            }
            .page-content {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .sidebar-overlay.active {
                display: block;
            }
            .main-wrapper {
                margin-left: 0;
            }
            .hamburger {
                display: block;
            }
            .topbar {
                padding: 10px 16px;
            }
            .topbar .search-box input {
                width: 120px;
                font-size: 13px;
            }
            .topbar .user-dropdown .name,
            .topbar .user-dropdown .role {
                display: none;
            }
            .page-content {
                padding: 16px;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
            .stat-card {
                padding: 14px 16px;
            }
            .stat-value {
                font-size: 20px;
            }
            .stat-card .icon-wrapper {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            #salesChart {
                height: 180px !important;
            }
            .sales-layout {
                grid-template-columns: 1fr;
            }
            .cart-panel {
                max-height: 400px;
            }
            .cart-items {
                max-height: 180px;
            }
            .table-responsive {
                overflow-x: auto;
            }
            .table {
                font-size: 13px;
            }
            .table th,
            .table td {
                padding: 8px 10px;
                white-space: nowrap;
            }
            .card {
                border-radius: 12px;
            }
            .card-body {
                padding: 16px;
            }
            .form-control {
                font-size: 16px;
                padding: 10px 14px;
            }
            .btn {
                padding: 10px 16px;
                font-size: 14px;
            }
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
            .modal-dialog {
                margin: 10px;
            }
            .modal-body {
                padding: 16px;
            }
            h1 {
                font-size: 22px;
            }
            h2 {
                font-size: 20px;
            }
            h3 {
                font-size: 18px;
            }
            p,
            .text-muted {
                font-size: 13px;
            }
            .pagination {
                flex-wrap: wrap;
            }
            .pagination .page-link {
                padding: 6px 10px;
                font-size: 13px;
            }
            #notificationMenu {
                width: 320px !important;
                right: -20px !important;
            }
        }

        @media (max-width: 576px) {
            .topbar .search-box input {
                width: 70px !important;
                font-size: 11px !important;
            }
            .topbar .search-box {
                padding: 4px 10px !important;
            }
            .topbar .user-dropdown .name {
                display: none;
            }
            .topbar .user-dropdown {
                padding: 2px 8px 2px 2px;
            }
            .btn-action-group .btn {
                padding: 4px 6px !important;
                font-size: 10px !important;
            }
            .btn-action-group {
                gap: 3px;
            }
            .pagination {
                flex-wrap: wrap;
                gap: 4px;
            }
            .pagination .page-link {
                padding: 4px 8px;
                font-size: 12px;
            }
            .pagination .page-item {
                margin-bottom: 4px;
            }
            .table td,
            .table th {
                font-size: 11px !important;
                padding: 4px 6px !important;
            }
            .card-body {
                padding: 12px !important;
            }
            h1 {
                font-size: 18px !important;
            }
            h2 {
                font-size: 16px !important;
            }
            h3 {
                font-size: 14px !important;
            }
            .stat-value {
                font-size: 16px !important;
            }
            .badge {
                font-size: 10px !important;
                padding: 3px 8px !important;
            }
            #notificationMenu {
                width: 260px !important;
                right: -60px !important;
            }
            .btn-sm {
                padding: 4px 8px !important;
                font-size: 10px !important;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }
            .topbar .search-box input {
                width: 60px !important;
                font-size: 10px !important;
            }
            .sales-products {
                grid-template-columns: repeat(2, 1fr);
            }
            .btn-group-mobile {
                flex-direction: column;
                gap: 8px;
            }
            .btn-group-mobile .btn {
                width: 100%;
            }
            #notificationMenu {
                width: 260px !important;
                right: -60px !important;
            }
            h1 {
                font-size: 17px !important;
            }
            h2 {
                font-size: 15px !important;
            }
            .stat-value {
                font-size: 15px !important;
            }
            .page-content {
                padding: 10px !important;
            }
        }

        /* ============================================
           TOMBOL AKSI GROUP
           ============================================ */
        .btn-action-group {
            display: flex;
            gap: 4px;
            justify-content: center;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <h3>Usaha<span>Ku</span></h3>
            <small>Kelola Usaha dengan Mudah</small>
        </div>

        <div class="nav-section">Menu Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> Dashboard
        </a>
        <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products*') ? 'active' : '' }}">
            <i class="fas fa-box"></i> Produk
        </a>
        <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers*') ? 'active' : '' }}">
            <i class="fas fa-truck"></i> Supplier
        </a>
        <a href="{{ route('stock-ins.index') }}" class="nav-link {{ request()->routeIs('stock-ins*') ? 'active' : '' }}">
            <i class="fas fa-arrow-down"></i> Stok Masuk
        </a>
        <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales*') ? 'active' : '' }}">
            <i class="fas fa-cash-register"></i> Penjualan
            <span class="badge">+2</span>
        </a>
        <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses*') ? 'active' : '' }}">
            <i class="fas fa-wallet"></i> Pengeluaran
        </a>

        <div class="nav-section">Laporan</div>
        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i> Laporan
        </a>

        <div class="nav-section">Sistem</div>
        <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i> Pengaturan
        </a>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Topbar -->
        <header class="topbar">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <button class="hamburger" id="hamburgerBtn">
                        <i class="fas fa-bars"></i>
                    </button>
                   
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- ⏰ JAM REAL-TIME -->
                    <div class="text-muted small d-none d-md-block" style="font-size: 13px;">
                        <i class="fas fa-clock me-1"></i>
                        <span id="realtimeClock">{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i:s') }}</span> WIB
                    </div>

                    <!-- 🔔 NOTIFIKASI -->
                    <div class="dropdown">
                        <button class="btn btn-link p-0 position-relative text-secondary" 
                                id="notificationDropdown" 
                                data-bs-toggle="dropdown" 
                                aria-expanded="false">
                            <i class="fas fa-bell fa-lg"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                                  id="notificationBadge" 
                                  style="font-size:9px; display: none;">
                                0
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-0" 
                             id="notificationMenu" 
                             style="width: 380px; max-height: 400px; overflow-y: auto;">
                            <div class="p-3 border-bottom bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold">Notifikasi</h6>
                                    <a href="{{ route('notifications.index') }}" class="text-primary small">Lihat semua</a>
                                </div>
                            </div>
                            <div id="notificationList">
                                <div class="text-center py-3 text-muted small">Memuat...</div>
                            </div>
                        </div>
                    </div>

                    <div class="user-dropdown" data-bs-toggle="dropdown">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=1a2a4a&color=fff&size=36" alt="Profile">
                        @endif
                        <div>
                            <div class="name">{{ Auth::user()->name }}</div>
                            <div class="role">{{ ucfirst(Auth::user()->role) }}</div>
                        </div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="page-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ============================================
        // ⏰ REAL-TIME CLOCK
        // ============================================
        (function() {
            function updateClock() {
                var now = new Date();
                var hours = String(now.getHours()).padStart(2, '0');
                var minutes = String(now.getMinutes()).padStart(2, '0');
                var seconds = String(now.getSeconds()).padStart(2, '0');
                var timeString = hours + ':' + minutes + ':' + seconds;
                
                var clockElement = document.getElementById('realtimeClock');
                if (clockElement) {
                    clockElement.textContent = timeString;
                }
            }
            setInterval(updateClock, 1000);
            updateClock();
        })();

        // ============================================
        // 📱 SIDEBAR TOGGLE
        // ============================================
        (function() {
            var hamburgerBtn = document.getElementById('hamburgerBtn');
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebarOverlay');

            if (hamburgerBtn) {
                hamburgerBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('open');
                    overlay.classList.toggle('active');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('active');
                });
            }
        })();

        // ============================================
        // 🔍 GLOBAL SEARCH SHORTCUT (Ctrl+K)
        // ============================================
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'k') {
                e.preventDefault();
                var search = document.getElementById('globalSearch');
                if (search) search.focus();
            }
        });

        // ============================================
        // 🍞 TOAST NOTIFICATION
        // ============================================
        function showToast(message, type) {
            type = type || 'info';
            var container = document.getElementById('toastContainer');
            var toast = document.createElement('div');
            toast.className = 'toast-custom ' + type;
            
            var icons = {
                success: 'fa-check-circle',
                error: 'fa-times-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };
            
            toast.innerHTML = '<i class="fas ' + (icons[type] || icons.info) + '"></i> ' + message;
            container.appendChild(toast);
            
            setTimeout(function() {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(30px)';
                setTimeout(function() { toast.remove(); }, 300);
            }, 3000);
        }

        // ============================================
        // 🔔 NOTIFIKASI REAL-TIME
        // ============================================
        (function() {
            function loadNotifications() {
                fetch('{{ route("notifications.unread") }}')
                    .then(function(response) { return response.json(); })
                    .then(function(data) {
                        var badge = document.getElementById('notificationBadge');
                        var list = document.getElementById('notificationList');
                        
                        if (data.count > 0) {
                            badge.textContent = data.count;
                            badge.style.display = 'block';
                        } else {
                            badge.style.display = 'none';
                        }
                        
                        if (data.notifications.length > 0) {
                            var html = '';
                            data.notifications.forEach(function(n) {
                                var icon = n.type === 'warning' ? 'fa-exclamation-triangle text-warning' : 
                                           n.type === 'success' ? 'fa-check-circle text-success' : 
                                           'fa-info-circle text-primary';
                                html += '<div class="notification-item p-3 border-bottom hover-bg-light">';
                                html += '<div class="d-flex align-items-start gap-2">';
                                html += '<div class="mt-1"><i class="fas ' + icon + '"></i></div>';
                                html += '<div class="flex-grow-1">';
                                html += '<div class="fw-semibold small">' + n.title + '</div>';
                                html += '<div class="text-muted small">' + n.message + '</div>';
                                html += '<div class="text-muted small mt-1">' + new Date(n.created_at).toLocaleDateString('id-ID') + '</div>';
                                html += '</div>';
                                html += '<button class="btn btn-sm btn-link text-primary mark-read-dropdown" data-id="' + n.id + '">';
                                html += '<i class="fas fa-check-circle"></i>';
                                html += '</button>';
                                html += '</div></div>';
                            });
                            list.innerHTML = html;
                        } else {
                            list.innerHTML = '<div class="text-center py-4 text-muted">' +
                                '<i class="fas fa-bell-slash fa-2x d-block mb-2 opacity-25"></i>' +
                                '<small>Tidak ada notifikasi baru</small></div>';
                        }
                    });
            }

            loadNotifications();
            setInterval(loadNotifications, 30000);

            document.addEventListener('click', function(e) {
                var target = e.target.closest('.mark-read-dropdown');
                if (target) {
                    var id = target.dataset.id;
                    fetch('/notifications/' + id + '/read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(function(response) { return response.json(); })
                    .then(function(data) {
                        if (data.success) {
                            loadNotifications();
                            showToast('Notifikasi ditandai sudah dibaca', 'success');
                        }
                    });
                }
            });
        })();

        // ============================================
        // 🚀 WELCOME TOAST
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                showToast('🚀 Selamat datang di UsahaKu!', 'success');
            }, 500);
        });
    </script>
    @stack('scripts')
</body>
</html>