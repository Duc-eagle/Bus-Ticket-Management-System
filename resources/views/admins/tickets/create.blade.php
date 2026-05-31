@extends('layouts.app')

@section('title', 'Thêm mới Vé')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0 text-dark fw-bold">Thêm mới Vé</h2>
            <a href="{{ route('tickets.index') }}" class="btn btn-outline-dark">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('tickets.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <label for="customer_phone" class="form-label fw-bold text-dark">Số điện thoại KH</label>
                        <input type="tel" class="form-control bg-light @error('customer_phone') is-invalid @enderror" id="customer_phone" name="customer_phone" placeholder="Nhập số điện thoại..." required>
                        @error('customer_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-4">
                        <label for="customer_name" class="form-label fw-bold text-dark">Họ tên khách hàng</label>
                        <input type="text" class="form-control bg-light @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" placeholder="Họ tên khách hàng..." required>
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="trip_id" class="form-label fw-bold text-dark">Chuyến đi</label>
                        <select class="form-select bg-light @error('trip_id') is-invalid @enderror" id="trip_id" name="trip_id" required>
                            <option value="">-- Chọn chuyến đi --</option>
                            @foreach($trips as $trip)
                                <option value="{{ $trip->id }}" data-price="{{ $trip->base_price }}">
                                    {{ $trip->route->route_name ?? 'Tuyến' }} - {{ \Carbon\Carbon::parse($trip->trip_date)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($trip->departure_time)->format('H:i') }}
                                </option>
                            @endforeach
                        </select>
                        @error('trip_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="seat_id" class="form-label fw-bold text-dark">Ghế (Tự động tải sau khi chọn chuyến)</label>
                        <select class="form-select bg-light @error('seat_id') is-invalid @enderror" id="seat_id" name="seat_id" required disabled>
                            <option value="">-- Vui lòng chọn chuyến đi trước --</option>
                        </select>
                        @error('seat_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="payment_method_id" class="form-label fw-bold text-dark">Phương thức thanh toán</label>
                        <select class="form-select bg-light @error('payment_method_id') is-invalid @enderror" id="payment_method_id" name="payment_method_id" required>
                            <option value="">-- Chọn phương thức --</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->method_name }}</option>
                            @endforeach
                        </select>
                        @error('payment_method_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <label for="purchase_date" class="form-label fw-bold text-dark">Ngày mua</label>
                        <input type="date" class="form-control bg-light @error('purchase_date') is-invalid @enderror" id="purchase_date" name="purchase_date" value="{{ date('Y-m-d') }}" required>
                        @error('purchase_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="total" class="form-label fw-bold text-dark">Tổng tiền (Tự động cập nhật)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">₫</span>
                            <input type="text" class="form-control bg-light border-start-0 @error('total') is-invalid @enderror" id="total" name="total" placeholder="0" readonly required>
                        </div>
                        @error('total')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="status" class="form-label fw-bold text-dark">Trạng thái</label>
                        <select class="form-select bg-light @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="confirmed" selected>Đã xác nhận</option>
                            <option value="paid">Đã thanh toán</option>
                            <option value="pending_payment">Chờ thanh toán</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-5">
                    <button type="submit" class="btn btn-dark px-4 py-2 fw-bold text-uppercase">
                        <i class="fas fa-save me-2"></i>Lưu thông tin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const phoneInput = document.getElementById('customer_phone');
        const nameInput = document.getElementById('customer_name');

        phoneInput.addEventListener('blur', function() {
            const phone = this.value.trim();
            if (phone.length < 9) return;

            fetch(`/admin/users/check-phone?phone=${phone}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        nameInput.value = data.name;
                        nameInput.readOnly = true;
                        nameInput.style.backgroundColor = '#e9ecef';
                    } else {
                        nameInput.value = '';
                        nameInput.readOnly = false;
                        nameInput.style.backgroundColor = '';
                    }
                })
                .catch(err => console.error(err));
        });

        const tripSelect = document.getElementById('trip_id');
        const seatSelect = document.getElementById('seat_id');
        const totalInput = document.getElementById('total');

        tripSelect.addEventListener('change', function() {
            const tripId = this.value;
            const selectedOption = this.options[this.selectedIndex];
            const basePrice = selectedOption.dataset.price;

            // Update Total Price automatically
            if (basePrice) {
                totalInput.value = parseFloat(basePrice).toLocaleString('en-US');
            } else {
                totalInput.value = '';
            }

            // Reset and disable seat select if no trip
            if (!tripId) {
                seatSelect.innerHTML = '<option value="">-- Vui lòng chọn chuyến đi trước --</option>';
                seatSelect.disabled = true;
                return;
            }

            // Fetch available seats
            seatSelect.innerHTML = '<option value="">-- Đang tải ghế... --</option>';
            seatSelect.disabled = true;

            fetch(`/api/trips/${tripId}/seats`)
                .then(response => response.json())
                .then(data => {
                    seatSelect.innerHTML = '<option value="">-- Chọn ghế trống --</option>';
                    
                    let hasAvailable = false;
                    data.seats.forEach(seat => {
                        // Check if seat is NOT in the booked_seat_ids array
                        if (!data.booked_seat_ids.includes(seat.id)) {
                            const option = document.createElement('option');
                            option.value = seat.id;
                            option.textContent = seat.seat_code;
                            seatSelect.appendChild(option);
                            hasAvailable = true;
                        }
                    });

                    if (!hasAvailable) {
                        seatSelect.innerHTML = '<option value="">-- Đã hết ghế trống --</option>';
                    } else {
                        seatSelect.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error fetching seats:', error);
                    seatSelect.innerHTML = '<option value="">-- Lỗi tải ghế --</option>';
                });
        });
    });
</script>
@endsection
