@extends('layouts.app')

@section('title', 'Hồ sơ quản trị viên')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0 text-dark fw-bold">Hồ sơ cá nhân</h2>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Admin Details Card -->
        <div class="col-md-5 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom pb-0 pt-4 px-4">
                    <h5 class="fw-bold text-dark"><i class="fas fa-id-card me-2"></i>Thông tin tài khoản</h5>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="bg-dark text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 100px; height: 100px; font-size: 3rem;">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h4 class="mt-3 fw-bold text-dark">{{ $user->full_name }}</h4>
                        <span class="badge bg-dark px-3 py-2 text-uppercase">{{ $user->role == 'admin' ? 'Quản trị viên' : 'Nhân viên bán vé' }}</span>
                    </div>

                    <ul class="list-group list-group-flush mt-4">
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent border-bottom">
                            <span class="text-muted"><i class="fas fa-user me-2"></i>Tên đăng nhập</span>
                            <span class="fw-bold text-dark">{{ $user->user_name }}</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent border-bottom">
                            <span class="text-muted"><i class="fas fa-envelope me-2"></i>Email</span>
                            <span class="fw-bold text-dark">{{ $user->email }}</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent border-bottom">
                            <span class="text-muted"><i class="fas fa-phone me-2"></i>Số điện thoại</span>
                            <span class="fw-bold text-dark">{{ $user->phone ?? 'Chưa cập nhật' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Change Password Card -->
        <div class="col-md-7 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom pb-0 pt-4 px-4">
                    <h5 class="fw-bold text-dark"><i class="fas fa-lock me-2"></i>Đổi mật khẩu</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.profile.password') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="current_password" class="form-label fw-bold text-dark">Mật khẩu hiện tại</label>
                            <input type="password" class="form-control bg-light @error('current_password') is-invalid @enderror" id="current_password" name="current_password" placeholder="Nhập mật khẩu hiện tại">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold text-dark">Mật khẩu mới</label>
                            <input type="password" class="form-control bg-light @error('password') is-invalid @enderror" id="password" name="password" placeholder="Nhập mật khẩu mới">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold text-dark">Xác nhận mật khẩu mới</label>
                            <input type="password" class="form-control bg-light" id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu mới">
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-dark py-2 fw-bold text-uppercase">Cập nhật mật khẩu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
