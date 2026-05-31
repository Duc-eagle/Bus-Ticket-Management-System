@extends('layouts.app')

@section('content')
    <div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="page-title mb-0">Quản Lý Xe</h2>
            <a href="{{ route('buses.create') }}" class="btn btn-primary">Thêm Xe</a>
        </div>

        <div class="admin-card table-responsive">
            <table class="table-premium">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Hình ảnh</th>
                    <th>Biển số</th>
                    <th>Tên xe</th>
                    <th>Tổng ghế</th>
                    <th>Có giường nằm</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                @foreach($buses as $bus)
                    <tr>
                        <td>{{ ($buses->currentPage() - 1) * $buses->perPage() + $loop->iteration }}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-1" style="max-width: 150px;">
                                @foreach($bus->images as $image)
                                    <img src="{{ asset('storage/' . $image->image_path) }}" width="40" height="30" class="rounded border" style="object-fit: cover;">
                                @endforeach
                            </div>
                        </td>
                        <td>{{ $bus->license_plate }}</td>
                        <td>{{ $bus->bus_name }}</td>
                        <td>{{ $bus->total_seats }}</td>
                        <td>
                            @if($bus->has_beds)
                                <span class="badge bg-success-subtle text-success">Có</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Không</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('buses.edit', $bus) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('buses.destroy', $bus) }}" method="POST" style="display:inline;">
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
