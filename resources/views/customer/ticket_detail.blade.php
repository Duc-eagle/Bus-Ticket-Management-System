@extends('layouts.layout')

@push('styles')
    <style>
        .account-page {
            padding: 40px 20px 80px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .account-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .account-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .account-layout {
            display: flex;
            min-height: 600px;
        }

        .account-sidebar {
            width: 280px;
            background: #ffffff;
            border-right: 1px solid #f1f5f9;
            padding: 32px 0;
            flex-shrink: 0;
        }

        .account-user {
            padding: 0 24px 24px;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 24px;
        }

        .account-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #64748b;
            margin-bottom: 16px;
        }

        .account-user-name {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .account-user-role {
            font-size: 13px;
            color: #64748b;
        }

        .account-nav-item {
            display: flex;
            align-items: center;
            padding: 12px 24px;
            color: #475569;
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .account-nav-item i {
            width: 20px;
            margin-right: 12px;
            font-size: 16px;
            color: #94a3b8;
            transition: color 0.2s;
        }

        .account-nav-item:hover,
        .account-nav-item.active {
            color: #2563eb;
            background: #f8fafc;
            border-left-color: #2563eb;
        }

        .account-nav-item:hover i,
        .account-nav-item.active i {
            color: #2563eb;
        }

        .account-content {
            flex: 1;
            padding: 40px 48px;
            background: #fcfcfd;
        }

        .account-heading {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ticket-detail-box {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 16px;
            margin-bottom: 16px;
        }

        .ticket-code {
            font-size: 20px;
            font-weight: 700;
            color: #2563eb;
        }

        .ticket-status.paid { color: #16a34a; background: #dcfce7; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 14px; }
        .ticket-status.pending { color: #d97706; background: #fef3c7; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 14px; }
        .ticket-status.cancelled { color: #dc2626; background: #fee2e2; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 14px; }

        .ticket-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .info-group label {
            display: block;
            font-size: 13px;
            color: #64748b;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .info-group .info-val {
            font-size: 16px;
            font-weight: 600;
            color: #0f172a;
        }

        .route-path {
            background: #f8fafc;
            padding: 16px;
            border-radius: 8px;
            margin: 20px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .route-point {
            flex: 1;
        }
        
        .route-point:last-child {
            text-align: right;
        }

        .route-point h4 {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 4px 0;
        }

        .route-point p {
            font-size: 13px;
            color: #64748b;
            margin: 0;
        }

        .route-arrow {
            color: #cbd5e1;
            font-size: 24px;
            padding: 0 20px;
        }

        @media (max-width: 992px) {
            .account-layout {
                flex-direction: column;
            }
            .account-sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #f1f5f9;
            }
            .account-content {
                padding: 24px;
            }
            .ticket-info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <section class="account-page">
        <div class="account-container">
            <div class="account-card">
                <div class="account-layout">
                    <!-- Sidebar -->
                    <aside class="account-sidebar">
                        <div class="account-user">
                            <div class="account-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="account-user-name">{{ $user->full_name }}</div>
                            <div class="account-user-role">Khách hàng My Bus</div>
                        </div>

                        <div class="account-nav">
                            <a href="{{ route('customer.account') }}" class="account-nav-item">
                                <i class="fas fa-user-cog"></i> Thông tin tài khoản
                            </a>
                            <a href="{{ route('customer.change-password') }}" class="account-nav-item">
                                <i class="fas fa-key"></i> Đổi mật khẩu
                            </a>
                            <a href="{{ route('customer.history') }}" class="account-nav-item active">
                                <i class="fas fa-ticket-alt"></i> Vé của tôi
                            </a>
                            <form action="{{ route('customer.logout') }}" method="POST">
                                @csrf
                                <div class="account-nav-item logout" style="cursor: pointer;" onclick="this.parentNode.submit()">
                                    <i class="fas fa-power-off text-danger"></i> <span class="text-danger">Đăng xuất</span>
                                </div>
                            </form>
                        </div>
                    </aside>

                    <!-- Main Content -->
                    <div class="account-content">
                        <div class="account-heading">
                            <div>
                                <a href="{{ route('customer.history') }}" class="btn btn-sm btn-light me-2"><i class="fas fa-arrow-left"></i></a>
                                Chi tiết vé
                            </div>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            </div>
                        @endif

                        @php 
                            $firstTicket = $ticketGroup->first(); 
                            $activeTickets = $ticketGroup->where('status', '!=', 'cancelled');
                        @endphp

                        <div class="ticket-detail-box">
                            <div class="ticket-header">
                                <div class="ticket-code">Mã vé: {{ $firstTicket->ticket_name }}</div>
                                <div>
                                    @if($firstTicket->status === 'paid' || $firstTicket->status === 'confirmed')
                                        <span class="ticket-status paid"><i class="fas fa-check-circle"></i> Đã thanh toán</span>
                                    @elseif($firstTicket->status === 'pending_payment')
                                        <span class="ticket-status pending"><i class="fas fa-clock"></i> Chờ thanh toán</span>
                                    @elseif($firstTicket->status === 'completed')
                                        <span class="ticket-status paid" style="background:#e2e8f0;color:#475569;"><i class="fas fa-check-double"></i> Đã đi</span>
                                    @else
                                        <span class="ticket-status cancelled"><i class="fas fa-times-circle"></i> Đã hủy</span>
                                    @endif
                                </div>
                            </div>

                            <div class="route-path">
                                <div class="route-point">
                                    <p>Khởi hành</p>
                                    <h4>{{ \Carbon\Carbon::parse($firstTicket->trip->departure_time)->format('H:i') }}</h4>
                                    <p>{{ $firstTicket->trip->route->departureStation->station_name ?? 'Điểm đi' }}</p>
                                    <p>{{ \Carbon\Carbon::parse($firstTicket->trip->trip_date)->format('d/m/Y') }}</p>
                                </div>
                                <div class="route-arrow">
                                    <i class="fas fa-long-arrow-alt-right"></i>
                                </div>
                                <div class="route-point">
                                    <p>Dự kiến đến</p>
                                    <h4>{{ \Carbon\Carbon::parse($firstTicket->trip->arrival_time)->format('H:i') }}</h4>
                                    <p>{{ $firstTicket->trip->route->arrivalStation->station_name ?? 'Điểm đến' }}</p>
                                    <p>{{ \Carbon\Carbon::parse($firstTicket->trip->trip_date)->format('d/m/Y') }}</p>
                                </div>
                            </div>

                            <div class="ticket-info-grid mt-4">
                                <div class="info-group">
                                    <label>Hành khách</label>
                                    <div class="info-val">{{ $user->full_name }}</div>
                                </div>
                                <div class="info-group">
                                    <label>Số điện thoại</label>
                                    <div class="info-val">{{ $user->phone ?? 'Không có' }}</div>
                                </div>
                                <div class="info-group">
                                    <label>Biển số xe</label>
                                    <div class="info-val">{{ $firstTicket->trip->bus->license_plate ?? 'Đang cập nhật' }}</div>
                                </div>
                                <div class="info-group">
                                    <label>Vị trí ghế</label>
                                    <div class="info-val" style="color:#2563eb; font-size:18px;">{{ $activeTickets->pluck('seat.seat_code')->filter()->join(', ') ?: 'Không có ghế hợp lệ' }}</div>
                                </div>
                                <div class="info-group">
                                    <label>Phương thức thanh toán</label>
                                    <div class="info-val">{{ $firstTicket->paymentMethod->method_name ?? 'Không xác định' }}</div>
                                </div>
                                <div class="info-group">
                                    <label>Tổng tiền (Các ghế còn hiệu lực)</label>
                                    <div class="info-val" style="color:#dc2626; font-size:18px;">{{ number_format($activeTickets->sum('total'), 0, ',', '.') }} đ</div>
                                </div>
                            </div>
                        </div>

                        <!-- Individual Seats List -->
                        <div class="ticket-detail-box mt-4">
                            <h4 class="mb-4" style="font-size: 18px; font-weight: 700; color: #0f172a; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">Chi tiết từng ghế</h4>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Mã Ghế</th>
                                            <th>Giá tiền</th>
                                            <th>Trạng thái</th>
                                            <th class="text-end">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ticketGroup as $ticket)
                                            <tr>
                                                <td><strong style="color: #2563eb; font-size: 16px;">{{ $ticket->seat->seat_code }}</strong></td>
                                                <td class="text-danger fw-bold">{{ number_format($ticket->total, 0, ',', '.') }} đ</td>
                                                <td>
                                                    @if($ticket->status === 'paid' || $ticket->status === 'confirmed')
                                                        <span class="badge bg-success">Đã thanh toán</span>
                                                    @elseif($ticket->status === 'pending_payment')
                                                        <span class="badge bg-warning text-dark">Chờ thanh toán</span>
                                                    @elseif($ticket->status === 'completed')
                                                        <span class="badge bg-secondary">Đã đi</span>
                                                    @else
                                                        <span class="badge bg-danger">Đã hủy</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    @if(in_array($ticket->status, ['pending_payment', 'paid', 'confirmed']))
                                                        <form action="{{ route('customer.ticket.cancel', $ticket->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy ghế {{ $ticket->seat->seat_code }} không? Hành động này không thể hoàn tác.');">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hủy ghế này</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
