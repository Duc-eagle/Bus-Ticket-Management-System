@extends('layouts.layout')

@section('title', 'Đặt vé thành công')

@push('styles')
<style>
    .success-page {
        background: #f4f7fb;
        min-height: 100vh;
        padding: 60px 20px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .success-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(30, 41, 59, 0.08);
        padding: 40px;
        max-width: 500px;
        width: 100%;
        text-align: center;
    }
    .success-icon {
        width: 80px;
        height: 80px;
        background: #ecfdf5;
        color: #10b981;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        margin: 0 auto 24px;
        box-shadow: 0 0 0 10px rgba(16, 185, 129, 0.1);
    }
    .success-title {
        color: #111827;
        font-size: 24px;
        font-weight: 800;
        margin-bottom: 8px;
    }
    .success-desc {
        color: #6b7280;
        font-size: 15px;
        margin-bottom: 32px;
    }
    .success-details {
        background: #f9fafb;
        border: 1px dashed #d1d5db;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 32px;
        text-align: left;
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    .detail-row:last-child {
        margin-bottom: 0;
        padding-top: 12px;
        border-top: 1px dashed #d1d5db;
    }
    .detail-label {
        color: #4b5563;
        font-weight: 500;
    }
    .detail-value {
        color: #111827;
        font-weight: 700;
    }
    .detail-value.highlight {
        color: #c2410c;
        font-size: 18px;
    }
    .btn-home {
        display: block;
        width: 100%;
        padding: 14px;
        background: #111827;
        color: #fff;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-home:hover {
        background: #374151;
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="success-page">
    <div class="success-card">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        <h1 class="success-title">Đặt vé thành công!</h1>
        <p class="success-desc">Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi. Ghế của bạn đã được thanh toán và giữ chỗ thành công.</p>
        
        <div class="success-details">
            <div class="detail-row">
                <span class="detail-label">Mã ghế:</span>
                @php
                    $seatCodes = \App\Models\Seat::whereIn('id', explode(',', $seatIds))->pluck('seat_code')->implode(', ');
                @endphp
                <span class="detail-value text-primary">{{ $seatCodes }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tổng thanh toán:</span>
                <span class="detail-value highlight">{{ number_format($total, 0, ',', '.') }}đ</span>
            </div>
        </div>

        <a href="{{ route('home') }}" class="btn-home"><i class="fas fa-home me-2"></i> Quay lại trang chủ</a>
    </div>
</div>
@endsection
