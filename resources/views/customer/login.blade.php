<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - My Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            padding: 50px;
            max-width: 500px;
            width: 100%;
            animation: slideIn 0.5s ease-out;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header h1 {
            font-size: 36px;
            color: #333;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #999;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 15px;
        }

        .form-label .required {
            color: #ef4444;
            margin-left: 3px;
        }

        .form-control {
            padding: 12px 15px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 15px;
            transition: all 0.3s;
            background: #f9fafb;
            width: 100%;
        }

        .form-control:focus {
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: #d1d5db;
        }

        .form-check {
            margin-bottom: 25px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 2px;
            accent-color: #667eea;
        }

        .form-check-label {
            margin-left: 8px;
            cursor: pointer;
            color: #0099ff;
            font-weight: 500;
            font-size: 15px;
            user-select: none;
        }

        .form-check-label:hover {
            text-decoration: underline;
        }

        .btn-login {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            border: none;
            color: white;
            padding: 13px 30px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #45a049 0%, #3d8b40 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(76, 175, 80, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            border: none;
            border-radius: 6px;
            margin-bottom: 25px;
            padding: 12px 15px;
            font-size: 14px;
            animation: slideIn 0.3s ease-out;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }

        .invalid-feedback {
            color: #ef4444;
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }

        .is-invalid {
            border-color: #ef4444 !important;
        }

        .login-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .login-footer p {
            color: #666;
            font-size: 14px;
            margin-bottom: 0;
        }

        .login-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .login-footer a:hover {
            text-decoration: underline;
            color: #764ba2;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 30px 20px;
            }

            .login-header h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Header -->
        <div class="login-header">
            <h1>Đăng Nhập</h1>
        </div>

        <!-- Alert Messages -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Đăng nhập thất bại!</strong>
                @if ($errors->has('email'))
                    <br>{{ $errors->first('email') }}
                @endif
                @if ($errors->has('password'))
                    <br>{{ $errors->first('password') }}
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('customer.login.process') }}" novalidate>
            @csrf

            <!-- Email Field -->
            <div class="form-group">
                <label class="form-label">
                    Email
                    <span class="required">*</span>
                </label>
                <input 
                    type="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    name="email" 
                    value="{{ old('email') }}"
                    placeholder="Nhập email của bạn"
                    required 
                    autofocus>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <label class="form-label">
                    Mật khẩu
                    <span class="required">*</span>
                </label>
                <input 
                    type="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    name="password" 
                    placeholder="Nhập mật khẩu"
                    required>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remember Me Checkbox -->
            <div class="form-check">
                <input 
                    class="form-check-input" 
                    type="checkbox" 
                    name="remember" 
                    id="remember" 
                    {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Duy trì đăng nhập
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-login">Đăng Nhập</button>

            <!-- Footer Links -->
            <div class="login-footer">
                <p>
                    Chưa có tài khoản? 
                    <a href="{{ route('register') }}">Đăng ký ngay</a>
                </p>
                <p style="margin-top: 10px; font-size: 13px;">
                    <a href="{{ route('home') }}">← Quay lại trang chủ</a>
                </p>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
