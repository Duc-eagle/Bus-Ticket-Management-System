@extends('layouts.layout')

@section('title', 'Lịch sử đặt vé - My Bus')

@push('styles')
    <style>
        .account-page {
            background:
                radial-gradient(circle at top left, rgba(0, 153, 255, 0.08), transparent 24%),
                linear-gradient(180deg, #f8fbff 0%, #eef4fb 100%);
            min-height: calc(100vh - 140px);
            padding: 42px 20px 72px;
        }

        .account-container {
            max-width: 1220px;
            margin: 0 auto;
        }

        .account-card {
            background: #fff;
            border: 1px solid #e4ebf5;
            border-radius: 28px;
            box-shadow: 0 24px 54px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .account-layout {
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
        }

        .account-sidebar {
            border-right: 1px solid #edf2f7;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            padding: 28px 0;
        }

        .account-user {
            margin: 0 18px 24px;
            padding: 14px 18px;
            border-radius: 16px;
            background: linear-gradient(135deg, #0d8bff 0%, #2f68ff 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .account-user-main {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            min-width: 0;
        }

        .account-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .account-user-name {
            font-size: 17px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .account-user-role {
            font-size: 12px;
            opacity: 0.9;
            margin-top: 2px;
        }

        .account-nav {
            display: grid;
            gap: 4px;
            padding: 0 18px;
        }

        .account-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 12px;
            color: #344054;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .account-nav-item:hover {
            background: #f5f8fd;
            color: #0d8bff;
        }

        .account-nav-item.active {
            background: #eef6ff;
            color: #0d8bff;
        }

        .account-nav-item.logout {
            color: #ef4444;
        }

        .account-nav-item button {
            all: unset;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
        }

        .account-content {
            padding: 34px 36px 40px;
        }

        .account-heading {
            text-align: left;
            color: #1f2430;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .account-heading i {
            color: #ff5b2e;
        }

        .history-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            transition: all 0.2s;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            align-items: center;
        }

        .history-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        .history-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .history-route {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .history-time {
            font-size: 14px;
            color: #4b5563;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .history-time span {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .history-time i {
            color: #ff5b2e;
        }

        .history-seat {
            font-size: 13px;
            color: #6b7280;
        }

        .history-price-status {
            text-align: right;
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: flex-end;
        }

        .history-price {
            font-size: 20px;
            font-weight: 800;
            color: #c2410c;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-badge.paid {
            background: #ecfdf5;
            color: #059669;
        }

        .status-badge.pending {
            background: #fffbeb;
            color: #d97706;
        }

        .status-badge.cancelled {
            background: #fef2f2;
            color: #dc2626;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 48px;
            color: #cbd5e1;
            margin-bottom: 16px;
        }

        @media (max-width: 992px) {
            .account-layout {
                grid-template-columns: 1fr;
            }

            .account-sidebar {
                border-right: 0;
                border-bottom: 1px solid #edf2f7;
            }
        }

        @media (max-width: 768px) {
            .account-page {
                padding: 28px 14px 52px;
            }

            .account-content {
                padding: 24px 18px 28px;
            }

            .history-card {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .history-price-status {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                border-top: 1px dashed #e2e8f0;
                padding-top: 12px;
            }
        }
    </style>
@endpush

@section('content')
    <section class="account-page">
        <div class="account-container">
            <div class="account-card">
                <div class="account-layout">
                    <!-- Sidebar (Replicated perfectly from account.blade.php) -->
                    <aside class="account-sidebar">
                        <div class="account-user">
                            <div class="account-user-main">
                                <div class="account-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div>
                                    <div class="account-user-name">Xin chào {{ $user->full_name }}</div>
                                    <div class="account-user-role">Khách hàng My Bus</div>
                                </div>
                            </div>
                            <i class="fas fa-chevron-down"></i>
                        </div>

                        <div class="account-nav">
                            <a href="{{ route('customer.account') }}" class="account-nav-item">
                                <i class="fas fa-user-cog"></i>
                                Thông tin tài khoản
                            </a>
                            <a href="{{ route('customer.change-password') }}" class="account-nav-item">
                                <i class="fas fa-key"></i>
                                Đổi mật khẩu
                            </a>
                            <a href="{{ route('customer.history') }}" class="account-nav-item active">
                                <i class="fas fa-ticket-alt"></i>
                                Vé của tôi
                            </a>
                            <a href="javascript:void(0)" class="account-nav-item">
                                <i class="fas fa-bullhorn"></i>
                                Thông báo
                            </a>
                            <form action="{{ route('customer.logout') }}" method="POST">
                                @csrf
                                <div class="account-nav-item logout">
                                    <button type="submit">
                                        <i class="fas fa-power-off"></i>
                                        Đăng xuất
                                    </button>
                                </div>
                            </form>
                        </div>
                    </aside>

                    <!-- Main Content -->
                    <div class="account-content">
                        <h1 class="account-heading"><i class="fas fa-history"></i> Lịch sử đặt vé</h1>

                        <!-- Tabs Navigation -->
                        <ul class="nav nav-tabs mb-4" id="historyTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold" id="current-tab" data-bs-toggle="tab" data-bs-target="#current" type="button" role="tab" aria-controls="current" aria-selected="true" style="color: #0f172a;">
                                    Hiện tại <span class="badge bg-primary rounded-pill ms-1">{{ $currentTickets->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab" aria-controls="past" aria-selected="false" style="color: #64748b;">
                                    Đã đi <span class="badge bg-secondary rounded-pill ms-1">{{ $pastTickets->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab" aria-controls="cancelled" aria-selected="false" style="color: #64748b;">
                                    Đã hủy <span class="badge bg-danger rounded-pill ms-1">{{ $cancelledTickets->count() }}</span>
                                </button>
                            </li>
                        </ul>

                        <!-- Tabs Content -->
                        <div class="tab-content" id="historyTabsContent">
                            <!-- Tab 1: Current -->
                            <div class="tab-pane fade show active" id="current" role="tabpanel" aria-labelledby="current-tab">
                                @if($currentTickets->isEmpty())
                                    <div class="empty-state text-center py-5">
                                        <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                                        <h4>Bạn chưa có chuyến đi nào sắp tới</h4>
                                        <p class="text-muted">Hãy đặt vé ngay để bắt đầu hành trình của bạn cùng My Bus!</p>
                                        <a href="{{ route('home') }}" class="btn btn-primary mt-3">Tìm chuyến xe</a>
                                    </div>
                                @else
                                    <div class="history-list">
                                        @foreach($currentTickets as $ticketName => $group)
                                            @php $firstTicket = $group->first(); @endphp
                                            <div class="history-card">
                                                <div class="history-info">
                                                    <div class="history-route">
                                                        {{ $firstTicket->trip->route->departureStation->station_name ?? 'Điểm đi' }} 
                                                        <i class="fas fa-long-arrow-alt-right mx-2 text-muted" style="font-size: 14px;"></i> 
                                                        {{ $firstTicket->trip->route->arrivalStation->station_name ?? 'Điểm đến' }}
                                                    </div>
                                                    <div class="history-time">
                                                        <span><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($firstTicket->trip->trip_date)->format('d/m/Y') }}</span>
                                                        <span><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($firstTicket->trip->departure_time)->format('H:i') }}</span>
                                                    </div>
                                                    <div class="history-seat">
                                                        <strong>Ghế:</strong> {{ $group->activeSeats ?: 'Đang cập nhật' }}
                                                        @if($group->cancelledSeats)
                                                            <span class="text-danger small ms-1">(Đã hủy: {{ $group->cancelledSeats }})</span>
                                                        @endif
                                                        &bull; 
                                                        <strong>Biển số xe:</strong> {{ $firstTicket->trip->bus->license_plate ?? 'Đang cập nhật' }} &bull;
                                                        <strong>Mã vé:</strong> {{ $ticketName }}
                                                    </div>
                                                </div>
                                                <div class="history-price-status d-flex flex-column align-items-end">
                                                    <div class="history-price mb-2">{{ number_format($group->totalPrice, 0, ',', '.') }}đ</div>
                                                    @if($firstTicket->status === 'paid' || $firstTicket->status === 'confirmed')
                                                        <div class="status-badge paid mb-2"><i class="fas fa-check-circle me-1"></i> Đã thanh toán</div>
                                                    @elseif($firstTicket->status === 'pending_payment')
                                                        <div class="status-badge pending mb-2"><i class="fas fa-clock me-1"></i> Chờ thanh toán</div>
                                                    @endif
                                                    <a href="{{ route('customer.ticket.show', $firstTicket->id) }}" class="btn btn-sm btn-outline-primary w-100">Chi tiết</a>
                                                    @if($firstTicket->status === 'pending_payment' && stripos($firstTicket->paymentMethod->method_name ?? '', 'ZaloPay') !== false)
                                                        @php $deadline = \Carbon\Carbon::parse($group->first()->created_at)->addMinutes(20); @endphp
                                                        <p class="text-danger small mb-1 mt-2 w-100 text-end" style="font-weight: 500;">Vui lòng thanh toán trước {{ $deadline->format('H:i d/m/Y') }}</p>
                                                        <a href="{{ route('checkout.retry', $ticketName) }}" class="btn btn-primary btn-sm w-100">Thanh toán ZaloPay</a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Tab 2: Past -->
                            <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
                                @if($pastTickets->isEmpty())
                                    <div class="empty-state text-center py-5">
                                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                        <h4>Bạn chưa có chuyến đi nào đã hoàn thành</h4>
                                    </div>
                                @else
                                    <div class="history-list">
                                        @foreach($pastTickets as $ticketName => $group)
                                            @php $firstTicket = $group->first(); @endphp
                                            <div class="history-card" style="opacity: 0.8;">
                                                <div class="history-info">
                                                    <div class="history-route">
                                                        {{ $firstTicket->trip->route->departureStation->station_name ?? 'Điểm đi' }} 
                                                        <i class="fas fa-long-arrow-alt-right mx-2 text-muted" style="font-size: 14px;"></i> 
                                                        {{ $firstTicket->trip->route->arrivalStation->station_name ?? 'Điểm đến' }}
                                                    </div>
                                                    <div class="history-time">
                                                        <span><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($firstTicket->trip->trip_date)->format('d/m/Y') }}</span>
                                                        <span><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($firstTicket->trip->departure_time)->format('H:i') }}</span>
                                                    </div>
                                                    <div class="history-seat">
                                                        <strong>Ghế:</strong> {{ $group->activeSeats ?: 'Đang cập nhật' }}
                                                        @if($group->cancelledSeats)
                                                            <span class="text-danger small ms-1">(Đã hủy: {{ $group->cancelledSeats }})</span>
                                                        @endif
                                                        &bull; 
                                                        <strong>Biển số xe:</strong> {{ $firstTicket->trip->bus->license_plate ?? 'Đang cập nhật' }} &bull;
                                                        <strong>Mã vé:</strong> {{ $ticketName }}
                                                    </div>
                                                </div>
                                                <div class="history-price-status d-flex flex-column align-items-end">
                                                    <div class="history-price mb-2">{{ number_format($group->totalPrice, 0, ',', '.') }}đ</div>
                                                    <div class="status-badge paid mb-2" style="background: #e2e8f0; color: #475569;"><i class="fas fa-check-double me-1"></i> Đã đi</div>
                                                    <a href="{{ route('customer.ticket.show', $firstTicket->id) }}" class="btn btn-sm btn-outline-secondary w-100">Chi tiết</a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Tab 3: Cancelled -->
                            <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                                @if($cancelledTickets->isEmpty())
                                    <div class="empty-state text-center py-5">
                                        <i class="fas fa-times-circle fa-3x text-muted mb-3"></i>
                                        <h4>Bạn không có vé nào bị hủy</h4>
                                    </div>
                                @else
                                    <div class="history-list">
                                        @foreach($cancelledTickets as $ticketName => $group)
                                            @php $firstTicket = $group->first(); @endphp
                                            <div class="history-card" style="opacity: 0.7; filter: grayscale(100%);">
                                                <div class="history-info">
                                                    <div class="history-route text-decoration-line-through">
                                                        {{ $firstTicket->trip->route->departureStation->station_name ?? 'Điểm đi' }} 
                                                        <i class="fas fa-long-arrow-alt-right mx-2 text-muted" style="font-size: 14px;"></i> 
                                                        {{ $firstTicket->trip->route->arrivalStation->station_name ?? 'Điểm đến' }}
                                                    </div>
                                                    <div class="history-time">
                                                        <span><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($firstTicket->trip->trip_date)->format('d/m/Y') }}</span>
                                                        <span><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($firstTicket->trip->departure_time)->format('H:i') }}</span>
                                                    </div>
                                                    <div class="history-seat">
                                                        <strong>Ghế:</strong> {{ $group->cancelledSeats ?: 'Đang cập nhật' }} &bull; 
                                                        <strong>Mã vé:</strong> {{ $ticketName }}
                                                    </div>
                                                </div>
                                                <div class="history-price-status d-flex flex-column align-items-end">
                                                    <div class="history-price mb-2 text-muted">{{ number_format($group->sum('total'), 0, ',', '.') }}đ</div>
                                                    <div class="status-badge cancelled mb-2"><i class="fas fa-times-circle me-1"></i> Đã hủy</div>
                                                    <a href="{{ route('customer.ticket.show', $firstTicket->id) }}" class="btn btn-sm btn-outline-danger w-100">Chi tiết</a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
