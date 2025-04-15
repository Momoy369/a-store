@extends('admin.layout')

@section('content-admin')
    <div class="container">
        <h1>Edit Diskon Produk</h1>

        <form action="{{ route('admin.discounts.update', $discount->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="product_id">Pilih Produk</label>
                <select name="product_id" class="form-control" required>
                    <option value="">Pilih Produk...</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ $product->id == $discount->product_id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="discount_percentage" class="form-label">Diskon (%)</label>
                <input type="number" name="discount_percentage" id="discount_percentage" class="form-control"
                    value="{{ old('discount_percentage', $discount->discount_percentage) }}" min="0" max="100"
                    required>
            </div>

            <div class="mb-3">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" class="form-control"
                    value="{{ old('start_date', \Carbon\Carbon::parse($discount->start_date)->format('Y-m-d')) }}" required>
            </div>

            <div class="mb-3">
                <label for="end_date" class="form-label">Tanggal Berakhir</label>
                <input type="date" name="end_date" id="end_date" class="form-control"
                    value="{{ old('end_date', \Carbon\Carbon::parse($discount->end_date)->format('Y-m-d')) }}" required>
            </div>


            <button type="submit" class="btn btn-success">Update Diskon</button>
        </form>
    </div>
@endsection
