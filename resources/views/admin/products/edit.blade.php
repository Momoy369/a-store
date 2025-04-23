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

                {{-- Nama Produk --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                </div>

                {{-- Kategori --}}
                <div class="mb-3">
                    <label for="category_id" class="form-label">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected($product->category_id == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Harga & Stok Default --}}
                <div class="mb-3">
                    <label for="price" class="form-label">Harga Dasar</label>
                    <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
                </div>

                <div class="mb-3">
                    <label for="stock" class="form-label">Stok Dasar</label>
                    <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
                </div>

                {{-- Variant Combinations --}}
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Kombinasi Varian</h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addVariantCombination()">+ Tambah
                            Kombinasi</button>
                    </div>
                    <div class="card-body" id="variant-combinations-wrapper">
                        @foreach ($product->variantCombinations as $i => $combination)
                            <div class="row variant-combination-row mb-3" data-index="{{ $i }}">
                                <div class="col-md-12 mb-2">
                                    <strong>Kombinasi {{ $i + 1 }}</strong>
                                    <button type="button" class="btn btn-sm btn-danger float-end"
                                        onclick="removeVariantCombination(this)">Hapus</button>
                                </div>

                                @foreach ($combination->variantValues as $j => $val)
                                    <div class="col-md-4 d-flex align-items-center mb-2">
                                        {{-- Pilih Opsi --}}
                                        <select
                                            name="variants[{{ $i }}][options][{{ $j }}][option_id]"
                                            class="form-select me-1 option-select" required>
                                            <option value="">-- Opsi --</option>
                                            @foreach ($variantOptions as $option)
                                                <option value="{{ $option->id }}" @selected($val->variant_option_id == $option->id)>
                                                    {{ $option->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        {{-- Pilih Nilai --}}
                                        <select
                                            name="variants[{{ $i }}][options][{{ $j }}][value_id]"
                                            class="form-select value-select" required>
                                            <option value="">-- Nilai --</option>
                                            @foreach ($variantOptions->firstWhere('id', $val->variant_option_id)?->variantValues ?? [] as $vv)
                                                <option value="{{ $vv->id }}" @selected($val->id == $vv->id)>
                                                    {{ $vv->value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach

                                {{-- Harga, Stok, Diskon --}}
                                <div class="col-md-4">
                                    <input type="number" name="variants[{{ $i }}][price]" class="form-control"
                                        value="{{ $combination->price }}" placeholder="Harga" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" name="variants[{{ $i }}][stock]" class="form-control"
                                        value="{{ $combination->stock }}" placeholder="Stok" required>
                                </div>
                                <div class="col-md-4 d-flex">
                                    <select name="variants[{{ $i }}][discount_type]" class="form-select me-2">
                                        <option value="">-- Diskon --</option>
                                        <option value="fixed" @selected($combination->discount_type == 'fixed')>Fix (Rp)</option>
                                        <option value="percentage" @selected($combination->discount_type == 'percentage')>%</option>
                                    </select>
                                    <input type="number" name="variants[{{ $i }}][discount_value]"
                                        class="form-control" value="{{ $combination->discount_value }}"
                                        placeholder="Nilai Diskon">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Gambar --}}
                <div class="mb-3">
                    <label for="image" class="form-label">Gambar Baru (opsional)</label>
                    <input type="file" name="image" class="form-control">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid mt-2"
                            style="max-height: 150px;">
                    @endif
                </div>

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
                </div>

                {{-- Submit --}}
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
        const variantValues = [];

        @foreach ($variantOptions as $option)
            @foreach ($option->variantValues as $val)
                variantValues.push({
                    id: "{{ $val->id }}",
                    value: "{{ $val->value }}",
                    variant_option_id: "{{ $option->id }}"
                });
            @endforeach
        @endforeach

        function addVariantCombination() {
            const index = document.querySelectorAll('.variant-combination-row').length;

            const wrapper = document.getElementById('variant-combinations-wrapper');
            const div = document.createElement('div');
            div.className = 'row variant-combination-row mb-3';
            div.dataset.index = index;

            let optionSelect = `<select name="variants[${index}][options][0][option_id]" class="form-select me-1 option-select" required>
            <option value="">-- Opsi --</option>
            @foreach ($variantOptions as $option)
                <option value="{{ $option->id }}">{{ $option->name }}</option>
            @endforeach
        </select>`;

            let valueSelect = `<select name="variants[${index}][options][0][value_id]" class="form-select value-select" required>
            <option value="">-- Nilai --</option>
        </select>`;

            div.innerHTML = `
            <div class="col-md-12 mb-2">
                <strong>Kombinasi Baru</strong>
                <button type="button" class="btn btn-sm btn-danger float-end" onclick="removeVariantCombination(this)">Hapus</button>
            </div>
            <div class="col-md-4 d-flex align-items-center mb-2">
                ${optionSelect} ${valueSelect}
            </div>
            <div class="col-md-4"><input type="number" name="variants[${index}][price]" class="form-control" placeholder="Harga" required></div>
            <div class="col-md-4"><input type="number" name="variants[${index}][stock]" class="form-control" placeholder="Stok" required></div>
            <div class="col-md-4 d-flex mt-2">
                <select name="variants[${index}][discount_type]" class="form-select me-2">
                    <option value="">-- Diskon --</option>
                    <option value="fixed">Fix (Rp)</option>
                    <option value="percentage">%</option>
                </select>
                <input type="number" name="variants[${index}][discount_value]" class="form-control" placeholder="Nilai Diskon">
            </div>
        `;

            wrapper.appendChild(div);
        }

        function removeVariantCombination(el) {
            el.closest('.variant-combination-row').remove();
        }

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('option-select')) {
                const optionId = e.target.value;
                const valueSelect = e.target.closest('.d-flex').querySelector('.value-select');
                valueSelect.innerHTML = '<option value="">-- Nilai --</option>';

                const filtered = variantValues.filter(v => v.variant_option_id === optionId);
                filtered.forEach(val => {
                    const opt = document.createElement('option');
                    opt.value = val.id;
                    opt.textContent = val.value;
                    valueSelect.appendChild(opt);
                });
            }
        });
    </script>
@endpush
