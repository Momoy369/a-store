@extends('admin.layout')

@section('title', 'Tambah Produk')

@section('content-admin')
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4>Tambah Produk Baru</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama produk" required>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Harga</label>
                    <input type="number" name="price" class="form-control" placeholder="Masukkan harga produk" required>
                </div>

                <div class="mb-3">
                    <label for="stock" class="form-label">Stok</label>
                    <input type="number" name="stock" class="form-control" placeholder="Masukkan stok produk" required>
                </div>

                <div class="mb-3">
                    <h5>Varian Produk</h5>
                    <div id="variant-container">
                        <div class="row mb-2 variant-row">
                            <div class="col-md-2">
                                <input type="text" name="variants[0][name]" class="form-control"
                                    placeholder="Nama Varian (cth: Warna)" required>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="variants[0][value]" class="form-control"
                                    placeholder="Nilai Varian (cth: Merah)" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="variants[0][price]" step="0.01" class="form-control"
                                    placeholder="Harga Varian (opsional)">
                            </div>
                            <div class="col-md-2">
                                <select name="variants[0][discount_type]" class="form-control">
                                    <option value="">— Diskon —</option>
                                    <option value="fixed">Potongan Tetap</option>
                                    <option value="percent">Persentase</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" step="0.01" name="variants[0][discount_value]" class="form-control"
                                    placeholder="Nilai Diskon">
                            </div>
                            <div class="col-md-1">
                                <input type="number" name="variants[0][stock]" class="form-control" placeholder="Stok"
                                    required>
                            </div>
                            <div class="col-md-1 d-flex align-items-center">
                                <button type="button" class="btn btn-danger btn-remove-variant">×</button>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="add-variant-btn" class="btn btn-sm btn-secondary mb-3">
                        + Tambah Varian
                    </button>
                </div>



                <div class="mb-3">
                    <label for="image" class="form-label">Gambar Produk</label>
                    <input type="file" name="image" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Masukkan deskripsi produk"></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Simpan</button>
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
    </style>
@endpush

@push('scripts')
    <script>
        let variantIndex = 1;

        document.getElementById('add-variant-btn').addEventListener('click', function() {
            const container = document.getElementById('variant-container');
            const row = document.createElement('div');
            row.className = 'row mb-2 variant-row';
            row.innerHTML = `
            <div class="col-md-2">
                <input type="text" name="variants[${variantIndex}][name]" class="form-control"
                    placeholder="Nama Varian (cth: Warna)" required>
            </div>
            <div class="col-md-2">
                <input type="text" name="variants[${variantIndex}][value]" class="form-control"
                    placeholder="Nilai Varian (cth: Merah)" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="variants[${variantIndex}][price]" step="0.01" class="form-control"
                    placeholder="Harga Varian (opsional)">
            </div>
            <div class="col-md-2">
                <select name="variants[${variantIndex}][discount_type]" class="form-control">
                    <option value="">— Diskon —</option>
                    <option value="fixed">Potongan Tetap</option>
                    <option value="percent">Persentase</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="variants[${variantIndex}][discount_value]" class="form-control"
                    placeholder="Nilai Diskon">
            </div>
            <div class="col-md-1">
                <input type="number" name="variants[${variantIndex}][stock]" class="form-control" placeholder="Stok" required>
            </div>
            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-remove-variant">×</button>
            </div>
        `;
            container.appendChild(row);
            variantIndex++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remove-variant')) {
                e.target.closest('.variant-row').remove();
            }
        });
    </script>
@endpush
