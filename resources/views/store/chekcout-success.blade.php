@extends('layouts.store')

@section('title', 'Checkout Berhasil')

@section('content')
    <div class="card shadow p-4 mb-5 bg-white rounded text-center">
        <h3 class="mb-3 text-success">🎉 Terima kasih atas pesanan kamu!</h3>
        <p class="lead">Pesanan kamu telah berhasil diproses.</p>

        @if(session('invoice'))
            <p class="mt-3">
                <strong>Nomor Invoice:</strong> {{ session('invoice') }}<br>
                <small>Kamu dapat mengecek status pembayaran melalui riwayat pesanan.</small>
            </p>
        @endif

        <div class="mt-4">
            <a href="{{ route('store.index') }}" class="btn btn-primary">
                <i class="fas fa-home me-1"></i> Kembali ke Toko
            </a>
            <a href="{{ route('orders.history') }}" class="btn btn-outline-secondary">
                <i class="fas fa-receipt me-1"></i> Lihat Riwayat Pesanan
            </a>
        </div>
    </div>
@endsection
