@extends('admin.layout')

@section('title', 'Edit Produk')

@section('content-admin')
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4>Edit Produk</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input type="text" name="name" class="form-control" value="{{ $product->name }}" required
                        placeholder="Masukkan nama produk">
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Kategori</label>
                    <select name="category_id" id="category_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected($product->category_id == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Harga</label>
                    <input type="number" name="price" class="form-control" value="{{ $product->price }}" required
                        placeholder="Masukkan harga produk">
                </div>

                <div class="mb-3">
                    <label for="stock" class="form-label">Stok</label>
                    <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required
                        placeholder="Masukkan jumlah stok">
                </div>

                <div class="mb-3">
                    <label for="variants" class="form-label">Varian Produk</label>

                    <div id="variant-container">
                        @php
                            $variants = old('variants') ?? ($product->variants ?? []);
                        @endphp

                        @foreach ($variants as $i => $variant)
                            <div class="row g-2 mb-2 variant-row">
                                <div class="col-md-2">
                                    <input type="text" name="variants[{{ $i }}][name]" class="form-control"
                                        value="{{ $variant['name'] ?? ($variant->name ?? '') }}" placeholder="Nama Varian"
                                        required>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="variants[{{ $i }}][value]" class="form-control"
                                        value="{{ $variant['value'] ?? ($variant->value ?? '') }}"
                                        placeholder="Nilai Varian" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="variants[{{ $i }}][price]" class="form-control"
                                        value="{{ $variant['price'] ?? ($variant->price ?? '') }}"
                                        placeholder="Harga Tambahan">
                                </div>
                                <div class="col-md-2">
                                    <select name="variants[{{ $i }}][discount_type]" class="form-control">
                                        <option value="">— Diskon —</option>
                                        <option value="fixed"
                                            {{ (old("variants.$i.discount_type") ?? ($variant['discount_type'] ?? ($variant->discount_type ?? ''))) === 'fixed' ? 'selected' : '' }}>
                                            Potongan Tetap
                                        </option>
                                        <option value="percent"
                                            {{ (old("variants.$i.discount_type") ?? ($variant['discount_type'] ?? ($variant->discount_type ?? ''))) === 'percent' ? 'selected' : '' }}>
                                            Persentase
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" step="0.01"
                                        name="variants[{{ $i }}][discount_value]" class="form-control"
                                        value="{{ $variant['discount_value'] ?? ($variant->discount_value ?? '') }}"
                                        placeholder="Nilai Diskon">
                                </div>
                                <div class="col-md-1">
                                    <input type="number" name="variants[{{ $i }}][stock]" class="form-control"
                                        value="{{ $variant['stock'] ?? ($variant->stock ?? '') }}" placeholder="Stok"
                                        required>
                                </div>
                                <div class="col-md-1 d-flex align-items-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-variant">&times;</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-primary mt-2" id="add-variant-btn">+ Tambah Varian</button>
                </div>



                <div class="mb-3">
                    <label for="image" class="form-label">Gambar Baru (opsional)</label>
                    <input type="file" name="image" class="form-control">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid mt-2"
                            style="max-height: 150px;">
                    @endif
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Masukkan deskripsi produk">{{ $product->description }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Update Produk</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-danger">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            margin-top: 20px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .card-header {
            border-radius: 8px 8px 0 0;
            padding: 15px;
        }

        .card-body {
            padding: 20px;
        }

        .mb-3 label {
            font-weight: bold;
        }

        .d-flex.justify-content-between button {
            font-size: 16px;
        }

        .btn {
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn:active {
            transform: translateY(2px);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .img-fluid {
            border-radius: 8px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let variantIndex = document.querySelectorAll('.variant-row').length;

            document.getElementById('add-variant-btn').addEventListener('click', function() {
                const container = document.getElementById('variant-container');
                const row = document.createElement('div');
                row.className = 'row g-2 mb-2 variant-row';
                row.innerHTML = `
                    <div class="col-md-2">
                        <input type="text" name="variants[${variantIndex}][name]" class="form-control" placeholder="Nama Varian" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="variants[${variantIndex}][value]" class="form-control" placeholder="Nilai Varian" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="variants[${variantIndex}][price]" class="form-control" placeholder="Harga Tambahan">
                    </div>
                    <div class="col-md-2">
                        <select name="variants[${variantIndex}][discount_type]" class="form-control">
                            <option value="">— Diskon —</option>
                            <option value="fixed">Potongan Tetap</option>
                            <option value="percent">Persentase</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" name="variants[${variantIndex}][discount_value]" class="form-control" placeholder="Nilai Diskon">
                    </div>
                    <div class="col-md-1">
                        <input type="number" name="variants[${variantIndex}][stock]" class="form-control" placeholder="Stok" required>
                    </div>
                    <div class="col-md-1 d-flex align-items-center">
                        <button type="button" class="btn btn-danger btn-sm remove-variant">&times;</button>
                    </div>
                `;
                container.appendChild(row);
                variantIndex++;
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-variant')) {
                    e.target.closest('.variant-row').remove();
                }
            });
        });
    </script>
@endpush
