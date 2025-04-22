@extends('admin.layout')

@section('title', 'Stok Rendah')

@section('content_header')
    <h1>Produk dengan Stok Rendah</h1>
@stop

@section('content-admin')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Kombinasi Produk dengan Stok di Bawah 5</h3>
        </div>
        <div class="card-body">
            @if ($lowStockCombinations->isEmpty())
                <p>Semua kombinasi produk memiliki stok yang cukup.</p>
            @else
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Kombinasi Varian</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lowStockCombinations as $combination)
                            <tr>
                                <td>{{ $combination->product->name }}</td>
                                <td>
                                    @foreach ($combination->variantValues as $value)
                                        {{ $value->variantOption->name }}: {{ $value->value }}
                                        @if (!$loop->last)
                                            |
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $combination->stock }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@stop
