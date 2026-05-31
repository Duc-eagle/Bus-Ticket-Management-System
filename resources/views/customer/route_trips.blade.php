@extends('layouts.layout')

@section('title', $route->route_name . ' - Danh sach chuyen di')

@push('styles')
    <style>
        .trip-page {
            background:
                radial-gradient(circle at top left, rgba(255, 111, 60, 0.08), transparent 24%),
                linear-gradient(180deg, #fffaf7 0%, #f4f7fb 28%, #eef3f9 100%);
            min-height: 100vh;
            padding: 48px 20px 72px;
        }

        .trip-container {
            max-width: 1180px;
            margin: 0 auto;
        }

        .trip-search-shell {
            margin-bottom: 26px;
        }

        .trip-search-card {
            position: relative;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(255, 107, 53, 0.45);
            border-radius: 22px;
            box-shadow: 0 18px 40px rgba(34, 52, 84, 0.08);
            padding: 18px 22px 28px;
        }

        .trip-search-top {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 12px;
        }

        .trip-search-guide {
            color: #ff5b2e;
            font-size: 13px;
            text-decoration: none;
            font-weight: 500;
        }

        .trip-search-form {
            display: grid;
            grid-template-columns: 1.2fr 48px 1.2fr 1.1fr 1fr;
            gap: 12px;
            align-items: end;
        }

        .trip-search-group label {
            display: block;
            color: #111827;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .trip-search-field {
            width: 100%;
            height: 52px;
            border: 1px solid #d7dde8;
            border-radius: 8px;
            background: #fff;
            padding: 0 16px;
            color: #111827;
            font-size: 15px;
            outline: none;
        }

        .trip-search-field::placeholder {
            color: #98a2b3;
        }

        .trip-search-date {
            display: flex;
            flex-direction: column;
            justify-content: center;
            line-height: 1.2;
        }

        .trip-search-date strong {
            color: #111827;
            font-size: 15px;
            font-weight: 700;
        }

        .trip-search-date span {
            color: #475467;
            font-size: 13px;
            margin-top: 2px;
        }

        .trip-search-switch {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 52px;
        }

        .trip-search-switch span {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 1px solid #ffd5c7;
            background: #fff7f2;
            color: #ff5b2e;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .trip-search-action {
            display: flex;
            justify-content: center;
            margin-top: -12px;
        }

        .trip-search-btn {
            min-width: 196px;
            height: 38px;
            border: 0;
            border-radius: 999px;
            background: linear-gradient(135deg, #ff5b2e 0%, #ff7a3d 100%);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            box-shadow: 0 16px 26px rgba(255, 107, 53, 0.25);
            margin-top: 25px;
        }

        .trip-layout {
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
            gap: 24px;
            align-items: start;
        }

        .trip-header {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 111, 60, 0.18);
            border-radius: 28px;
            box-shadow: 0 20px 50px rgba(34, 52, 84, 0.08);
            padding: 28px;
            margin-bottom: 28px;
            backdrop-filter: blur(14px);
        }

        .trip-title {
            color: #1f2430;
            font-size: 34px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .trip-subtitle {
            color: #667085;
            margin-bottom: 24px;
        }

        .trip-route-meta {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }

        .meta-card {
            background: linear-gradient(180deg, #fff 0%, #fff7f2 100%);
            border: 1px solid #f6ddd1;
            border-radius: 18px;
            padding: 18px;
        }

        .meta-label {
            color: #8a6470;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 8px;
        }

        .meta-value {
            color: #1f2430;
            font-size: 16px;
            font-weight: 600;
        }

        .trip-filter {
            position: sticky;
            top: 24px;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid #e3e8f0;
            border-radius: 22px;
            box-shadow: 0 18px 42px rgba(30, 41, 59, 0.08);
            overflow: hidden;
        }

        .trip-filter-head {
            padding: 18px 20px;
            border-bottom: 1px solid #edf1f6;
        }

        .trip-filter-title {
            color: #111827;
            font-size: 14px;
            font-weight: 700;
            margin: 0 0 4px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .trip-filter-note {
            color: #6b7280;
            font-size: 13px;
            margin: 0;
            line-height: 1.5;
        }

        .trip-filter-body {
            padding: 18px 20px 22px;
        }

        .filter-block + .filter-block {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #edf1f6;
        }

        .filter-label {
            color: #344054;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .filter-chip-wrap {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .filter-chip {
            border: 1px solid #d9e2ec;
            border-radius: 999px;
            padding: 7px 12px;
            color: #475467;
            font-size: 12px;
            background: #fff;
        }

        .trip-list {
            background: transparent;
        }

        .trip-list-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 18px;
        }

        .trip-list-title {
            color: #1f2430;
            font-size: 26px;
            font-weight: 700;
            margin: 0 0 6px;
        }

        .trip-list-caption {
            color: #667085;
            margin: 0;
        }

        .trip-summary-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 999px;
            background: #fff4ed;
            color: #c2410c;
            font-weight: 600;
            white-space: nowrap;
        }

        .trip-cards {
            display: grid;
            gap: 14px;
        }

        /* ── Card wrapper ── */
        .trip-card {
            background: #fff;
            border: 1px solid #e5eaf2;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(30, 41, 59, 0.07);
            overflow: hidden;
            display: grid;
            grid-template-columns: 100px 1fr auto;
            align-items: stretch;
        }

        /* ── Left: bus thumbnail ── */
        .trip-card-image {
            width: 100px;
            background: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #94a3b8;
            flex-shrink: 0;
        }

        .trip-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ── Middle: info ── */
        .trip-card-body {
            padding: 14px 18px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        /* Row 1: bus name + seats */
        .trip-card-bus-info {
            display: flex;
            align-items: baseline;
            gap: 10px;
        }

        .trip-card-bus-name {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }

        .trip-card-bus-seats {
            font-size: 13px;
            color: #667085;
        }

        /* Row 2: departure time/station */
        .trip-card-stop {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #374151;
            font-size: 14px;
        }

        .trip-card-stop-icon {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: 2px solid #f97316;
            flex-shrink: 0;
        }

        .trip-card-stop-icon.arrival {
            border-color: #111827;
        }

        .trip-card-stop-time {
            font-weight: 700;
            font-size: 15px;
            color: #111827;
            min-width: 52px;
        }

        .trip-card-station {
            font-size: 13px;
            color: #667085;
        }

        /* Row 3: duration connector */
        .trip-card-connector {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #9ca3af;
            font-size: 12px;
            padding-left: 4px;
        }

        .trip-card-connector-line {
            width: 2px;
            height: 22px;
            background: #e5e7eb;
            margin-left: 3px;
            flex-shrink: 0;
        }

        .trip-card-duration-label {
            font-size: 12px;
            color: #9ca3af;
        }

        /* ── Right: price + actions ── */
        .trip-card-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: space-between;
            padding: 14px 16px;
            border-left: 1px solid #f0f2f5;
            min-width: 180px;
        }

        .trip-card-price {
            font-size: 20px;
            font-weight: 800;
            color: #f97316;
        }

        .trip-card-available {
            font-size: 12px;
            color: #667085;
            text-align: right;
        }

        .trip-card-buttons {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 8px;
        }

        /* .trip-card-details {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #0d8bff;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .trip-card-details:hover { color: #0a66cc; }
        .trip-card-details i { font-size: 11px; } */

        .trip-card-btn {
            padding: 9px 20px;
            border: none;
            border-radius: 8px;
            background: #fff;
            color: #374151;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            white-space: nowrap;
        }

        .trip-card-btn-primary {
            background: #fbbf24;
            color: #000;
        }

        .trip-card-btn-primary:hover { background: #f59e0b; }

        .trip-empty {
            background: rgba(255, 255, 255, 0.96);
            border: 1px dashed #f2b89f;
            border-radius: 22px;
            padding: 28px;
            color: #6b7280;
            box-shadow: 0 16px 38px rgba(30, 41, 59, 0.06);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 22px;
            color: #344054;
            text-decoration: none;
            font-weight: 700;
        }

        .back-link:hover {
            color: #f97316;
        }

        @media (max-width: 992px) {
            .trip-search-form {
                grid-template-columns: 1fr 48px 1fr;
            }

            .trip-search-group:nth-child(4),
            .trip-search-group:nth-child(5) {
                grid-column: span 1;
            }

            .trip-route-meta {
                grid-template-columns: repeat(2, 1fr);
            }

            .trip-layout {
                grid-template-columns: 1fr;
            }

            .trip-filter {
                position: static;
            }

            .trip-card {
                grid-template-columns: 90px 1fr auto;
            }

            .trip-card-right {
                min-width: 160px;
            }
        }

        @media (max-width: 768px) {
            .trip-page {
                padding: 32px 14px 56px;
            }

            .trip-search-card {
                padding: 16px 16px 24px;
                border-radius: 18px;
            }

            .trip-search-form {
                grid-template-columns: 1fr;
            }

            .trip-search-switch {
                height: auto;
                margin: -2px 0;
            }

            .trip-title {
                font-size: 26px;
            }

            .trip-route-meta {
                grid-template-columns: 1fr;
            }

            .trip-list-head,
            .trip-card-foot {
                flex-direction: column;
                align-items: stretch;
            }

            .trip-card {
                grid-template-columns: 80px 1fr;
            }

            .trip-card-right {
                grid-column: 1 / -1;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                border-left: none;
                border-top: 1px solid #f0f2f5;
                padding: 10px 14px;
                min-width: unset;
            }

            .trip-card-buttons {
                flex-direction: row;
                align-items: center;
                margin-top: 0;
            }
        }

    </style>
@endpush

@section('content')
    <section class="trip-page">
        <div class="trip-container">
            <div class="trip-search-shell">
                <div class="trip-search-card">
                    <div class="trip-search-top">
                        <a href="javascript:void(0)" class="trip-search-guide">Hướng dẫn mua vé</a>
                    </div>

                    <form class="trip-search-form">
                        <div class="trip-search-group">
                            <label>Điểm đi</label>
                            <input type="text" class="trip-search-field" value="{{ $route->departureStation->station_name ?? '' }}" placeholder="Chon diem di">
                        </div>

                        <div class="trip-search-switch">
                            <span><i class="fas fa-arrow-right"></i></span>
                        </div>

                        <div class="trip-search-group">
                            <label>Điểm đến</label>
                            <input type="text" class="trip-search-field" value="{{ $route->arrivalStation->station_name ?? '' }}" placeholder="Chon diem den">
                        </div>

                        <div class="trip-search-group">
                            <label>Ngày đi</label>
                            <div class="trip-search-field trip-search-date">
                                <strong>{{ $today->format('d/m/Y') }}</strong>
                                <span>{{ $today->translatedFormat('l') }}</span>
                            </div>
                        </div>

                        <div class="trip-search-group">
                            <label>Số vé</label>
                            <select class="trip-search-field">
                                <option selected>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                        </div>
                    </form>

                    <div class="trip-search-action">
                        <button type="button" class="trip-search-btn">Tìm chuyến xe</button>
                    </div>
                </div>
            </div>

            <div class="trip-header">
                <h1 class="trip-title">{{ $route->route_name }}</h1>
                <p class="trip-subtitle">
                    Danh sách chuyến đi trong vòng 1 tuần, từ {{ $today->format('d/m/Y') }} đến {{ $endDate->format('d/m/Y') }}.
                </p>

                <div class="trip-route-meta">
                    <div class="meta-card">
                        <div class="meta-label">Điểm di</div>
                        <div class="meta-value">{{ $route->departureStation->station_name ?? 'Dang cap nhat' }}</div>
                    </div>
                    <div class="meta-card">
                        <div class="meta-label">Điểm den</div>
                        <div class="meta-value">{{ $route->arrivalStation->station_name ?? 'Dang cap nhat' }}</div>
                    </div>
                    <div class="meta-card">
                        <div class="meta-label">Khoảng cach</div>
                        <div class="meta-value">{{ $route->distance }} km</div>
                    </div>
                    <div class="meta-card">
                        <div class="meta-label">Thời gian dự kiến</div>
                        <div class="meta-value">{{ $route->estimate_time }}</div>
                    </div>
                </div>
            </div>

            <div class="trip-layout">
                <aside class="trip-filter">
                    <div class="trip-filter-head">
                        <h2 class="trip-filter-title">Thông tin tuyến</h2>
{{--                        <p class="trip-filter-note">Phan nay chi doi giao dien de bo cuc giong danh sach chuyen xe hien dai. Logic loc va hien thi giu nguyen.</p>--}}
                    </div>
                    <div class="trip-filter-body">
                        <div class="filter-block">
                            <div class="filter-label">Lộ trinh</div>
                            <div class="filter-chip-wrap">
                                <span class="filter-chip">{{ $route->departureStation->station_name ?? 'Diem di' }}</span>
                                <span class="filter-chip">{{ $route->arrivalStation->station_name ?? 'Diem den' }}</span>
                            </div>
                        </div>
                        <div class="filter-block">
                            <div class="filter-label">Thời gian áp dụng</div>
                            <div class="filter-chip-wrap">
                                <span class="filter-chip">{{ $today->format('d/m/Y') }}</span>
                                <span class="filter-chip">{{ $endDate->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="filter-block">
                            <div class="filter-label">Thông số</div>
                            <div class="filter-chip-wrap">
                                <span class="filter-chip">{{ $route->distance }} km</span>
                                <span class="filter-chip">{{ $route->estimate_time }}</span>
                            </div>
                        </div>
                    </div>
                </aside>

                <div class="trip-list">
                    <div class="trip-list-head">
                        <div>
                            <h2 class="trip-list-title">Danh sách chuyến đi</h2>
                            <p class="trip-list-caption">{{ $route->departureStation->station_name ?? 'Diem di' }} - {{ $route->arrivalStation->station_name ?? 'Diem den' }}</p>
                        </div>
                        <div class="trip-summary-badge">{{ $trips->count() }} chuyến trong 7 ngày tới</div>
                    </div>

                    @if($trips->isEmpty())
                        <div class="trip-empty">
                            Hiện tại chưa có chuyến đi nào cho tuyến này trong 7 ngày tới.
                        </div>
                    @else
                        <div class="trip-cards">
                            @foreach($trips as $trip)
                                <div class="trip-card">
                                    <!-- Hình ảnh nhà xe -->
                                    <div class="trip-card-image">
                                        <i class="fas fa-bus"></i>
                                    </div>

                                    <!-- Thông tin chuyến -->
                                    <div class="trip-card-body">
                                        <!-- Tên xe + số ghế -->
                                        <div class="trip-card-bus-info">
                                            <div class="trip-card-bus-name">{{ $trip->bus->license_plate ?? 'Đang cập nhật' }}</div>
                                            <div class="trip-card-bus-seats">Ghế ngồi {{ $trip->bus->total_seats ?? '0' }} chỗ</div>
                                        </div>

                                        <!-- Giờ đi -->
                                        <div class="trip-card-stop">
                                            <div class="trip-card-stop-icon"></div>
                                            <span class="trip-card-stop-time">{{ \Carbon\Carbon::parse($trip->departure_time)->format('H:i') }}</span>
                                            <span class="trip-card-station">{{ $route->departureStation->station_name ?? 'Đang cập nhật' }}</span>
                                        </div>

                                        <!-- Khoảng cách thời gian -->
                                        <div class="trip-card-connector">
                                            <div class="trip-card-connector-line"></div>
                                            <span class="trip-card-duration-label">{{ $route->estimate_time }}</span>
                                        </div>

                                        <!-- Giờ đến -->
                                        <div class="trip-card-stop">
                                            <div class="trip-card-stop-icon arrival"></div>
                                            <span class="trip-card-stop-time">{{ \Carbon\Carbon::parse($trip->arrival_time)->format('H:i') }}</span>
                                            <span class="trip-card-station">{{ $route->arrivalStation->station_name ?? 'Đang cập nhật' }}</span>
                                        </div>
                                    </div>

                                    <!-- Giá và nút -->
                                    <div class="trip-card-right">
                                        <div class="trip-card-price">{{ number_format($trip->base_price, 0, ',', '.') }}đ</div>
                                        <div class="trip-card-available">Còn {{ $trip->available_seats }} chỗ trống</div>
                                        <div class="trip-card-buttons">
                                            <!-- <a href="javascript:void(0)" class="trip-card-details">Thông tin chi tiết <i class="fas fa-chevron-down"></i></a> -->
                                            <button type="button" class="trip-card-btn trip-card-btn-primary btn-open-seat-modal" data-trip-id="{{ $trip->id }}" data-trip-date="{{ $trip->trip_date }}" data-bus-name="{{ $trip->bus->license_plate }}" data-price="{{ $trip->base_price }}" data-departure-time="{{ $trip->departure_time }}" data-arrival-time="{{ $trip->arrival_time }}" data-route-name="{{ $route->route_name }}">Chọn chuyến</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <a href="{{ route('home') }}" class="back-link">
                        <i class="fas fa-arrow-left"></i>
                        Quay lại trang chủ
                    </a>
                </div>
            </div>
        </div>
    </section>

@include('customer.partials.seat_selection_modal')

@endsection