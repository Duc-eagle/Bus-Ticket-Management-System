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
        }

        .login-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 900px;
            width: 90%;
        }

        .login-image {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .login-image i {
            font-size: 120px;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .login-image h2 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .login-image p {
            font-size: 14px;
            opacity: 0.9;
            text-align: center;
        }

        .login-form {
            padding: 50px;
        }

        .login-form h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .login-form .subtitle {
            color: #999;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
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
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-login {
            background: #667eea;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            color: white;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 15px;
        }

        .btn-login:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
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

        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
            }

            .login-image {
                display: none;
            }

            .login-form {
                padding: 40px;
            }

            .login-form h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-image">
            <i class="fas fa-bus"></i>
            <h2>My Bus</h2>
            <p>Hệ thống quản lý bán vé xe khách</p>
        </div>

        <div class="login-form">
            <h1>Đăng nhập hệ thống quản trị</h1>
            <p class="subtitle">Nhập thông tin đăng nhập của bạn để tiếp tục</p>

            <form method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Email:</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" placeholder="Nhập địa chỉ email" required>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Mật khẩu:</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" placeholder="Nhập mật khẩu" required>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-login">Đăng nhập</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
