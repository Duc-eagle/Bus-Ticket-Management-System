@extends('layouts.app')

@section('content')
    <div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="page-title mb-0">Danh Sách Bến Xe</h2>
            <a href="{{ route('bus_stations.create') }}" class="btn btn-primary">Thêm Bến xe</a>
        </div>

        <div class="admin-card table-responsive">
            <table class="table-premium">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Tỉnh/Thành phố</th>
                    <th>Tên Bến xe</th>
                    <th>Địa chỉ</th>
                    <th>Số điện thoại</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                @foreach($busStations as $station)
                    <tr>
                        <td>{{ ($busStations->currentPage() - 1) * $busStations->perPage() + $loop->iteration }}</td>
                        <td>{{ $station->province->province_name }}</td>
                        <td>{{ $station->station_name }}</td>
                        <td>{{ $station->address }}</td>
                        <td>{{ $station->phone }}</td>
                        <td>
                            <a href="{{ route('bus_stations.edit', $station) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('bus_stations.destroy', $station) }}" method="POST" style="display:inline;">
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
