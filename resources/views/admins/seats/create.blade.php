@extends('layouts.app')

@section('title', 'Thêm mới Ghế')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0 text-dark fw-bold">Thêm mới Ghế</h2>
            <a href="{{ route('seats.index') }}" class="btn btn-outline-dark">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('seats.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="bus_id" class="form-label fw-bold text-dark">Xe</label>
                    <select class="form-select bg-light @error('bus_id') is-invalid @enderror" id="bus_id" name="bus_id" required>
                        <option value="">-- Chọn xe --</option>
                        @foreach($buses as $bus)
                            <option value="{{ $bus->id }}" {{ request('bus_id') == $bus->id ? 'selected' : '' }}>{{ $bus->license_plate }}</option>
                        @endforeach
                    </select>
                    @error('bus_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="seat_code" class="form-label fw-bold text-dark">Mã Ghế</label>
                    <input type="text" class="form-control bg-light @error('seat_code') is-invalid @enderror" id="seat_code" name="seat_code" placeholder="VD: A1, A2, B1" required>
                    @error('seat_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="status" class="form-label fw-bold text-dark">Trạng thái</label>
                    <select class="form-select bg-light @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="available">Trống</option>
                        <option value="booked">Đã đặt</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
@endsection
