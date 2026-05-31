<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Bus - Đặt vé xe khách trực tuyến')</title>
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
            line-height: 1.6;
        }

        .top-bar {
            background: #1a2849;
            color: white;
            padding: 12px 0;
            font-size: 14px;
        }

        .top-bar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .top-bar-left {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .top-bar-left a,
        .top-bar-right a {
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }

        .top-bar-left a:hover,
        .top-bar-right a:hover {
            color: #0099ff;
        }

        .top-bar-right a {
            margin-left: 20px;
        }

        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-left: 20px;
        }

        .user-menu {
            position: relative;
            display: inline-block;
        }

        .user-menu-toggle {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .user-menu-toggle:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .user-menu-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 4px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            min-width: 200px;
            display: none;
            margin-top: 0;
            z-index: 1000;
            overflow: hidden;
        }

        .user-menu:hover .user-menu-dropdown {
            display: block;
        }

        .user-menu-item {
            padding: 10px 15px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .top-bar-right .user-menu-dropdown .user-menu-item {
            color: #333;
            margin-left: 0;
        }

        .user-menu-item:hover {
            background: #f5f5f5;
            color: #0099ff;
        }

        .user-menu-logout {
            border-top: 1px solid #eee;
            color: #ef4444;
            width: 100%;
            text-align: left;
            border-left: none;
            border-right: none;
            border-bottom: none;
            background: none;
        }

        .user-menu-logout:hover {
            background: #fff5f5;
        }

        .navbar-custom {
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }

        .navbar-custom .navbar-brand {
            font-size: 24px;
            font-weight: bold;
            color: #0099ff !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-custom .navbar-brand i {
            font-size: 30px;
        }

        .navbar-custom .nav-link {
            color: #333 !important;
            font-weight: 500;
            transition: all 0.3s;
            margin: 0 15px;
        }

        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            color: #0099ff !important;
        }

        .footer {
            background: #1a2849;
            color: white;
            padding: 60px 20px 30px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 40px;
            margin-bottom: 40px;
            padding-bottom: 40px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-section h3 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #0099ff;
        }

        .footer-section ul {
            list-style: none;
            padding-left: 0;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-section a {
            color: #ccc;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 14px;
        }

        .footer-section a:hover {
            color: #0099ff;
        }

        .contact-info {
            font-size: 14px;
            color: #ccc;
            line-height: 1.8;
        }

        .contact-number {
            font-size: 32px;
            font-weight: bold;
            color: #c41e3a;
            margin: 10px 0;
        }

        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .social-icons a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            color: #0099ff;
        }

        .social-icons a:hover {
            background: #0099ff;
            color: white;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            color: #999;
            font-size: 14px;
            max-width: 1200px;
            margin: 0 auto;
        }

        @media (max-width: 1024px) {
            .footer-content {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .top-bar-content {
                flex-direction: column;
                gap: 10px;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="top-bar">
        <div class="top-bar-content">
            <div class="top-bar-left">
                <a href="mailto:mybus@gmail.com"><i class="fas fa-envelope"></i> mybus@gmail.com</a>
                <a href="tel:0985-90-7107"><i class="fas fa-phone"></i> 0985-90-7107</a>
{{--                <a href="#"><i class="fab fa-facebook"></i></a>--}}
{{--                <a href="#"><i class="fab fa-twitter"></i></a>--}}
            </div>
            <div class="top-bar-right">
                @if (Auth::check())
                    <div class="user-dropdown">
                        <div class="user-menu">
                            <a href="#" class="user-menu-toggle">
                                <i class="fas fa-user-circle"></i>
                                {{ Auth::user()->full_name }}
                            </a>
                            <div class="user-menu-dropdown">
                                <a href="{{ route('customer.account') }}" class="user-menu-item">
                                    <i class="fas fa-user"></i> Tài khoản
                                </a>
                                <a href="{{ route('customer.history') }}" class="user-menu-item">
                                    <i class="fas fa-history"></i> Lịch sử đặt vé
                                </a>
                                <a href="#" class="user-menu-item">
                                    <i class="fas fa-cog"></i> Cài đặt
                                </a>
                                <form action="{{ route('customer.logout') }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="user-menu-item user-menu-logout">
                                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('customer.login') }}">Đăng nhập</a>
                    <a href="{{ route('register') }}">Đăng ký</a>
                @endif
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-lg">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-map-pin"></i> My Bus
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link @if(Route::currentRouteName() === 'home') active @endif" href="{{ route('home') }}">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(Route::currentRouteName() === 'customer.history') active @endif" href="{{ route('customer.history') }}">Đơn hàng của tôi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Liên hệ</a>
                    </li>
{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link" href="#">Hóa đơn</a>--}}
{{--                    </li>--}}
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Kết nối với chúng tôi</h3>
                <div class="contact-number">09859017017</div>
{{--                <div class="social-icons">--}}
{{--                    <a href="#"><i class="fab fa-facebook"></i></a>--}}
{{--                    <a href="#"><i class="fab fa-twitter"></i></a>--}}
{{--                    <a href="#"><i class="fab fa-linkedin"></i></a>--}}
{{--                </div>--}}
                <div class="contact-info" style="margin-top: 20px;">
                    Địa chỉ: TP.Hà Nội<br>
                    Email: info@mybus.com
                </div>
            </div>

            <div class="footer-section">
                <h3>Hướng dẫn</h3>
{{--                <ul>--}}
{{--                    <li><a href="#">Hướng dẫn đặt vé trên Web</a></li>--}}
{{--                    <li><a href="#">Hướng dẫn đặt vé trên App</a></li>--}}
{{--                    <li><a href="#">Hỏi đáp</a></li>--}}
{{--                    <li><a href="#">Điều khoản sử dụng</a></li>--}}
{{--                </ul>--}}
            </div>

            <div class="footer-section">
                <h3>Đi đến trang</h3>
                <ul>
                    <li><a href="{{ route('home') }}">Trang chủ</a></li>
{{--                    <li><a href="#">Lịch trình</a></li>--}}
{{--                    <li><a href="#">Liên hệ</a></li>--}}
                    <li><a href="{{ route('customer.login') }}">Đăng nhập</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Khác</h3>
                <ul>
                    <li><a href="#">Trở thành nhà cung cấp</a></li>
                    <li><a href="#">Cộng tác với chúng tôi</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                    <li><a href="#">Liên kết hữu dụng</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            &copy; 2026 My Bus Management System. All rights reserved.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
