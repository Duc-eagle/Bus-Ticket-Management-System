@extends('layouts.app')

@section('content')
    <div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="page-title mb-0">Quản Lý Chuyến Xe</h2>
            <a href="{{ route('trips.create') }}" class="btn btn-primary">Thêm Chuyến đi</a>
        </div>

        <div class="admin-card table-responsive">
            <table class="table-premium">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên Chuyến</th>
                    <th>Xe (Biển số)</th>
                    <th>Tuyến đường</th>
                    <th>Ngày đi</th>
                    <th>Giờ đi</th>
                    <th>Giờ đến</th>
                    <th>Giá cơ bản</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                @foreach($trips as $trip)
                    <tr>
                        <td>{{ ($trips->currentPage() - 1) * $trips->perPage() + $loop->iteration }}</td>
                        <td>{{ $trip->trip_name }}</td>
                        <td>{{ $trip->bus->license_plate }}</td>
                        <td>{{ $trip->route->route_name }}</td>
                        <td>{{ $trip->trip_date }}</td>
                        <td>{{ $trip->departure_time }}</td>
                        <td>{{ $trip->arrival_time }}</td>
                        <td>{{ number_format($trip->base_price, 0, ',', '.') }}₫</td>
                        <td>
                            @if($trip->status == 'active')
                                <span class="badge bg-success-subtle text-success">Hoạt động</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">{{ $trip->status }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('trips.edit', $trip) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('trips.destroy', $trip) }}" method="POST" style="display:inline;">
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

