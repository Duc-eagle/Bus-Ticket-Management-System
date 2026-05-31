@extends('layouts.layout')

@section('title', 'Doi mat khau - My Bus')

@push('styles')
    <style>
        .account-page {
            background:
                radial-gradient(circle at top left, rgba(0, 153, 255, 0.08), transparent 24%),
                linear-gradient(180deg, #f8fbff 0%, #eef4fb 100%);
            min-height: calc(100vh - 140px);
            padding: 42px 20px 72px;
        }

        .account-container {
            max-width: 1220px;
            margin: 0 auto;
        }

        .account-card {
            background: #fff;
            border: 1px solid #e4ebf5;
            border-radius: 28px;
            box-shadow: 0 24px 54px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .account-layout {
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
        }

        .account-sidebar {
            border-right: 1px solid #edf2f7;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            padding: 28px 0;
        }

        .account-user {
            margin: 0 18px 24px;
            padding: 14px 18px;
            border-radius: 16px;
            background: linear-gradient(135deg, #0d8bff 0%, #2f68ff 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .account-user-main {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            min-width: 0;
        }

        .account-user-main > div:last-child {
            flex: 1;
            min-width: 0;
        }

        .account-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .account-user-name {
            font-size: 17px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .account-user-role {
            font-size: 12px;
            opacity: 0.9;
            margin-top: 2px;
        }

        .account-user > .fa-chevron-down {
            flex-shrink: 0;
        }

        .account-nav {
            display: grid;
            gap: 4px;
            padding: 0 18px;
        }

        .account-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 12px;
            color: #344054;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .account-nav-item:hover {
            background: #f5f8fd;
            color: #0d8bff;
        }

        .account-nav-item.active {
            background: #eef6ff;
            color: #0d8bff;
        }

        .account-nav-item.logout {
            color: #ef4444;
        }

        .account-nav-item button {
            all: unset;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
        }

        .account-content {
            padding: 34px 36px 40px;
        }

        .account-heading {
            text-align: center;
            color: #0d6adf;
            font-size: 44px;
            font-weight: 800;
            margin-bottom: 28px;
        }

        .account-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px 22px;
            max-width: 600px;
        }

        .account-field {
            display: flex;
            flex-direction: column;
        }

        .account-field label {
            color: #344054;
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .account-field input,
        .account-field select,
        .account-field textarea {
            width: 100%;
            min-height: 50px;
            border: 1px solid #d9e2ef;
            border-radius: 8px;
            background: #fff;
            padding: 12px 14px;
            color: #101828;
            font-size: 15px;
            outline: none;
            transition: all 0.2s;
        }

        .account-field input:focus,
        .account-field select:focus,
        .account-field textarea:focus {
            border-color: #0d8bff;
            box-shadow: 0 0 0 3px rgba(13, 139, 255, 0.1);
        }

        .account-field textarea {
            min-height: 112px;
            resize: vertical;
        }

        .account-field input[readonly],
        .account-field select:disabled,
        .account-field textarea[readonly] {
            background: #fbfcfe;
            color: #475467;
        }

        .account-field.full {
            grid-column: 1 / -1;
        }

        .account-inline-note {
            margin-top: 8px;
            color: #98a2b3;
            font-size: 13px;
        }

        .account-actions {
            margin-top: 28px;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .account-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 92px;
            height: 42px;
            border: 0;
            border-radius: 8px;
            background: linear-gradient(135deg, #0d8bff 0%, #2f68ff 100%);
            color: #fff;
            font-weight: 700;
            box-shadow: 0 16px 30px rgba(13, 139, 255, 0.22);
            cursor: pointer;
            transition: all 0.2s;
            padding-left: 10px;
            padding-right: 10px;
        }

        .account-btn:hover {
            box-shadow: 0 20px 40px rgba(13, 139, 255, 0.28);
        }

        .account-btn-secondary {
            background: #fff;
            color: #0d8bff;
            border: 2px solid #0d8bff;
            box-shadow: none;
            text-decoration: none;
        }

        .account-btn-secondary:hover {
            background: #eef6ff;
            box-shadow: none;
        }

        .form-message {
            margin-top: 16px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            display: none;
        }

        .form-message.success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            display: block;
        }

        .form-message.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
            display: block;
        }

        .error-text {
            color: #ef4444;
            font-size: 13px;
            margin-top: 6px;
        }

        @media (max-width: 992px) {
            .account-layout {
                grid-template-columns: 1fr;
            }

            .account-sidebar {
                border-right: 0;
                border-bottom: 1px solid #edf2f7;
            }

            .account-heading {
                font-size: 34px;
            }
        }

        @media (max-width: 768px) {
            .account-page {
                padding: 28px 14px 52px;
            }

            .account-content {
                padding: 24px 18px 28px;
            }

            .account-heading {
                font-size: 28px;
                margin-bottom: 22px;
            }

            .account-form-grid {
                grid-template-columns: 1fr;
            }

            .account-actions {
                flex-direction: column;
            }

            .account-btn,
            .account-btn-secondary {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <section class="account-page">
        <div class="account-container">
            <div class="account-card">
                <div class="account-layout">
                    <aside class="account-sidebar">
                        <div class="account-user">
                            <div class="account-user-main">
                                <div class="account-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div>
                                    <div class="account-user-name">Xin chào {{ $user->full_name }}</div>
                                    <div class="account-user-role">Khách hàng My Bus</div>
                                </div>
                            </div>
                            <i class="fas fa-chevron-down"></i>
                        </div>

                        <div class="account-nav">
                            <a href="{{ route('customer.account') }}" class="account-nav-item">
                                <i class="fas fa-user-cog"></i>
                                Thông tin tài khoản
                            </a>
                            <a href="{{ route('customer.change-password') }}" class="account-nav-item active">
                                <i class="fas fa-key"></i>
                                Đổi mật khẩu
                            </a>
                            <a href="{{ route('customer.history') }}" class="account-nav-item">
                                <i class="fas fa-ticket-alt"></i>
                                Vé của tôi
                            </a>
                            <a href="javascript:void(0)" class="account-nav-item">
                                <i class="fas fa-bullhorn"></i>
                                Thông báo
                            </a>
                            <form action="{{ route('customer.logout') }}" method="POST">
                                @csrf
                                <div class="account-nav-item logout">
                                    <button type="submit">
                                        <i class="fas fa-power-off"></i>
                                        Đăng xuất
                                    </button>
                                </div>
                            </form>
                        </div>
                    </aside>

                    <div class="account-content">
                        <h1 class="account-heading">Đổi mật khẩu</h1>

                        @if ($errors->any())
                            <div class="form-message error">
                                <strong>Có lỗi xảy ra:</strong>
                                <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="form-message success">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('customer.change-password.store') }}" method="POST">
                            @csrf

                            <div class="account-form-grid">
                                <div class="account-field full">
                                    <label for="current_password">Mật khẩu hiện tại</label>
                                    <input 
                                        type="password" 
                                        id="current_password" 
                                        name="current_password" 
                                        placeholder="Nhập mật khẩu hiện tại"
                                        required
                                    >
                                    @error('current_password')
                                        <div class="error-text">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="account-field full">
                                    <label for="password">Mật khẩu mới</label>
                                    <input 
                                        type="password" 
                                        id="password" 
                                        name="password" 
                                        placeholder="Nhập mật khẩu mới (tối thiểu 8 ký tự)"
                                        required
                                    >
                                    @error('password')
                                        <div class="error-text">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="account-inline-note">
                                        Mật khẩu phải có ít nhất 8 ký tự
                                    </div>
                                </div>

                                <div class="account-field full">
                                    <label for="password_confirmation">Xác nhận mật khẩu mới</label>
                                    <input 
                                        type="password" 
                                        id="password_confirmation" 
                                        name="password_confirmation" 
                                        placeholder="Nhập lại mật khẩu mới"
                                        required
                                    >
                                    @error('password_confirmation')
                                        <div class="error-text">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="account-actions">
                                <button type="submit" class="account-btn">
                                    <i class="fas fa-save"></i>&nbsp; Cập nhật mật khẩu
                                </button>
                                <a href="{{ route('customer.account') }}" class="account-btn account-btn-secondary">
                                    <i class="fas fa-arrow-left"></i>&nbsp; Quay lại
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
