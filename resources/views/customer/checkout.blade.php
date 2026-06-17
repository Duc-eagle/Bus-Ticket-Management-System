@extends('layouts.layout')

@section('title', 'Thanh toán & Đặt vé')

@push('styles')
<style>
    .checkout-page {
        background: #f4f7fb;
        min-height: 100vh;
        padding: 48px 20px;
    }
    .checkout-container {
        max-width: 1000px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }
    .checkout-section {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(30, 41, 59, 0.05);
        padding: 24px;
        margin-bottom: 24px;
    }
    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2430;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-title i {
        color: #ff5b2e;
    }
    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0f2f5;
    }
    .summary-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .summary-label {
        color: #667085;
        font-size: 14px;
    }
    .summary-value {
        font-weight: 600;
        color: #1f2430;
        text-align: right;
    }
    .total-price-box {
        background: #fff7f2;
        border: 1px dashed #ff5b2e;
        border-radius: 12px;
        padding: 16px;
        margin-top: 20px;
    }
    .total-price-label {
        font-size: 14px;
        color: #ff5b2e;
        font-weight: 600;
    }
    .total-price-amount {
        font-size: 28px;
        font-weight: 800;
        color: #c2410c;
        margin-top: 4px;
    }
    .form-label {
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }
    .form-control[readonly] {
        background-color: #f9fafb;
        cursor: not-allowed;
    }
    .payment-option {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .payment-option:hover {
        border-color: #ff5b2e;
        background: #fffaf7;
    }
    .payment-option.active {
        border-color: #ff5b2e;
        background: #fffaf7;
        box-shadow: 0 0 0 2px rgba(255, 91, 46, 0.2);
    }
    .payment-icon {
        width: 40px;
        height: 40px;
        background: #f0f2f5;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1f2430;
        font-size: 20px;
    }
    .payment-info h6 {
        margin: 0 0 4px;
        font-weight: 600;
        color: #1f2430;
    }
    .payment-info p {
        margin: 0;
        font-size: 12px;
        color: #667085;
    }
    .btn-checkout {
        width: 100%;
        padding: 16px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #ff5b2e 0%, #ff7a3d 100%);
        color: #fff;
        font-size: 16px;
        font-weight: 700;
        box-shadow: 0 16px 26px rgba(255, 107, 53, 0.25);
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 30px rgba(255, 107, 53, 0.35);
    }
    
    @media (max-width: 992px) {
        .checkout-container {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<section class="checkout-page">
    <div class="checkout-container">
        <!-- Cột trái: Thông tin chuyến và Thông tin khách hàng -->
        <div class="checkout-left">
            <div class="checkout-section">
                <h3 class="section-title"><i class="fas fa-bus"></i> Thông tin chuyến đi</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="summary-item">
                            <span class="summary-label">Tuyến xe</span>
                            <span class="summary-value">{{ $trip->route->route_name }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Điểm đón</span>
                            <span class="summary-value">{{ $trip->route->departureStation->station_name ?? 'Đang cập nhật' }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Điểm trả</span>
                            <span class="summary-value">{{ $trip->route->arrivalStation->station_name ?? 'Đang cập nhật' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="summary-item">
                            <span class="summary-label">Ngày đi</span>
                            <span class="summary-value">{{ \Carbon\Carbon::parse($trip->trip_date)->format('d/m/Y') }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Giờ xuất phát</span>
                            <span class="summary-value">{{ \Carbon\Carbon::parse($trip->departure_time)->format('H:i') }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Biển số xe</span>
                            <span class="summary-value">{{ $trip->bus->license_plate }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="checkout-section">
                <h3 class="section-title"><i class="fas fa-user"></i> Thông tin khách hàng</h3>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" name="customer_name" value="{{ Auth::user()->full_name }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" name="customer_phone" value="{{ Auth::user()->phone }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="customer_email" value="{{ Auth::user()->email }}">
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0" style="font-size: 13px;">
                    * Thông tin được lấy tự động từ tài khoản của bạn để đảm bảo vé được gửi đúng người nhận.
                </p>
            </div>
        </div>

        <!-- Cột phải: Thanh toán và Tổng tiền -->
        <div class="checkout-right">
            <div class="checkout-section">
                <h3 class="section-title"><i class="fas fa-ticket-alt"></i> Chi tiết vé</h3>
                
                <div class="summary-item">
                    <span class="summary-label">Ghế đã chọn</span>
                    <span class="summary-value text-primary">
                        @foreach($seats as $seat)
                            {{ $seat->seat_code }}{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    </span>
                </div>
                
                <div class="summary-item">
                    <span class="summary-label">Số lượng vé</span>
                    <span class="summary-value">{{ $seats->count() }} vé</span>
                </div>
                
                <div class="summary-item">
                    <span class="summary-label">Giá mỗi vé</span>
                    <span class="summary-value">{{ number_format($trip->base_price, 0, ',', '.') }}đ</span>
                </div>

                <div class="total-price-box">
                    <div class="total-price-label">TỔNG THANH TOÁN</div>
                    <div class="total-price-amount">{{ number_format($totalPrice, 0, ',', '.') }}đ</div>
                </div>
            </div>

            <form action="{{ route('customer.checkout.process') }}" method="POST" id="finalCheckoutForm">
                @csrf
                <div class="checkout-section">
                    <h3 class="section-title"><i class="fas fa-credit-card"></i> Phương thức thanh toán</h3>
                    
                    @foreach($paymentMethods as $index => $method)
                    <label class="payment-option {{ $index === 0 ? 'active' : '' }}">
                        <input type="radio" name="payment_method_id" value="{{ $method->id }}" class="form-check-input" {{ $index === 0 ? 'checked' : '' }} style="display:none;">
                        <div class="payment-icon">
                            @if(stripos($method->method_name, 'momo') !== false)
                                <i class="fas fa-wallet text-pink-500" style="color: #a50064;"></i>
                            @elseif(stripos($method->method_name, 'chuyển khoản') !== false || stripos($method->method_name, 'ngân hàng') !== false)
                                <i class="fas fa-university text-primary"></i>
                            @else
                                <i class="fas fa-money-bill-wave text-success"></i>
                            @endif
                        </div>
                        <div class="payment-info">
                            <h6>{{ $method->method_name }}</h6>
                            <p>{{ $method->method_description ?? 'Thanh toán an toàn & tiện lợi' }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>

                <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                <input type="hidden" name="seat_ids" value="{{ $seats->pluck('id')->implode(',') }}">
                
                <button type="submit" class="btn-checkout">Xác nhận đặt vé & Thanh toán</button>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentOptions = document.querySelectorAll('.payment-option');
        
        paymentOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Xóa active khỏi tất cả
                paymentOptions.forEach(opt => opt.classList.remove('active'));
                
                // Thêm active cho option được chọn
                this.classList.add('active');
                
                // Set radio checked
                const radio = this.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;
            });
        });

        // Bắt sự kiện submit form (chuẩn bị cho Phase 4)
        document.getElementById('finalCheckoutForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('.btn-checkout');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        });
    });
</script>
@endpush
