@extends('admin.layout')

@section('content-admin')
    <div class="container py-4">
        <h4 class="mb-4">Edit Kombinasi Varian untuk: <strong>{{ $product->name }}</strong></h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                @forelse ($product->variantCombinations as $combination)
                    <form method="POST" action="{{ route('admin.products.combinations.update', [$product, $combination]) }}"
                        class="border rounded p-3 mb-4">
                        @csrf
                        @method('PUT')

                        <div class="mb-2">
                            <strong>Kombinasi:</strong><br>
                            @foreach ($combination->variantValues as $value)
                                <span class="badge bg-secondary">{{ $value->option->name }}: {{ $value->value }}</span>
                            @endforeach
                        </div>

                        <div class="row g-3">
                            <div class="col-md-3">
                                <label>Harga</label>
                                <input type="number" name="price" value="{{ old('price', $combination->price) }}"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label>Stok</label>
                                <input type="number" name="stock" value="{{ old('stock', $combination->stock) }}"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label>Tipe Diskon</label>
                                <select name="discount_type" class="form-select">
                                    <option value="none" {{ $combination->discount_type === 'none' ? 'selected' : '' }}>
                                        Tidak Ada</option>
                                    <option value="percent"
                                        {{ $combination->discount_type === 'percent' ? 'selected' : '' }}>Persentase
                                    </option>
                                    <option value="fixed" {{ $combination->discount_type === 'fixed' ? 'selected' : '' }}>
                                        Potongan Tetap</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Nilai Diskon</label>
                                <input type="number" name="discount_value"
                                    value="{{ old('discount_value', $combination->discount_value) }}" class="form-control">
                            </div>
                        </div>

                        <div class="mt-3 d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <form method="POST"
                                action="{{ route('admin.products.combinations.destroy', [$product, $combination]) }}"
                                onsubmit="return confirm('Yakin ingin menghapus kombinasi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </form>
                @empty
                    <p class="text-muted">Belum ada kombinasi varian.</p>
                @endforelse

                <hr class="my-4">

                <div class="text-end">
                    <a href="{{ route('admin.products.combinations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('admin.products.combinations.create', $product) }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Tambah Kombinasi Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
