<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Quản lý Bán vé Xe khách - Admin</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        html {
            height: 100%;
        }

        body {
            /* 4. Content Canvas: Soft light gray */
            background-color: #f4f6f9;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* 2. Custom Scrollbars (CRITICAL UX) */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent; 
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(100, 116, 139, 0.4); 
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(100, 116, 139, 0.7); 
        }

        /* 1. Modern Sidebar (Slate Dark Theme) */
        .sidebar {
            background: #0f172a; /* Slate 900 */
            height: 100vh;
            padding: 20px 15px;
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            color: #f8fafc;
            overflow-y: auto;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            transform: translateX(-260px);
        }

        .sidebar .logo {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: 700;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            color: #ffffff;
            text-decoration: none;
        }

        .sidebar .logo i {
            margin-right: 12px;
            font-size: 28px;
            color: #38bdf8; /* Sky 400 */
        }

        .sidebar .menu-group {
            margin-bottom: 25px;
        }

        .sidebar .menu-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b; /* Slate 500 */
            margin-bottom: 12px;
            letter-spacing: 1.2px;
            padding-left: 10px;
        }

        .sidebar .menu-item {
            padding: 12px 15px;
            margin-bottom: 4px;
            border-radius: 8px;
            color: #cbd5e1; /* Slate 300 */
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
        }

        .sidebar .menu-item:hover {
            background-color: rgba(255,255,255,0.05);
            color: #ffffff;
        }

        .sidebar .menu-item.active {
            background-color: #38bdf8; /* Sky 400 */
            color: #0f172a; /* Slate 900 */
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
        }

        .sidebar .menu-item i {
            width: 24px;
            margin-right: 10px;
            font-size: 18px;
            text-align: center;
        }

        .main-wrapper {
            margin-left: 260px;
            width: calc(100% - 260px);
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: all 0.3s ease;
            background-color: #f4f6f9;
        }

        .main-wrapper.expanded {
            margin-left: 0;
            width: 100%;
        }

        /* 3. Topbar & Responsive Toggle */
        .topbar {
            background: #ffffff;
            padding: 0 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
            height: 70px;
            z-index: 999;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .sidebar-toggle {
            background: transparent;
            border: none;
            color: #64748b;
            font-size: 20px;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .sidebar-toggle:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .topbar-right {
            display: flex;
            align-items: center;
        }

        .user-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 40px;
            transition: background 0.2s;
            text-decoration: none;
            color: #334155;
            font-weight: 500;
            font-size: 14px;
        }

        .user-dropdown-toggle:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 16px;
        }

        .main-wrapper > main {
            flex: 1;
            overflow-y: auto;
            padding: 40px 48px; /* Breathing room (p-5 equivalent) */
        }

        /* Adjust basic table & button styles for the new canvas */
        .admin-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03); /* Subtle soft shadow */
            border: none; /* Removed heavy borders */
            margin-bottom: 24px;
        }

        .table-premium {
            margin: 0;
            --bs-table-bg: transparent;
            width: 100%;
        }

        .table-premium thead th {
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 600;
            color: #94a3b8; /* Muted color */
            padding: 16px 20px;
            font-size: 11px; /* Smaller font size */
            text-transform: uppercase;
            letter-spacing: 1.2px; /* Slight letter-spacing */
            border-top: none;
            border-left: none;
            border-right: none;
        }

        .table-premium tbody td {
            padding: 16px 20px;
            border-bottom: 1px solid #f8fafc; /* Very light gray line for separation */
            vertical-align: middle;
            font-size: 14px;
            color: #334155;
            border-left: none;
            border-right: none;
        }

        .table-premium tbody tr:last-child td {
            border-bottom: none;
        }

        .table-premium tbody tr:hover {
            background: #f8fafc;
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 8px 16px;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        /* Typography */
        .page-title {
            font-size: 1.5rem; /* fs-4 equivalent */
            font-weight: 600; /* fw-semibold equivalent */
            color: #0f172a; /* text-dark equivalent */
            margin-bottom: 1.5rem; /* mb-4 equivalent */
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-260px);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <a href="{{ route('dashboard') }}" class="logo">
            <i class="fas fa-bus"></i>
            <span>My Bus Admin</span>
        </a>

        <div class="menu-group">
            <div class="menu-title">Tổng quan</div>
            <a href="{{ route('dashboard') }}" class="menu-item @if(Route::currentRouteName() === 'dashboard') active @endif">
                <i class="fas fa-chart-pie"></i> Bảng điều khiển
            </a>
        </div>

        <div class="menu-group">
            <div class="menu-title">Quản lý nghiệp vụ</div>
            <a href="{{ route('tickets.index') }}" class="menu-item @if(str_contains(Route::currentRouteName(), 'tickets')) active @endif">
                <i class="fas fa-ticket-alt"></i> Quản Lý Vé
            </a>
            <a href="{{ route('trips.index') }}" class="menu-item @if(str_contains(Route::currentRouteName(), 'trips')) active @endif">
                <i class="fas fa-calendar-check"></i> Quản Lý Chuyến Xe
            </a>
            <a href="{{ route('routes.index') }}" class="menu-item @if(str_contains(Route::currentRouteName(), 'routes')) active @endif">
                <i class="fas fa-route"></i> Tuyến Xe
            </a>
            <a href="{{ route('buses.index') }}" class="menu-item @if(str_contains(Route::currentRouteName(), 'buses')) active @endif">
                <i class="fas fa-bus-alt"></i> Quản Lý Xe
            </a>
            <a href="{{ route('seats.index') }}" class="menu-item @if(str_contains(Route::currentRouteName(), 'seats')) active @endif">
                <i class="fas fa-chair"></i> Quản Lý Ghế
            </a>
        </div>

        <div class="menu-group">
            <div class="menu-title">Cấu hình hệ thống</div>
            <a href="{{ route('users.index') }}" class="menu-item @if(str_contains(Route::currentRouteName(), 'users')) active @endif">
                <i class="fas fa-users"></i> Người Dùng
            </a>
            <a href="{{ route('provinces.index') }}" class="menu-item @if(str_contains(Route::currentRouteName(), 'provinces')) active @endif">
                <i class="fas fa-map-marked-alt"></i> Tỉnh/Thành Phố
            </a>
            <a href="{{ route('bus_stations.index') }}" class="menu-item @if(str_contains(Route::currentRouteName(), 'bus_stations')) active @endif">
                <i class="fas fa-map-pin"></i> Bến Xe
            </a>
            <a href="{{ route('paymentMethods.index') }}" class="menu-item @if(str_contains(Route::currentRouteName(), 'payment_methods')) active @endif">
                <i class="fas fa-credit-card"></i> Phương Thức TT
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-wrapper" id="mainWrapper">
        <!-- Topbar -->
        <div class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <div class="topbar-right">
                <!-- Elegant Admin Profile Dropdown -->
                <div class="dropdown">
                    <a href="#" class="user-dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="d-none d-md-inline">{{ Auth::guard('admin')->user()->full_name ?? 'Administrator' }}</span>
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown" style="border: none; border-radius: 12px; margin-top: 10px;">
                        <li><h6 class="dropdown-header">Quản lý tài khoản</h6></li>
                        <li><a class="dropdown-item py-2" href="{{ url('/admin/profile') }}"><i class="fas fa-user-cog me-2 text-muted"></i>Hồ sơ</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('admins.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Page Content Canvas -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar Toggle Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainWrapper = document.getElementById('mainWrapper');

            sidebarToggle.addEventListener('click', function() {
                if (window.innerWidth <= 992) {
                    sidebar.classList.toggle('show');
                } else {
                    sidebar.classList.toggle('collapsed');
                    mainWrapper.classList.toggle('expanded');
                }
            });

            // Close sidebar on mobile when clicking outside
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 992) {
                    if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                        sidebar.classList.remove('show');
                    }
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
