@extends('layouts.layout')

@section('title', 'Đăng ký - My Bus')

@push('styles')
    <style>
        .register-page {
            background: radial-gradient(circle at top left, rgba(0, 153, 255, 0.08), transparent 24%),
                        linear-gradient(180deg, #f8fbff 0%, #eef4fb 100%);
            min-height: calc(100vh - 140px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .register-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 24px 54px rgba(15, 23, 42, 0.08);
            width: 100%;
            max-width: 500px;
            padding: 40px;
        }

        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .register-header h1 {
            font-size: 28px;
            font-weight: 800;
            color: #1f2430;
            margin-bottom: 10px;
        }

        .register-header p {
            color: #64748b;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }

        .form-control {
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: #0d8bff;
            box-shadow: 0 0 0 3px rgba(13, 139, 255, 0.1);
        }

        .btn-register {
            background: linear-gradient(135deg, #0d8bff 0%, #2f68ff 100%);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(13, 139, 255, 0.25);
            color: white;
        }

        .login-link {
            text-align: center;
            margin-top: 24px;
            color: #64748b;
        }

        .login-link a {
            color: #0d8bff;
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')
    <div class="register-page">
        <div class="register-card">
            <div class="register-header">
                <h1>Tạo tài khoản</h1>
                <p>Tham gia cùng My Bus ngay hôm nay</p>
            </div>

            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label" for="name">Họ và tên</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Nhập họ và tên của bạn" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Ví dụ: example@gmail.com" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="phone">Số điện thoại</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Nhập số điện thoại">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Mật khẩu</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Mật khẩu ít nhất 6 ký tự" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Xác nhận mật khẩu</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu" required>
                </div>

                <button type="submit" class="btn btn-register">Đăng ký ngay</button>
            </form>

            <div class="login-link">
                Đã có tài khoản? <a href="{{ route('customer.login') }}">Đăng nhập</a>
            </div>
        </div>
    </div>
@endsection
