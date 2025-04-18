@extends('admin.layout')

@section('title', 'Stok Rendah')

@section('content_header')
    <h1>Produk dengan Stok Rendah</h1>
@stop

@section('content-admin')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Produk dengan Stok di Bawah 5</h3>
        </div>
        <div class="card-body">
            @if ($lowStockProducts->isEmpty())
                <p>Semua produk memiliki stok yang cukup.</p>
            @else
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Nama Varian</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lowStockProducts as $product)
                            @foreach ($product->variants as $variant)
                                @if ($variant->stock < 5)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $variant->name }}</td>
                                        <td>{{ $variant->stock }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@stop
