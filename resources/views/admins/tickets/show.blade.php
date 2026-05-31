@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0">Chi Tiết Mã Vé: {{ $ticket_name }}</h2>
        <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary btn-sm">Quay lại</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="admin-card table-responsive">
        <table class="table-premium">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Khách Hàng</th>
                    <th>Ghế</th>
                    <th>Giá</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->user->full_name ?? 'N/A' }}</td>
                        <td>{{ $ticket->seat->seat_code ?? 'N/A' }}</td>
                        <td>{{ number_format($ticket->total, 0, ',', '.') }}₫</td>
                        <td>
                            @if($ticket->status === 'paid' || $ticket->status === 'confirmed')
                                <span class="badge bg-success-subtle text-success">Đã thanh toán</span>
                            @elseif($ticket->status === 'pending_payment')
                                <span class="badge bg-warning-subtle text-warning">Chờ thanh toán</span>
                            @elseif($ticket->status === 'completed')
                                <span class="badge bg-secondary-subtle text-secondary">Hoàn thành</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Đã hủy</span>
                            @endif
                        </td>
                        <td>
                            @if($ticket->status !== 'cancelled')
                                <form action="{{ route('admin.tickets.cancel_seat', $ticket->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn hủy ghế này không?')"><i class="fas fa-trash"></i> Hủy ghế</button>
                                </form>
                            @else
                                <span class="text-muted"><i class="fas fa-ban"></i> Không khả dụng</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
