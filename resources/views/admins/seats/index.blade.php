@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2 class="page-title mb-0">Danh Sách Ghế</h2>
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

    <div style="margin-bottom: 30px;">
        <h4 style="margin: 0; color: #333; font-weight: 600;">Lựa Chọn Xe Để Xem Ghế</h4>
    </div>

    <div class="row">
        @forelse($buses as $bus)
            <div class="col-md-4 mb-4">
                <a href="{{ route('admin.seats.by_bus', $bus->id) }}" class="btn-primary" style="display: flex; width: 100%; justify-content: center; align-items: center; padding: 20px; font-size: 16px; border-radius: 8px; text-decoration: none;">
                    <i class="fas fa-bus" style="margin-right: 10px; font-size: 20px;"></i>
                    Xe: {{ $bus->bus_name }} - {{ $bus->license_plate }}
                </a>
            </div>
        @empty
            <div class="col-12" style="text-align: center; padding: 50px;">
                <p style="color: #999; font-size: 18px;">Chưa có dữ liệu xe.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
