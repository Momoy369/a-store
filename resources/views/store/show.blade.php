@extends('layouts.store')

@section('title', $product->name)

@section('content')
    <div class="row">
        <div class="col-md-6">
            <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid">
        </div>
        <div class="col-md-6">
            <h2>{{ $product->name }}</h2>
            <p>Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            <p>{{ $product->description }}</p>

            <form action="{{ route('cart.add', $product->id) }}" method="GET">
                <button class="btn btn-success">Tambah ke Keranjang</button>
            </form>

        </div>
    </div>
@endsection
