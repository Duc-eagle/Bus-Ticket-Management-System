@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0">Danh sách ghế - {{ $bus->bus_name }} ({{ $bus->license_plate }})</h2>
    <a href="{{ route('seats.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="page-content">
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('seats.create', ['bus_id' => $bus->id]) }}" class="btn btn-primary" style="padding: 8px 15px;">
            <i class="fas fa-plus"></i> Thêm Ghế
        </a>
    </div>

    <div class="table-responsive bg-white rounded shadow-sm p-3">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Mã Ghế</th>
                    <th>Trạng thái</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($seats as $seat)
                    <tr>
                        <td>{{ $seat->id }}</td>
                        <td><span style="font-weight: 600; font-size: 16px;">{{ $seat->seat_code }}</span></td>
                        <td>
                            @if($seat->status == 'available')
                                <span class="badge bg-success" style="padding: 6px 12px;">Trống</span>
                            @else
                                <span class="badge bg-warning text-dark" style="padding: 6px 12px;">Đã đặt</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('seats.edit', $seat) }}" class="btn btn-sm btn-outline-primary me-2" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('seats.destroy', $seat) }}" method="POST" style="display: inline;" 
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa ghế này không?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-5">
                            <i class="fas fa-chair fa-3x mb-3 text-light"></i>
                            <p class="mb-0 fs-5">Xe này chưa có ghế nào.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $seats->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
