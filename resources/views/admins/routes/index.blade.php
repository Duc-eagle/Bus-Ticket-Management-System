@extends('layouts.app')


@section('content')
    <div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="page-title mb-0">Quản Lý Tuyến Xe</h2>
            <a href="{{ route('routes.create') }}" class="btn btn-primary">Thêm Tuyến đường</a>
        </div>

        <div class="admin-card table-responsive">
            <table class="table-premium">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Hình ảnh</th>
                    <th>Tên Tuyến</th>
                    <th>Điểm đi</th>
                    <th>Điểm đến</th>
                    <th>Khoảng cách (km)</th>
                    <th>Thời gian ước tính</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                @foreach($routes as $route)
                    <tr>
                        <td>{{ ($routes->currentPage() - 1) * $routes->perPage() + $loop->iteration }}</td>
                        <td>
                            @if($route->image_path)
                                <img src="{{ asset('storage/' . $route->image_path) }}" alt="Img" width="60" class="rounded shadow-sm" style="object-fit: cover; height: 40px;">
                            @else
                                <span class="text-muted small">Chưa có ảnh</span>
                            @endif
                        </td>
                        <td>{{ $route->route_name }}</td>
                        <td>{{ $route->departureStation->station_name }}</td>
                        <td>{{ $route->arrivalStation->station_name }}</td>
                        <td>{{ $route->distance }}</td>
                        <td>{{ $route->estimate_time }}</td>
                        <td>
                            <a href="{{ route('routes.edit', $route) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('routes.destroy', $route) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn chắc chứ?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
