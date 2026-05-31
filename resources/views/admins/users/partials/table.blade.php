<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Họ Tên</th>
                <th>Email</th>
                <th>Địa Chỉ</th>
                <th>Giới Tính</th>
                <th>Tuổi</th>
                <th>Số lần đặt vé</th>
                <th style="text-align: center;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                @php
                    $age = $user->dob ? \Carbon\Carbon::parse($user->dob)->age : 0;
                @endphp
                <tr>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->address ?? '-' }}</td>
                    <td>{{ $user->gender ?? '-' }}</td>
                    <td>{{ $age }}</td>
                    <td>{{ $user->tickets_count ?? 0 }}</td>
                    <td style="text-align: center;">
                        <div class="actions" style="justify-content: center;">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn-edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;" 
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa không?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px;">
                        <p style="color: #999; margin: 0;">Chưa có dữ liệu</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <p style="color: #999; font-size: 13px; margin: 0;">Tổng cộng {{ $users->total() }} người</p>
        {{ $users->links('pagination::bootstrap-4') }}
    </div>
</div>
