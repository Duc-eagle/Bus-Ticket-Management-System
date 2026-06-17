@extends('layouts.layout')

@section('title', 'Kết quả tìm kiếm chuyến xe - My Bus')

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
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 15px;
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

        .trip-search-btn {
            min-width: 196px;
            height: 52px;
            border: 0;
            border-radius: 8px;
            background: linear-gradient(135deg, #c41e3a 0%, #a01830 100%);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            box-shadow: 0 16px 26px rgba(196, 30, 58, 0.25);
            cursor: pointer;
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
            margin-bottom: 0px;
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

        .trip-card-body {
            padding: 14px 18px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

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

        .trip-card-btn {
            padding: 9px 20px;
            border: none;
            border-radius: 8px;
            background: #fbbf24;
            color: #000;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            white-space: nowrap;
        }

        .trip-card-btn:hover { background: #f59e0b; }

        .trip-empty {
            background: rgba(255, 255, 255, 0.96);
            border: 1px dashed #f2b89f;
            border-radius: 22px;
            padding: 28px;
            color: #6b7280;
            box-shadow: 0 16px 38px rgba(30, 41, 59, 0.06);
            text-align: center;
            font-size: 16px;
        }

        @media (max-width: 992px) {
            .trip-search-form {
                grid-template-columns: 1fr 1fr;
            }
            .trip-layout {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .trip-search-form {
                grid-template-columns: 1fr;
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
            }
        }
    </style>
@endpush

@section('content')
    <section class="trip-page">
        <div class="trip-container">
            <div class="trip-search-shell">
                <div class="trip-search-card">
                    <form action="{{ route('customer.search') }}" method="GET" class="trip-search-form">
                        <div class="trip-search-group">
                            <label>Điểm đi</label>
                            <select name="departure_id" class="trip-search-field">
                                <option value="">Tất cả</option>
                                @foreach($stations as $station)
                                    <option value="{{ $station->id }}" {{ $request->departure_id == $station->id ? 'selected' : '' }}>{{ $station->station_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="trip-search-group">
                            <label>Điểm đến</label>
                            <select name="arrival_id" class="trip-search-field">
                                <option value="">Tất cả</option>
                                @foreach($stations as $station)
                                    <option value="{{ $station->id }}" {{ $request->arrival_id == $station->id ? 'selected' : '' }}>{{ $station->station_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="trip-search-group">
                            <label>Ngày đi</label>
                            <input type="date" name="date" class="trip-search-field" value="{{ $request->date }}" min="{{ date('Y-m-d') }}">
                        </div>

                        <button type="submit" class="trip-search-btn">TÌM CHUYẾN XE</button>
                    </form>
                </div>
            </div>

            <div class="trip-header">
                <h1 class="trip-title">Kết quả tìm kiếm</h1>
                <p class="trip-subtitle">
                    @if($request->date)
                        Tìm thấy <strong>{{ $trips->count() }}</strong> chuyến xe vào ngày {{ \Carbon\Carbon::parse($request->date)->format('d/m/Y') }}
                    @else
                        Tìm thấy <strong>{{ $trips->count() }}</strong> chuyến xe
                    @endif
                </p>
            </div>

            <div class="trip-layout">
                <aside class="trip-filter">
                    <div class="trip-filter-head">
                        <h2 class="trip-filter-title">Bộ lọc tìm kiếm</h2>
                    </div>
                    <div class="trip-filter-body">
                        <div class="filter-block">
                            <div class="filter-label">Thông tin</div>
                            <div class="filter-chip-wrap">
                                <span class="filter-chip">Giá rẻ nhất</span>
                                <span class="filter-chip">Sớm nhất</span>
                                <span class="filter-chip">Muộn nhất</span>
                            </div>
                        </div>
                    </div>
                </aside>

                <div class="trip-list">
                    <div class="trip-list-head">
                        <div>
                            <h2 class="trip-list-title">Danh sách chuyến xe</h2>
                        </div>
                        <div class="trip-summary-badge">{{ $trips->count() }} chuyến khả dụng</div>
                    </div>

                    @if($trips->isEmpty())
                        <div class="trip-empty">
                            Không tìm thấy chuyến xe nào phù hợp với yêu cầu tìm kiếm của bạn. <br>
                            Vui lòng thử lại với ngày hoặc địa điểm khác.
                        </div>
                    @else
                        <div class="trip-cards">
                            @foreach($trips as $trip)
                                <div class="trip-card">
                                    <div class="trip-card-image">
                                        @if($trip->bus->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $trip->bus->images->first()->image_path) }}" alt="Bus" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <i class="fas fa-bus"></i>
                                        @endif
                                    </div>

                                    <div class="trip-card-body">
                                        <div class="trip-card-bus-info">
                                            <div class="trip-card-bus-name">{{ $trip->bus->license_plate ?? 'Đang cập nhật' }}</div>
                                            <div class="trip-card-bus-seats">Loại xe {{ $trip->bus->total_seats ?? '0' }} chỗ</div>
                                        </div>

                                        <div class="trip-card-stop">
                                            <div class="trip-card-stop-icon"></div>
                                            <span class="trip-card-stop-time">{{ \Carbon\Carbon::parse($trip->departure_time)->format('H:i') }} <span style="font-size: 13px; color: #64748b; font-weight: 500;">({{ \Carbon\Carbon::parse($trip->trip_date)->format('d/m/Y') }})</span></span>
                                            <span class="trip-card-station">{{ $trip->route->departureStation->station_name ?? 'Đang cập nhật' }}</span>
                                        </div>

                                        <div class="trip-card-connector">
                                            <div class="trip-card-connector-line"></div>
                                            <span class="trip-card-duration-label">{{ $trip->route->estimate_time }}</span>
                                        </div>

                                        <div class="trip-card-stop">
                                            <div class="trip-card-stop-icon arrival"></div>
                                            <span class="trip-card-stop-time">{{ \Carbon\Carbon::parse($trip->arrival_time)->format('H:i') }}</span>
                                            <span class="trip-card-station">{{ $trip->route->arrivalStation->station_name ?? 'Đang cập nhật' }}</span>
                                        </div>
                                    </div>

                                    <div class="trip-card-right">
                                        <div class="trip-card-price">{{ number_format($trip->base_price, 0, ',', '.') }}đ</div>
                                        <div class="trip-card-available">
                                            @if($trip->available_seats > 0)
                                                Còn {{ $trip->available_seats }} chỗ trống
                                            @else
                                                <span style="color: red;">Đã hết chỗ</span>
                                            @endif
                                        </div>
                                        <div class="trip-card-buttons">
                                            @if($trip->bus->images->isNotEmpty())
                                                <button type="button" class="trip-card-btn" style="background: #e2e8f0; color: #475569;" data-bs-toggle="modal" data-bs-target="#busImagesModal{{ $trip->bus->id }}">Xem ảnh xe</button>
                                            @endif
                                            @if($trip->available_seats > 0)
                                                <button type="button" class="trip-card-btn btn-open-seat-modal" data-trip-id="{{ $trip->id }}" data-price="{{ $trip->base_price }}">Chọn chỗ</button>
                                            @else
                                                <button type="button" class="trip-card-btn" style="background: #ccc; cursor: not-allowed;" disabled>Đã hết chỗ</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @include('customer.partials.seat_selection_modal')

    <!-- Bus Images Modals -->
    @foreach($trips->unique('bus_id') as $trip)
        @if($trip->bus->images->isNotEmpty())
            <div class="modal fade" id="busImagesModal{{ $trip->bus->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold">Hình ảnh xe: {{ $trip->bus->license_plate }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="carouselBus{{ $trip->bus->id }}" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner rounded">
                                    @foreach($trip->bus->images as $index => $image)
                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" class="d-block w-100" alt="Bus Image" style="object-fit: cover; height: 300px;">
                                        </div>
                                    @endforeach
                                </div>
                                @if($trip->bus->images->count() > 1)
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselBus{{ $trip->bus->id }}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselBus{{ $trip->bus->id }}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection
