@extends('layouts.app')

@section('content')
    <div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="page-title mb-0">Danh Sách Vé</h2>
            <div class="d-flex gap-2">
                <form action="{{ route('tickets.index') }}" method="GET" class="d-flex" style="width: 250px;">
                    <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Tìm mã vé..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Tìm</button>
                </form>
                <a href="{{ route('tickets.create') }}" class="btn btn-primary btn-sm d-flex align-items-center">Thêm Vé</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="admin-card table-responsive">
            <table class="table-premium">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã Vé (Nhóm)</th>
                    <th>Khách hàng</th>
                    <th>Ghế</th>
                    <th>Chuyến đi</th>
                    <th>Phương thức TT</th>
                    <th>Ngày mua</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                @foreach($groupedTickets as $ticketName => $group)
                    @php $firstTicket = $group->first(); @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $ticketName }}</strong></td>
                        <td>{{ $firstTicket->user->full_name ?? 'N/A' }}</td>
                        <td>
                            {{ $group->activeSeats ?: 'N/A' }}
                            @if($group->cancelledSeats)
                                <br><small class="text-danger">(Đã hủy: {{ $group->cancelledSeats }})</small>
                            @endif
                        </td>
                        <td>{{ $firstTicket->trip->trip_name ?? 'N/A' }}</td>
                        <td>{{ $firstTicket->paymentMethod->method_name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($firstTicket->purchase_date)->format('d/m/Y H:i') }}</td>
                        <td class="text-danger fw-bold">{{ number_format($group->totalPrice, 0, ',', '.') }}₫</td>
                        <td>
                            @if($group->displayStatus === 'paid' || $group->displayStatus === 'confirmed')
                                <span class="badge bg-success-subtle text-success">Đã thanh toán</span>
                            @elseif($group->displayStatus === 'pending_payment')
                                <span class="badge bg-warning-subtle text-warning">Chờ thanh toán</span>
                            @elseif($group->displayStatus === 'completed')
                                <span class="badge bg-secondary-subtle text-secondary">Hoàn thành</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Đã hủy</span>
                            @endif
                            
                            @if($group->isPartialCancel)
                                <br><span class="badge bg-warning-subtle text-warning mt-1">Hủy 1 phần</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('tickets.updateStatus', $ticketName) }}" method="POST" class="d-flex align-items-center mb-2">
                                @csrf
                                <select name="status" class="form-select form-select-sm me-1" style="width: 140px;">
                                    <option value="pending_payment" {{ $group->displayStatus == 'pending_payment' ? 'selected' : '' }}>Chờ thanh toán</option>
                                    <option value="paid" {{ in_array($group->displayStatus, ['paid', 'confirmed', 'completed']) ? 'selected' : '' }}>Đã thanh toán</option>
                                    <option value="cancelled" {{ $group->displayStatus == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                                <button type="submit" class="btn btn-outline-success btn-sm"><i class="fas fa-check"></i></button>
                            </form>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.tickets.show', $ticketName) }}" class="btn btn-outline-info btn-sm" title="Xem chi tiết"><i class="fas fa-eye"></i></a>
                                <form action="{{ route('tickets.destroy', $ticketName) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa toàn bộ nhóm vé này không?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

