@extends('layouts.app')

@section('title', 'Thêm mới Tỉnh/Thành phố')

@section('content')
<div class="container-fluid">
    <!-- Header Area -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0 text-dark fw-bold">Thêm mới Tỉnh/Thành phố</h2>
            <a href="{{ route('provinces.index') }}" class="btn btn-outline-dark">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('provinces.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label for="province_name" class="form-label fw-bold text-dark">Tên Tỉnh/Thành phố</label>
                    <input type="text" class="form-control bg-light @error('province_name') is-invalid @enderror" id="province_name" name="province_name" placeholder="VD: Hồ Chí Minh, Hà Nội..." required>
                    @error('province_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="image" class="form-label fw-bold text-dark">Hình ảnh nổi bật</label>
                    <input type="file" name="image" id="image" class="form-control bg-light" accept="image/*">
                </div>

                <!-- Primary Action Button -->
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
