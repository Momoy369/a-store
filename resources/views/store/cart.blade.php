@extends('layouts.store')

@section('title', 'Keranjang')

@section('content')
    <h2 class="mb-4">Keranjang Belanja</h2>

    @if (session('cart') && count(session('cart')) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach ($cart as $id => $item)
                    @php
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('cart.remove', $id) }}" class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td colspan="2"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <a href="{{ route('cart.clear') }}" class="btn btn-warning">Kosongkan Keranjang</a>
        <a href="#" class="btn btn-success">Lanjut ke Checkout</a>
    @else
        <p>Keranjang kamu kosong.</p>
        <a href="{{ route('store.index') }}" class="btn btn-primary">Kembali ke Toko</a>
    @endif
@endsection
