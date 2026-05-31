@extends('layouts.app')

@section('title', 'Cập nhật Người dùng')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0 text-dark fw-bold">Cập nhật Người dùng</h2>
            <a href="{{ route('users.index') }}" class="btn btn-outline-dark">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="full_name" class="form-label fw-bold text-dark">Họ Tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light @error('full_name') is-invalid @enderror" id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}" placeholder="Nhập họ tên" required>
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <label for="user_name" class="form-label fw-bold text-dark">Tên Đăng Nhập <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light @error('user_name') is-invalid @enderror" id="user_name" name="user_name" value="{{ old('user_name', $user->user_name) }}" placeholder="Nhập tên đăng nhập" required>
                        @error('user_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="email" class="form-label fw-bold text-dark">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control bg-light @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Nhập email" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <label for="phone" class="form-label fw-bold text-dark">Số Điện Thoại</label>
                        <input type="text" class="form-control bg-light @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Nhập số điện thoại">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="dob" class="form-label fw-bold text-dark">Ngày Sinh</label>
                        <input type="date" class="form-control bg-light @error('dob') is-invalid @enderror" id="dob" name="dob" value="{{ old('dob', $user->dob) }}">
                        @error('dob')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <label for="role" class="form-label fw-bold text-dark">Vai Trò <span class="text-danger">*</span></label>
                        <select class="form-select bg-light @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">Chọn vai trò</option>
                            <option value="customer" @if(old('role', $user->role) == 'customer') selected @endif>Khách hàng</option>
                            <option value="staff" @if(old('role', $user->role) == 'staff') selected @endif>Nhân viên</option>
                            <option value="admin" @if(old('role', $user->role) == 'admin') selected @endif>Admin</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="address" class="form-label fw-bold text-dark">Địa Chỉ</label>
                    <textarea class="form-control bg-light @error('address') is-invalid @enderror" id="address" name="address" placeholder="Nhập địa chỉ" rows="3">{{ old('address', $user->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-bold text-dark">Mật Khẩu Mới</label>
                    <input type="text" class="form-control bg-light @error('password') is-invalid @enderror" id="password" name="password" placeholder="Để trống nếu không muốn thay đổi">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
