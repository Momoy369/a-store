@extends('admin.layout')

@section('content-admin')
    <div class="container">
        <h1>Tambah Diskon Produk</h1>

        <form action="{{ route('admin.discounts.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="product_id">Pilih Produk</label>
                <select name="product_id" class="form-control" required>
                    <option value="">Pilih Produk...</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="discount_percentage">Persentase Diskon</label>
                <input type="number" name="discount_percentage" class="form-control" min="0" max="100"
                    required>
            </div>

            <div class="form-group">
                <label for="start_date">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="end_date">Tanggal Berakhir</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Diskon</button>
        </form>
    </div>
@endsection
