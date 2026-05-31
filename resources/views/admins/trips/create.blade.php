@extends('layouts.app')

@section('title', 'Thêm mới Chuyến đi')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0 text-dark fw-bold">Thêm mới Chuyến đi</h2>
            <a href="{{ route('trips.index') }}" class="btn btn-outline-dark">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('trips.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="trip_name" class="form-label fw-bold text-dark">Tên Chuyến đi</label>
                    <input type="text" class="form-control bg-light @error('trip_name') is-invalid @enderror" id="trip_name" name="trip_name" required>
                    @error('trip_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="bus_id" class="form-label fw-bold text-dark">Xe</label>
                    <select class="form-select bg-light @error('bus_id') is-invalid @enderror" id="bus_id" name="bus_id" required>
                        <option value="">-- Chọn xe --</option>
                        @foreach($buses as $bus)
                            <option value="{{ $bus->id }}">{{ $bus->license_plate }}</option>
                        @endforeach
                    </select>
                    @error('bus_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="route_id" class="form-label fw-bold text-dark">Tuyến đường</label>
                    <select class="form-select bg-light @error('route_id') is-invalid @enderror" id="route_id" name="route_id" required>
                        <option value="">-- Chọn tuyến đường --</option>
                        @foreach($routes as $route)
                            <option value="{{ $route->id }}">{{ $route->route_name }}</option>
                        @endforeach
                    </select>
                    @error('route_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <label for="trip_date" class="form-label fw-bold text-dark">Ngày đi</label>
                        <input type="date" class="form-control bg-light @error('trip_date') is-invalid @enderror" id="trip_date" name="trip_date" min="{{ \Carbon\Carbon::today()->toDateString() }}" required>
                        @error('trip_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-4">
                        <label for="departure_time" class="form-label fw-bold text-dark">Giờ đi</label>
                        <input type="time" class="form-control bg-light @error('departure_time') is-invalid @enderror" id="departure_time" name="departure_time" required>
                        @error('departure_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-4">
                        <label for="arrival_time" class="form-label fw-bold text-dark">Giờ đến</label>
                        <input type="time" class="form-control bg-light @error('arrival_time') is-invalid @enderror" id="arrival_time" name="arrival_time" required>
                        @error('arrival_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="base_price" class="form-label fw-bold text-dark">Giá cơ bản</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">₫</span>
                        <input type="text" class="form-control bg-light border-start-0 @error('base_price') is-invalid @enderror" id="base_price" name="base_price" placeholder="0" required>
                    </div>
                    @error('base_price')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="status" class="form-label fw-bold text-dark">Trạng thái</label>
                    <select class="form-select bg-light @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="scheduled">Lên lịch</option>
                        <option value="running">Đang chạy</option>
                        <option value="completed">Hoàn thành</option>
                        <option value="cancelled">Hủy</option>
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

<script>
    const totalInput = document.getElementById('base_price');
    totalInput.addEventListener('input', function(e) {
        let value = this.value.replace(/[^\d.]/g, '');
        if (value) {
            let number = parseFloat(value);
            let formatted = Math.floor(number).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            this.value = formatted;
        }
    });
    totalInput.closest('form').addEventListener('submit', function(e) {
        const cleanValue = totalInput.value.replace(/,/g, '');
        totalInput.value = cleanValue;
    });
</script>
@endsection
