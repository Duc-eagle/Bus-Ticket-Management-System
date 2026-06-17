@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0">Danh Sách Tỉnh/Thành Phố</h2>
    <a href="{{ route('provinces.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm tỉnh thành
    </a>
</div>

<div class="page-content">
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="admin-card table-responsive">
        <table class="table-premium">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Hình ảnh</th>
                    <th>Tên Tỉnh/TP</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($provinces as $province)
                    <tr>
                        <td>{{ ($provinces->currentPage() - 1) * $provinces->perPage() + $loop->iteration }}</td>
                        <td>
                            @if($province->image_path)
                                <img src="{{ asset('storage/' . $province->image_path) }}" alt="Img" width="60" class="rounded shadow-sm" style="object-fit: cover; height: 40px;">
                            @else
                                <span class="text-muted small">Chưa có ảnh</span>
                            @endif
                        </td>
                        <td>{{ $province->province_name }}</td>
                        <td class="text-center">
                            <a href="{{ route('provinces.edit', $province->id) }}" class="btn btn-outline-primary btn-sm me-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('provinces.destroy', $province->id) }}" method="POST" style="display: inline;"
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa không?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-5">
                            <p class="mb-0 fs-5">Chưa có dữ liệu</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4 d-flex justify-content-center">
            {{ $provinces->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
