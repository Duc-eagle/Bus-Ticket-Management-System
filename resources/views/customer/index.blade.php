@extends('layouts.layout')

@section('title', 'My Bus - Dat ve xe khach truc tuyen')

@push('styles')
    <style>
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),
            url('{{ asset('images/come_my_bus.jpg') }}') center/cover;
            height: 750px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-content h1 {
            font-size: 64px;
            font-weight: bold;
            margin-bottom: 20px;
            animation: slideInDown 0.8s ease-out;
        }

        .hero-content h1 .highlight {
            color: #0099ff;
        }

        .hero-content p {
            font-size: 20px;
            margin-bottom: 30px;
            color: #e0e0e0;
            animation: slideInUp 0.8s ease-out;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-bottom: 40px;
            animation: slideInUp 0.8s ease-out 0.2s both;
        }

        .btn-explore {
            background: #0099ff;
            color: white;
            padding: 12px 35px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-explore:hover {
            background: #0077cc;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 153, 255, 0.4);
        }

        .btn-video {
            background: transparent;
            border: 2px solid white;
            color: white;
            padding: 12px 35px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-video:hover {
            background: white;
            color: #0099ff;
        }

        .search-form-container {
            position: static;
            margin-top: -80px;
            width: 100%;
        }

        .search-form {
            background: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 1200px;
            margin: 0 auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 20px;
            align-items: flex-end;
        }

        .search-form-group {
            display: flex;
            flex-direction: column;
        }

        .search-form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .search-form-group select,
        .search-form-group input {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .search-form-group select:focus,
        .search-form-group input:focus {
            border-color: #0099ff;
            box-shadow: 0 0 0 3px rgba(0, 153, 255, 0.1);
            outline: none;
        }

        .btn-search {
            background: #c41e3a;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .btn-search:hover {
            background: #a01830;
            transform: translateY(-2px);
        }

        .features-section {
            background: white;
            padding: 80px 20px 40px;
            margin-top: 100px;
        }

        .features-container,
        .routes-container,
        .destinations-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            color: #0099ff;
            margin-bottom: 15px;
        }

        .section-subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 50px;
            font-size: 16px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            margin-bottom: 60px;
        }

        .feature-card {
            text-align: center;
            padding: 20px;
            transition: all 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            font-size: 60px;
            color: #0099ff;
            margin-bottom: 20px;
            background: #f0f7ff;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
            margin-right: auto;
        }

        .feature-card h3 {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .feature-card p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }

        .routes-section {
            background: #f8f9fa;
            padding: 60px 20px;
        }

        .routes-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }

        .route-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: grid;
            grid-template-columns: 150px 1fr;
            transition: all 0.3s;
            cursor: pointer;
        }

        .route-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .route-image {
            height: 150px;
            object-fit: cover;
            width: 100%;
        }

        .route-info {
            margin-left: 50px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .route-name {
            font-size: 18px;
            font-weight: bold;
            color: #c41e3a;
            margin-bottom: 10px;
        }

        .route-details {
            display: flex;
            gap: 30px;
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .route-detail {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .route-price {
            font-size: 18px;
            font-weight: bold;
            color: #0099ff;
        }

        .destinations-section {
            background: white;
            padding: 60px 20px;
        }

        .destinations-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 60px;
        }

        .destination-card {
            position: relative;
            height: 200px;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s;
        }

        .destination-card:hover {
            transform: scale(1.05);
        }

        .destination-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .destination-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0);
            transition: all 0.3s;
            display: flex;
            align-items: flex-end;
            padding: 15px;
            color: white;
        }

        .destination-card:hover .destination-overlay {
            background: rgba(0, 0, 0, 0.4);
        }

        .destination-name {
            font-size: 16px;
            font-weight: bold;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 1024px) {
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .routes-grid {
                grid-template-columns: 1fr;
            }

            .destinations-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .search-form {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 40px;
            }

            .hero-content p {
                font-size: 16px;
            }

            .hero-buttons {
                flex-direction: column;
                gap: 10px;
            }

            .search-form {
                grid-template-columns: 1fr;
                padding: 20px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .destinations-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .route-card {
                grid-template-columns: 1fr;
            }

            .route-image {
                height: 200px;
            }
        }
    </style>
@endpush

@section('content')
    <section class="hero-section">
        <div style="position: relative; z-index: 10; width: 100%; text-align: center;">
            <div class="hero-content">
                <h1>Welcome to <span class="highlight">My Bus</span></h1>
                <p>Đi để khám phá bản thân, đi để tìm thấy câu trả lời hạnh phúc thật sự</p>
{{--                <div class="hero-buttons">--}}
{{--                    <button class="btn-explore">KHÁM PHÁ</button>--}}
{{--                    <button class="btn-video">--}}
{{--                        <i class="fas fa-play"></i> Watch Video--}}
{{--                    </button>--}}
{{--                </div>--}}
            </div>
        </div>

        <div class="search-form-container">
            <div class="container-lg">
                <form action="{{ route('customer.search') }}" method="GET" class="search-form">
                    <div class="search-form-group">
                        <label>Điểm đi</label>
                        <select name="departure_id" required>
                            <option value="">Chọn điểm khởi hành</option>
                            @foreach($stations as $station)
                                <option value="{{ $station->id }}">{{ $station->station_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="search-form-group">
                        <label>Điểm đến</label>
                        <select name="arrival_id" required>
                            <option value="">Chọn điểm đến</option>
                            @foreach($stations as $station)
                                <option value="{{ $station->id }}">{{ $station->station_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="search-form-group">
                        <label>Ngày đi</label>
                        <input type="date" name="date" min="{{ date('Y-m-d') }}" required>
                    </div>
                    <button type="submit" class="btn-search">TÌM CHUYẾN XE</button>
                </form>
            </div>
        </div>
    </section>

    <section class="features-section">
        <div class="features-container">
            <h2 class="section-title">MY BUS - CHẤT LƯỢNG LÀ DANH DỰ</h2>
            <p class="section-subtitle">Được khách hàng tin tưởng và lựa chọn</p>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-thumbs-up"></i>
                    </div>
                    <h3>Tiện lợi</h3>
                    <p>Mua vé xe mọi nơi mà không cần tốn nhiều thời gian.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Hỗ trợ 24/7</h3>
                    <p>Chúng tôi cung cấp dịch vụ chăm sóc khách hàng 24 giờ bất cứ lúc nào.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-list"></i>
                    </div>
                    <h3>Thông tin đầy đủ</h3>
                    <p>Lịch trình, tuyến đường, chuyến xe, giá vé và chỗ ngồi đều hiển thị rõ ràng.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3>Thanh toán dễ dàng</h3>
                    <p>Hỗ trợ đầy đủ chuyển khoản và thẻ tín dụng cho quá trình đặt vé.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="routes-section">
        <div class="routes-container">
            <h2 class="section-title">TUYẾN PHỔ BIẾN</h2>
            <p class="section-subtitle">Gợi ý những tuyến xe được đặt nhiều</p>

            <div class="routes-grid">
                @foreach($routes as $route)
                    <div class="route-card" style="position: relative;">
                        <a href="{{ route('customer.routes.trips', $route) }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2;" title="Xem danh sach chuyen di"></a>
                        <img src="{{ $route->image_path ? asset('storage/' . $route->image_path) : 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=200&h=150&fit=crop' }}" alt="{{ $route->route_name }}" class="route-image">
                        <div class="route-info">
                            <h4 class="route-name">{{ $route->route_name }}</h4>
                            <div class="route-details">
                                <span class="route-detail"><i class="fas fa-clock"></i> {{ $route->estimate_time }}</span>
                                <span class="route-detail"><i class="fas fa-map-marker-alt"></i> {{ $route->distance }}km</span>
                            </div>
                            <div class="route-price">
                                @if($route->trips->first())
                                    {{ number_format($route->trips->first()->base_price, 0, ',', '.') }} đ
                                @else
                                    Liên hệ
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="destinations-section">
        <div class="destinations-container">
            <h2 class="section-title">ĐIỂM ĐẾN PHỔ BIẾN</h2>
            <p class="section-subtitle">Gợi ý những điểm du lịch được ưa thích trong năm</p>

            <div class="destinations-grid">
                @foreach($provinces as $province)
                    <a href="{{ route('customer.search', ['arrival_id' => $province->id, 'date' => \Carbon\Carbon::today()->format('Y-m-d')]) }}" class="destination-card" style="text-decoration: none; display: block; color: inherit;">
                        <img src="{{ $province->image_path ? asset('storage/' . $province->image_path) : 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?w=400&h=300&fit=crop' }}" alt="{{ $province->province_name }}">
                        <div class="destination-overlay">
                            <span class="destination-name">{{ $province->province_name }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
@endpush
