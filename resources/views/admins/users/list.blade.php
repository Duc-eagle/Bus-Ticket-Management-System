@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0">{{ $title }}</h2>
    <div>
        <a href="{{ route('users.index') }}" class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm mới
        </a>
    </div>
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
                    <th>Họ Tên</th>
                    <th>Email</th>
                    <th>Điện thoại</th>
                    <th>Vai trò</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                        <td><strong>{{ $user->full_name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>
                            @if($user->role == 'admin')
                                <span class="badge bg-danger-subtle text-danger" style="padding: 6px 12px;">Admin</span>
                            @elseif($user->role == 'staff')
                                <span class="badge bg-info-subtle text-info" style="padding: 6px 12px;">Staff</span>
                            @elseif($user->role == 'customer')
                                <span class="badge bg-success-subtle text-success" style="padding: 6px 12px;">Khách hàng</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary" style="padding: 6px 12px;">{{ $user->role }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary me-2" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;" 
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này không?');">
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
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="fas fa-users-slash fa-3x mb-3 text-light"></i>
                            <p class="mb-0 fs-5">Chưa có dữ liệu.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
