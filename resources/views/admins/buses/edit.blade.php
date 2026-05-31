@extends('layouts.app')

@section('title', 'Cập nhật Xe')

@section('content')
<div class="container-fluid">
    <!-- Header Area -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0 text-dark fw-bold">Cập nhật Xe</h2>
            <a href="{{ route('buses.index') }}" class="btn btn-outline-dark">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('buses.update', $bus) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="license_plate" class="form-label fw-bold text-dark">Biển số xe</label>
                    <input type="text" class="form-control bg-light @error('license_plate') is-invalid @enderror" id="license_plate" name="license_plate" value="{{ $bus->license_plate }}" required>
                    @error('license_plate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="bus_name" class="form-label fw-bold text-dark">Tên xe</label>
                    <input type="text" class="form-control bg-light @error('bus_name') is-invalid @enderror" id="bus_name" name="bus_name" value="{{ $bus->bus_name }}" required>
                    @error('bus_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="total_seats" class="form-label fw-bold text-dark">Tổng số ghế</label>
                    <input type="number" class="form-control bg-light @error('total_seats') is-invalid @enderror" id="total_seats" name="total_seats" value="{{ $bus->total_seats }}" min="1" required>
                    @error('total_seats')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="images" class="form-label fw-bold text-dark">Thêm hình ảnh xe (Có thể chọn nhiều)</label>
                    <input type="file" name="images[]" id="images" multiple class="form-control bg-light" accept="image/*">
                    @if($bus->images->count() > 0)
                        <div class="mt-3">
                            <p class="text-muted small mb-2">Hình ảnh hiện tại:</p>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach($bus->images as $image)
                                    <img src="{{ asset('storage/' . $image->image_path) }}" width="100" class="rounded border shadow-sm" style="object-fit: cover; height: 75px;" alt="Bus Image">
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="has_beds" name="has_beds" value="1" {{ $bus->has_beds ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold text-dark" for="has_beds">Xe có giường nằm</label>
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
