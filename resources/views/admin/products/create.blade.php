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

                <!-- Form Input untuk Produk -->
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
                    <h5>Opsi Varian Produk</h5>
                    <div id="variant-options-container">
                        <div class="row mb-2 variant-option-row">
                            <div class="col-md-4">
                                <input type="text" class="form-control variant-option-name" name="variant_options[]"
                                    placeholder="Nama Opsi (cth: Warna)" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control variant-option-id" name="variant_values[]"
                                    placeholder="Nilai (pisahkan dengan koma, cth: Merah,Biru)" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-remove-option">×</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary" id="add-variant-option-btn">+ Tambah
                        Opsi</button>
                </div>

                <div class="mb-3">
                    <h5>Kombinasi Varian</h5>
                    <div id="variant-combinations-container">
                        <small class="text-muted">Kombinasi akan muncul setelah opsi diisi.</small>
                    </div>
                    <!-- Input tersembunyi untuk variant_value_ids -->
                    <input type="hidden" name="variant_value_ids" id="variant_value_ids" value="">
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
        /* Styles as before */
    </style>
@endpush

@push('scripts')
    <script>
        function generateCombinations(optionsMap) {
            const keys = Object.keys(optionsMap);
            if (keys.length === 0) return [];

            function cartesian(arr) {
                return arr.reduce((a, b) => a.flatMap(d => b.map(e => [...d, e])), [
                    []
                ]);
            }

            const valuesList = keys.map(k => optionsMap[k].map(v => ({
                option: k,
                value: v
            })));

            return cartesian(valuesList);
        }

        function refreshCombinations() {
            const optionsMap = {};

            document.querySelectorAll('.variant-option-row').forEach((row) => {
                const name = row.querySelector('.variant-option-name').value.trim();
                const values = row.querySelector('.variant-option-id').value.trim().split(',').map(v => v.trim())
                    .filter(v => v);
                if (name && values.length) {
                    optionsMap[name] = values;
                }
            });

            const combinations = generateCombinations(optionsMap);
            const container = document.getElementById('variant-combinations-container');
            container.innerHTML = '';

            const variantValueIds = []; // Array untuk menyimpan ID kombinasi varian

            combinations.forEach((combo, index) => {
                const comboKey = combo.map(c => c.value).join(' - ');
                let variantInputs = '';
                combo.forEach(c => {
                    variantInputs +=
                        `<input type="hidden" name="combinations[${index}][variant_values][]" value="${c.value}">`;
                    variantValueIds.push(c.value); // Menyimpan ID dari tiap kombinasi varian
                });

                const html = `
                    <div class="card card-body mb-2">
                        <strong>${comboKey}</strong>
                        <div class="row mt-2">
                            ${variantInputs}
                            <div class="col-md-3">
                                <input type="number" name="combinations[${index}][stock]" class="form-control" placeholder="Stok" required>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" name="combinations[${index}][price]" class="form-control" placeholder="Harga (opsional)">
                            </div>
                            <div class="col-md-3">
                                <select name="combinations[${index}][discount_type]" class="form-control">
                                    <option value="">Tipe Diskon</option>
                                    <option value="percent">Persen</option>
                                    <option value="fixed">Nominal</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="combinations[${index}][discount_value]" class="form-control" placeholder="Nilai Diskon">
                            </div>
                        </div>
                    </div>`;
                container.insertAdjacentHTML('beforeend', html);
            });

            // Update input tersembunyi untuk variant_value_ids
            document.getElementById('variant_value_ids').value = JSON.stringify(
            variantValueIds); // Set nilai variant_value_ids
        }

        // Tambah Opsi
        document.getElementById('add-variant-option-btn').addEventListener('click', function() {
            const container = document.getElementById('variant-options-container');
            const html = `
                <div class="row mb-2 variant-option-row">
                    <div class="col-md-4">
                        <input type="text" class="form-control variant-option-name" name="variant_options[]"
                            placeholder="Nama Opsi (cth: Warna)" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control variant-option-id" name="variant_values[]"
                            placeholder="Nilai (pisahkan dengan koma, cth: Merah,Biru)" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-remove-option">×</button>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
        });

        // Hapus Opsi
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remove-option')) {
                e.target.closest('.variant-option-row').remove();
                refreshCombinations();
            }
        });

        // Re-render kombinasi saat input berubah
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('variant-option-name') || e.target.classList.contains(
                    'variant-option-id')) {
                refreshCombinations();
            }
        });
    </script>
@endpush
