@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content-admin')
    <div class="container mt-4">
        <h1 class="mb-4">Detail Pesanan #{{ $order->id }}</h1>

        <!-- Informasi Pesanan -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Informasi Pelanggan:</h5>
                <p><strong>Nama:</strong> {{ $order->name }}</p>
                <p><strong>Email:</strong> {{ $order->email }}</p>
                <p><strong>Alamat:</strong> {{ $order->address }}</p>
            </div>
            <div class="col-md-6">
                <h5>Status Pesanan:</h5>
                <p><strong>Status:</strong> 
                    <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'completed' ? 'success' : 'danger') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
                <p><strong>Total Pembayaran:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            </div>
        </div>

        <hr>

        <!-- Detail Item Pesanan -->
        <h4 class="mb-3">Item Pesanan:</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <hr>

        <!-- Kembali ke Daftar Pesanan -->
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mt-3">Kembali ke Daftar Pesanan</a>
    </div>
@endsection
