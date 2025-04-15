@extends('admin.layout')

@section('title', 'Daftar Pesanan')

@section('content-admin')
    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>Daftar Pesanan</h1>
                <!-- Filter Form -->
                <form method="GET" class="d-flex align-items-center">
                    <select name="status" class="form-select form-select-sm me-2">
                        <option value="">-- Pilih Status --</option>
                        @foreach (App\Models\Order::STATUSES as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-sm btn-primary">Filter</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>
                                    <strong>{{ $order->name }}</strong><br>
                                    <small class="text-muted">{{ $order->email }}</small>
                                </td>
                                <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td>
                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST"
                                        class="d-flex align-items-center">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select form-select-sm me-2">
                                            @foreach (App\Models\Order::STATUSES as $status)
                                                <option value="{{ $status }}"
                                                    {{ $order->status == $status ? 'selected' : '' }}>
                                                    {{ ucfirst($status) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-sm btn-primary me-2">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <span
                                            class="badge bg-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span>
                                    </form>
                                </td>

                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>

                                    <a href="{{ route('admin.orders.exportPdf') }}" class="btn btn-success mb-3">Export ke
                                        PDF</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-shopping-bag fa-2x mb-2"></i><br>
                                    Belum ada pesanan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
