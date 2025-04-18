@extends('admin.layout')

@section('content-admin')
    <div class="container py-4">
        <h4 class="mb-4">Tambah Kombinasi Baru untuk <strong>{{ $product->name }}</strong></h4>

        <form method="POST" action="{{ route('admin.products.combinations.store', $product) }}">
            @csrf

            <div class="row g-3">
                @foreach ($variantOptions as $option)
                    <div class="col-md-6">
                        <label for="option_{{ $option->id }}">{{ $option->name }}</label>
                        <select name="variant_value_ids[]" class="form-select" required>
                            <option value="">-- Pilih {{ $option->name }} --</option>
                            @foreach ($option->values as $value)
                                <option value="{{ $value->id }}">{{ $value->value }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>

            @if ($errors->has('variant_value_ids'))
                <div class="alert alert-danger mt-3">
                    {{ $errors->first('variant_value_ids') }}
                </div>
            @endif

            <div class="row g-3 mt-3">
                <div class="col-md-4">
                    <label>Harga</label>
                    <input type="number" name="price" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label>Stok</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label>Tipe Diskon</label>
                    <select name="discount_type" class="form-select">
                        <option value="none">Tidak Ada</option>
                        <option value="percent">Persentase</option>
                        <option value="fixed">Potongan Tetap</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Nilai Diskon</label>
                    <input type="number" name="discount_value" class="form-control">
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('admin.products.combinations.edit', $product) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus"></i> Simpan Kombinasi
                </button>
            </div>
        </form>
    </div>
@endsection
