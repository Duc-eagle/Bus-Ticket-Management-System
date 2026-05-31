<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - My Bus Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .login-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            min-height: 550px;
        }

        /* Illustration Side */
        .login-illustration {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eef7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .login-illustration::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 50%;
            top: -50px;
            right: -50px;
        }

        .login-illustration::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(118, 75, 162, 0.1);
            border-radius: 50%;
            bottom: -40px;
            left: -40px;
        }

        .illustration-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: #333;
        }

        .illustration-icon {
            font-size: 120px;
            color: #667eea;
            margin-bottom: 20px;
            display: block;
        }

        .illustration-text h3 {
            font-size: 20px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .illustration-text p {
            font-size: 14px;
            color: #999;
            line-height: 1.6;
        }

        /* Form Side */
        .login-form {
            display: flex;
            flex-direction: column;
            padding: 50px 45px;
            justify-content: center;
        }

        .login-form h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .login-form .subtitle {
            font-size: 14px;
            color: #999;
            margin-bottom: 35px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
            font-size: 14px;
        }

        .form-control {
            padding: 12px 15px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f9fafb;
            width: 100%;
        }

        .form-control::placeholder {
            color: #d1d5db;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            background: white;
            outline: none;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 15px;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            border: none;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 12px 15px;
            font-size: 14px;
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

        .form-check {
            margin-bottom: 20px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .form-check-label {
            margin-left: 8px;
            cursor: pointer;
            color: #666;
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .login-illustration {
                display: none;
            }

            .login-form {
                padding: 40px 30px;
            }

            .login-form h2 {
                font-size: 24px;
            }
        }

        /* Animation */
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

        .login-form,
        .login-illustration {
            animation: slideIn 0.6s ease-out;
        }

        .login-form {
            animation-delay: 0.1s;
        }

        /* Focus State */
        .form-control {
            box-shadow: none;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .remember-forgot a {
            color: #667eea;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .remember-forgot a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Illustration Side -->
        <div class="login-illustration">
            <div class="illustration-content">
                <i class="fas fa-laptop illustration-icon"></i>
                <div class="illustration-text">
                    <h3>My Bus</h3>
                    <p>Hệ thống quản lý bán vé xe khách hiệu quả và chuyên nghiệp</p>
                </div>
            </div>
        </div>

        <!-- Form Side -->
        <div class="login-form">
            <h2>Đăng nhập hệ thống quản trị</h2>
            <p class="subtitle">Nhập thông tin đăng nhập của bạn để tiếp tục</p>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Đăng nhập thất bại!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('admins.loginProcess') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Email:</label>
                    <input type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="Nhập địa chỉ email"
                           required
                           autofocus>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Mật khẩu:</label>
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           name="password"
                           placeholder="Nhập mật khẩu"
                           required>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="remember-forgot">
                    <div class="form-check">
                        <input class="form-check-input"
                               type="checkbox"
                               name="remember"
                               id="remember"
                               {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Ghi nhớ đăng nhập
                        </label>
                    </div>
                    <a href="#">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="btn-login">Đăng nhập</button>
            </form>

            <p style="text-align: center; margin-top: 25px; color: #999; font-size: 13px;">
                © 2026 My Bus Management System. All rights reserved.
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
