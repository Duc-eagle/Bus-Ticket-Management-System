@extends('layouts.app')

@section('content')
<div class="page-header">
    <h2>Danh Sách Người Dùng</h2>
{{--    <nav aria-label="breadcrumb">--}}
{{--        <ol class="breadcrumb">--}}
{{--            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang Chủ</a></li>--}}
{{--            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Người Dùng</a></li>--}}
{{--            <li class="breadcrumb-item active" aria-current="page">Danh Sách Người Dùng</li>--}}
{{--        </ol>--}}
{{--    </nav>--}}
</div>

<div class="page-content">
    @if ($errors->any())
        <div class="alert alert-danger">
            <h4>Có lỗi xảy ra!</h4>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: flex; gap: 20px; margin-top: 20px;">
        <a href="{{ route('admin.users.customers') }}" class="btn-primary" style="flex: 1; display: flex; justify-content: center; align-items: center; padding: 20px; font-size: 18px; border-radius: 8px; text-decoration: none;">
            <i class="fas fa-users" style="margin-right: 10px; font-size: 24px;"></i>
            Danh sách khách hàng
        </a>
        <a href="{{ route('admin.users.staff') }}" class="btn-primary" style="flex: 1; display: flex; justify-content: center; align-items: center; padding: 20px; font-size: 18px; border-radius: 8px; background-color: #10b981; text-decoration: none;">
            <i class="fas fa-user-shield" style="margin-right: 10px; font-size: 24px;"></i>
            Danh sách nhân viên
        </a>
    </div>
</div>
@endsection
