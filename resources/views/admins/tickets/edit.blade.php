@extends('layouts.app')

@section('title', 'Cập nhật Vé')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0 text-dark fw-bold">Cập nhật Vé</h2>
            <a href="{{ route('tickets.index') }}" class="btn btn-outline-dark">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="ticket_name" class="form-label fw-bold text-dark">Tên Vé</label>
                        <input type="text" class="form-control bg-light @error('ticket_name') is-invalid @enderror" id="ticket_name" name="ticket_name" value="{{ $ticket->ticket_name }}" required>
                        @error('ticket_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <label for="user_id" class="form-label fw-bold text-dark">Khách hàng</label>
                        <select class="form-select bg-light @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                            <option value="">-- Chọn khách hàng --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $user->id == $ticket->user_id ? 'selected' : '' }}>{{ $user->full_name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="trip_id" class="form-label fw-bold text-dark">Chuyến đi</label>
                        <select class="form-select bg-light @error('trip_id') is-invalid @enderror" id="trip_id" name="trip_id" required>
                            <option value="">-- Chọn chuyến đi --</option>
                            @foreach($trips as $trip)
                                <option value="{{ $trip->id }}" {{ $trip->id == $ticket->trip_id ? 'selected' : '' }}>{{ $trip->trip_name }}</option>
                            @endforeach
                        </select>
                        @error('trip_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="seat_id" class="form-label fw-bold text-dark">Ghế</label>
                        <select class="form-select bg-light @error('seat_id') is-invalid @enderror" id="seat_id" name="seat_id" required>
                            <option value="">-- Chọn ghế --</option>
                            @foreach($seats as $seat)
                                <option value="{{ $seat->id }}" {{ $seat->id == $ticket->seat_id ? 'selected' : '' }}>{{ $seat->bus->license_plate }} - {{ $seat->seat_code }}</option>
                            @endforeach
                        </select>
                        @error('seat_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="payment_method_id" class="form-label fw-bold text-dark">Phương thức thanh toán</label>
                        <select class="form-select bg-light @error('payment_method_id') is-invalid @enderror" id="payment_method_id" name="payment_method_id" required>
                            <option value="">-- Chọn phương thức --</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->id }}" {{ $method->id == $ticket->payment_method_id ? 'selected' : '' }}>{{ $method->method_name }}</option>
                            @endforeach
                        </select>
                        @error('payment_method_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="status" class="form-label fw-bold text-dark">Trạng thái</label>
                        <select class="form-select bg-light @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="pending_payment" {{ $ticket->status == 'pending_payment' ? 'selected' : '' }}>Chờ thanh toán</option>
                            <option value="paid" {{ $ticket->status == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="confirmed" {{ $ticket->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="cancelled" {{ $ticket->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="purchase_date" class="form-label fw-bold text-dark">Ngày mua</label>
                        <input type="date" class="form-control bg-light @error('purchase_date') is-invalid @enderror" id="purchase_date" name="purchase_date" value="{{ $ticket->purchase_date }}" required>
                        @error('purchase_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="total" class="form-label fw-bold text-dark">Tổng tiền</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">₫</span>
                            <input type="text" class="form-control bg-light border-start-0 @error('total') is-invalid @enderror" id="total" name="total" value="{{ $ticket->total }}" required>
                        </div>
                        @error('total')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-5">
                    <button type="submit" class="btn btn-dark px-4 py-2 fw-bold text-uppercase">
                        <i class="fas fa-save me-2"></i>Cập nhật thông tin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
