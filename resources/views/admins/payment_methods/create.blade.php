@extends('layouts.app')

@section('title', 'Thêm mới Phương thức thanh toán')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0 text-dark fw-bold">Thêm mới Phương thức thanh toán</h2>
            <a href="{{ route('paymentMethods.index') }}" class="btn btn-outline-dark">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('paymentMethods.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="method_name" class="form-label fw-bold text-dark">Tên Phương thức</label>
                    <input type="text" class="form-control bg-light @error('method_name') is-invalid @enderror" id="method_name" name="method_name" placeholder="VD: Tiền mặt, Chuyển khoản, Ví điện tử" required>
                    @error('method_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="method_description" class="form-label fw-bold text-dark">Mô tả phương thức</label>
                    <input type="text" class="form-control bg-light @error('method_description') is-invalid @enderror" id="method_description" name="method_description" required>
                    @error('method_description')
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
