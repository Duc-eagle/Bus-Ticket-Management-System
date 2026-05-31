@extends('layouts.layout')

@section('title', 'Thong tin tai khoan - My Bus')

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

        .account-gender {
            display: flex;
            gap: 20px;
            align-items: center;
            min-height: 50px;
        }

        .account-gender label {
            margin: 0;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            cursor: default;
        }

        .account-gender input {
            min-height: auto;
            width: auto;
            margin: 0;
        }

        .account-actions {
            margin-top: 28px;
            text-align: left;
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
                            <a href="{{ route('customer.account') }}" class="account-nav-item active">
                                <i class="fas fa-user-cog"></i>
                                Thông tin tài khoản
                            </a>
                            <a href="{{ route('customer.change-password') }}" class="account-nav-item">
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
                        <h1 class="account-heading">Thông tin tài khoản</h1>

                        <div class="account-form-grid">
                            <div class="account-field">
                                <label>Tên</label>
                                <input type="text" value="{{ $user->full_name }}" readonly>
                            </div>

                            <div class="account-field">
                                <label>Email</label>
                                <input type="email" value="{{ $user->email }}" readonly>
                            </div>

                            <div class="account-field">
                                <label>Số điện thoại</label>
                                <input type="text" value="{{ $user->phone ?? 'Chua cap nhat' }}" readonly>
                            </div>

                            <div class="account-field">
                                <label>Tên dăng nhập</label>
                                <input type="text" value="{{ $user->user_name }}" readonly>
                            </div>

                            <div class="account-field">
                                <label>Địa chỉ</label>
                                <input type="text" value="{{ $user->address ?? 'Chua cap nhat' }}" readonly>
                            </div>

{{--                            <div class="account-field">--}}
{{--                                <label>Ngân hàng</label>--}}
{{--                                <select disabled>--}}
{{--                                    <option selected>Chua cap nhat</option>--}}
{{--                                </select>--}}
{{--                                <div class="account-inline-note">He thong hien chua luu thong tin ngan hang cho tai khoan nay.</div>--}}
{{--                            </div>--}}

                            <div class="account-field">
                                <label>Ngày sinh</label>
                                <input type="text" value="{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d/m/Y') : 'Chua cap nhat' }}" readonly>
                            </div>

{{--                            <div class="account-field">--}}
{{--                                <label>Số tài khoản</label>--}}
{{--                                <input type="text" value="Chua cap nhat" readonly>--}}
{{--                                <div class="account-inline-note">Truong nay chi la giao dien mau, chua co cot du lieu trong bang users.</div>--}}
{{--                            </div>--}}

{{--                            <div class="account-field full">--}}
{{--                                <label>Giới tính</label>--}}
{{--                                <div class="account-gender">--}}
{{--                                    <label>--}}
{{--                                        <input type="radio" name="gender" disabled>--}}
{{--                                        Nam--}}
{{--                                    </label>--}}
{{--                                    <label>--}}
{{--                                        <input type="radio" name="gender" disabled>--}}
{{--                                        Nu--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                                <div class="account-inline-note">Gioi tinh hien chua duoc luu trong du lieu tai khoan, nen dang hien thi theo mau giao dien.</div>--}}
{{--                            </div>--}}
                        </div>

{{--                        <div class="account-actions">--}}
{{--                            <button type="button" class="account-btn">Gửi</button>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
