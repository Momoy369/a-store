@extends('layouts.store')

@section('title', 'Riwayat Pesanan')

@section('content')
    <h3 class="mb-4">Riwayat Pesanan Kamu</h3>

    @if ($orders->isEmpty())
        <div class="alert alert-info">Belum ada pesanan.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No Invoice</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td>Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>
                            @if ($order->status == 'paid')
                                <span class="badge bg-success">Lunas</span>
                            @elseif ($order->status == 'pending')
                                <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
