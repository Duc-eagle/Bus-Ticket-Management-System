<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Khách Hàng - My Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
        }

        /* Navbar */
        .navbar-customer {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }

        .navbar-customer .navbar-brand {
            color: white !important;
            font-size: 24px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-customer .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            transition: all 0.3s;
            margin: 0 10px;
        }

        .navbar-customer .nav-link:hover {
            color: white !important;
        }

        .navbar-customer .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid white;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            transition: all 0.3s;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
        }

        .navbar-customer .btn-logout:hover {
            background: white;
            color: #667eea;
        }

        /* Main Container */
        .container-customer {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .page-header {
            margin-bottom: 40px;
        }

        .page-header h1 {
            color: #333;
            font-weight: bold;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .page-header p {
            color: #999;
            font-size: 16px;
        }

        /* Card Grid */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .dashboard-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            text-align: center;
            cursor: pointer;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .dashboard-card-icon {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 15px;
        }

        .dashboard-card h3 {
            color: #333;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .dashboard-card p {
            color: #999;
            font-size: 14px;
            margin-bottom: 0;
        }

        .dashboard-card .card-value {
            font-size: 28px;
            color: #667eea;
            font-weight: bold;
            margin-top: 10px;
        }

        /* Quick Links */
        .quick-links {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .quick-links h2 {
            color: #333;
            font-weight: bold;
            margin-bottom: 25px;
            font-size: 22px;
        }

        .quick-links-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .quick-link-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 6px;
            text-decoration: none;
            text-align: center;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .quick-link-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 24px;
            }

            .cards-grid {
                grid-template-columns: 1fr;
            }

            .quick-links-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-customer">
        <div class="container-lg">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-bus"></i> My Bus
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.dashboard') }}">
                            <i class="fas fa-home"></i> Trang Chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.history') }}">
                            <i class="fas fa-ticket-alt"></i> Vé của tôi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.history') }}">
                            <i class="fas fa-history"></i> Lịch sử
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-user"></i> Tài khoản
                        </a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('customer.logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="btn-logout">
                                <i class="fas fa-sign-out-alt"></i> Đăng Xuất
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-customer">
        <!-- Page Header -->
        <div class="page-header">
            <h1>Xin chào, {{ Auth::user()->full_name }}! 👋</h1>
            <p>Chào mừng bạn quay trở lại My Bus</p>
        </div>

        <!-- Stats Cards -->
        <div class="cards-grid">
            <div class="dashboard-card">
                <div class="dashboard-card-icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <h3>Vé đang sử dụng</h3>
                <div class="card-value">0</div>
                <p>Chuyến đi sắp tới</p>
            </div>

            <div class="dashboard-card">
                <div class="dashboard-card-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3>Vé đã sử dụng</h3>
                <div class="card-value">0</div>
                <p>Hoàn thành</p>
            </div>

            <div class="dashboard-card">
                <div class="dashboard-card-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <h3>Tổng chi tiêu</h3>
                <div class="card-value">0 đ</div>
                <p>Năm nay</p>
            </div>

            <div class="dashboard-card">
                <div class="dashboard-card-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h3>Điểm thưởng</h3>
                <div class="card-value">0</div>
                <p>Có thể sử dụng</p>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="quick-links">
            <h2>Thao tác nhanh</h2>
            <div class="quick-links-grid">
                <a href="{{ route('home') }}" class="quick-link-btn">
                    <i class="fas fa-search"></i>
                    Tìm và đặt vé
                </a>
                <a href="{{ route('customer.history') }}" class="quick-link-btn">
                    <i class="fas fa-suitcase"></i>
                    Vé của tôi
                </a>
                <a href="{{ route('customer.history') }}" class="quick-link-btn">
                    <i class="fas fa-clock"></i>
                    Lịch sử đặt vé
                </a>
                <a href="#" class="quick-link-btn">
                    <i class="fas fa-question-circle"></i>
                    Trợ giúp
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
