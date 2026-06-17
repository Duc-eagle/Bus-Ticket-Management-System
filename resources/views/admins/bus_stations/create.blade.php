@extends('layouts.app')

@section('title', 'Thêm mới Bến xe')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0 text-dark fw-bold">Thêm mới Bến xe</h2>
            <a href="{{ route('bus_stations.index') }}" class="btn btn-outline-dark">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('bus_stations.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="province_id" class="form-label fw-bold text-dark">Tỉnh/Thành phố</label>
                    <select class="form-select bg-light @error('province_id') is-invalid @enderror" id="province_id" name="province_id" required>
                        <option value="">-- Chọn tỉnh/thành phố --</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}">{{ $province->province_name }}</option>
                        @endforeach
                    </select>
                    @error('province_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="station_name" class="form-label fw-bold text-dark">Tên Bến xe</label>
                    <input type="text" class="form-control bg-light @error('station_name') is-invalid @enderror" id="station_name" name="station_name" required>
                    @error('station_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="address" class="form-label fw-bold text-dark">Địa chỉ</label>
                    <!-- Thẻ div trống để React "bơm" Component Character Counter vào -->
                    <div id="character-counter-root"></div>
                    @error('address')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="phone" class="form-label fw-bold text-dark">Số điện thoại</label>
                    <input type="text" class="form-control bg-light @error('phone') is-invalid @enderror" id="phone" name="phone" required>
                    @error('phone')
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
