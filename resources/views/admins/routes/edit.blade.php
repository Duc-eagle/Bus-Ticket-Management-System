@extends('layouts.app')

@section('title', 'Cập nhật Tuyến đường')

@section('content')
<div class="container-fluid">
    <!-- Header Area -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0 text-dark fw-bold">Cập nhật Tuyến đường</h2>
            <a href="{{ route('routes.index') }}" class="btn btn-outline-dark">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('routes.update', $route) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="route_name" class="form-label fw-bold text-dark">Tên Tuyến đường</label>
                    <input type="text" class="form-control bg-light @error('route_name') is-invalid @enderror" id="route_name" name="route_name" value="{{ $route->route_name }}" required>
                    @error('route_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="departure_location" class="form-label fw-bold text-dark">Điểm đi</label>
                    <select class="form-select bg-light @error('departure_location') is-invalid @enderror" id="departure_location" name="departure_location" required>
                        <option value="">-- Chọn bến xe --</option>
                        @foreach($busStations as $station)
                            <option value="{{ $station->id }}" {{ $station->id == $route->departure_location ? 'selected' : '' }}>{{ $station->station_name }}</option>
                        @endforeach
                    </select>
                    @error('departure_location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="arrival_location" class="form-label fw-bold text-dark">Điểm đến</label>
                    <select class="form-select bg-light @error('arrival_location') is-invalid @enderror" id="arrival_location" name="arrival_location" required>
                        <option value="">-- Chọn bến xe --</option>
                        @foreach($busStations as $station)
                            <option value="{{ $station->id }}" {{ $station->id == $route->arrival_location ? 'selected' : '' }}>{{ $station->station_name }}</option>
                        @endforeach
                    </select>
                    @error('arrival_location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="distance" class="form-label fw-bold text-dark">Khoảng cách (km)</label>
                    <input type="number" class="form-control bg-light @error('distance') is-invalid @enderror" id="distance" name="distance" value="{{ $route->distance }}" min="1" required>
                    @error('distance')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="estimate_time" class="form-label fw-bold text-dark">Thời gian ước tính</label>
                    <input type="text" class="form-control bg-light @error('estimate_time') is-invalid @enderror" id="estimate_time" name="estimate_time" value="{{ $route->estimate_time }}" required>
                    @error('estimate_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="image" class="form-label fw-bold text-dark">Hình ảnh nổi bật</label>
                    <input type="file" name="image" id="image" class="form-control bg-light" accept="image/*">
                    @if($route->image_path)
                        <div class="mt-3">
                            <p class="text-muted small mb-2">Hình ảnh hiện tại:</p>
                            <img src="{{ asset('storage/' . $route->image_path) }}" width="150" class="rounded border shadow-sm" alt="Current Image">
                        </div>
                    @endif
                </div>

                <!-- Primary Action Button -->
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
