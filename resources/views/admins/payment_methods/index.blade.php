@extends('layouts.app')

@section('content')
    <div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="page-title mb-0">Phương Thức Thanh Toán</h2>
            <a href="{{ route('paymentMethods.create') }}" class="btn btn-primary">Thêm Phương thức</a>
        </div>

        <div class="admin-card table-responsive">
            <table class="table-premium">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên Phương thức</th>
                    <th>Mô tả phương thức</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                @foreach($paymentMethods as $method)
                    <tr>
                        <td>{{ ($paymentMethods->currentPage() - 1) * $paymentMethods->perPage() + $loop->iteration }}</td>
                        <td>{{ $method->method_name }}</td>
                        <td>{{ $method->method_description }}</td>
                        <td>
                            <a href="{{ route('paymentMethods.edit', $method) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('paymentMethods.destroy', $method) }}" method="POST" style="display:inline;">
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
