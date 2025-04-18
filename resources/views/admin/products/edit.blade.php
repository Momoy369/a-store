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

                {{-- Variants --}}
                <div class="mb-3">
                    <label class="form-label">Kombinasi Varian</label>
                    <div id="variant-container">
                        @foreach ($product->variantCombinations as $i => $variant)
                            <div class="row g-2 mb-2 variant-row" data-index="{{ $i }}">
                                {{-- Opsi Varian --}}
                                <div class="col-md-4 variant-options">
                                    @foreach ($variant->variantValues as $j => $val)
                                        <div class="input-group mb-1">
                                            <select
                                                name="variants[{{ $i }}][options][{{ $j }}][option_id]"
                                                class="form-select me-1" required>
                                                <option value="">-- Opsi --</option>
                                                @foreach ($variantOptions as $option)
                                                    <option value="{{ $option->id }}" @selected($val->variant_option_id == $option->id)>
                                                        {{ $option->name }}</option>
                                                @endforeach
                                            </select>

                                            <select
                                                name="variants[{{ $i }}][options][{{ $j }}][value_id]"
                                                class="form-select" required>
                                                <option value="">-- Nilai --</option>
                                                @foreach ($variantOptions as $val)
                                                    <option value="{{ $val->id }}" @selected(in_array($val->id, $usedVariantValueIds))>
                                                        {{ $val->name }}</option>

                                                    <!-- Menampilkan variantValues terkait dengan variantOption -->
                                                    @foreach ($val->variantValues as $vv)
                                                        <option value="{{ $vv->id }}" @selected(in_array($vv->id, $usedVariantValueIds))>
                                                            {{ $vv->value }}</option>
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach
                                    <button type="button" class="btn btn-sm btn-secondary add-option-btn mt-1">+ Tambah
                                        Opsi</button>
                                </div>

                                {{-- Harga --}}
                                <div class="col-md-2">
                                    <input type="number" name="variants[{{ $i }}][price]" class="form-control"
                                        value="{{ $variant->price }}" placeholder="Harga" required>
                                </div>

                                {{-- Diskon --}}
                                <div class="col-md-2">
                                    <select name="variants[{{ $i }}][discount_type]" class="form-select">
                                        <option value="">— Diskon —</option>
                                        <option value="fixed" @selected($variant->discount_type === 'fixed')>Potongan Tetap</option>
                                        <option value="percent" @selected($variant->discount_type === 'percent')>Persentase</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <input type="number" step="0.01"
                                        name="variants[{{ $i }}][discount_value]" class="form-control"
                                        value="{{ $variant->discount_value }}" placeholder="Nilai Diskon">
                                </div>

                                {{-- Stok --}}
                                <div class="col-md-1">
                                    <input type="number" name="variants[{{ $i }}][stock]" class="form-control"
                                        value="{{ $variant->stock }}" required placeholder="Stok">
                                </div>

                                {{-- Hapus --}}
                                <div class="col-md-1 d-flex align-items-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-variant">&times;</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-primary mt-2" id="add-variant-btn">+ Tambah Kombinasi</button>
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
        document.addEventListener('DOMContentLoaded', function() {
            let variantIndex = document.querySelectorAll('.variant-row').length;

            const variantOptions = @json($variantOptions);
            let variantValues = [];

            @foreach ($variantOptions as $option)
                variantValues.push({
                    id: "{{ $option->id }}",
                    name: "{{ $option->name }}",
                    value: "{{ $option->value }}"
                });
            @endforeach

            function createOptionSelect(optionIndex = 0, selectedOptionId = '', selectedValueId = '') {
                let optionSelect =
                    `<select name="variants[${variantIndex}][options][${optionIndex}][option_id]" class="form-select me-1" required>`;
                optionSelect += `<option value="">-- Opsi --</option>`;
                variantOptions.forEach(opt => {
                    optionSelect +=
                        `<option value="${opt.id}" ${opt.id == selectedOptionId ? 'selected' : ''}>${opt.name}</option>`;
                });
                optionSelect += `</select>`;

                let valueSelect =
                    `<select name="variants[${variantIndex}][options][${optionIndex}][value_id]" class="form-select" required>`;
                valueSelect += `<option value="">-- Nilai --</option>`;

                // Pastikan variantValues adalah array
                const valuesArray = Array.isArray(variantValues) ? variantValues : Object.values(variantValues);

                valuesArray.filter(v => v.variant_option_id == selectedOptionId).forEach(v => {
                    valueSelect +=
                        `<option value="${v.id}" ${v.id == selectedValueId ? 'selected' : ''}>${v.value}</option>`;
                });
                valueSelect += `</select>`;

                return `<div class="input-group mb-1">${optionSelect}${valueSelect}</div>`;
            }


            // Add Variant Row
            document.getElementById('add-variant-btn').addEventListener('click', function() {
                const row = document.createElement('div');
                row.className = 'row g-2 mb-2 variant-row';
                row.dataset.index = variantIndex;

                row.innerHTML = `
        <div class="col-md-4 variant-options">${createOptionSelect()}</div>
        <div class="col-md-2"><input type="number" name="variants[${variantIndex}][price]" class="form-control" placeholder="Harga" required></div>
        <div class="col-md-2">
            <select name="variants[${variantIndex}][discount_type]" class="form-select">
                <option value="">— Diskon —</option>
                <option value="fixed">Potongan Tetap</option>
                <option value="percent">Persentase</option>
            </select>
        </div>
        <div class="col-md-2"><input type="number" step="0.01" name="variants[${variantIndex}][discount_value]" class="form-control" placeholder="Diskon"></div>
        <div class="col-md-1"><input type="number" name="variants[${variantIndex}][stock]" class="form-control" placeholder="Stok" required></div>
        <div class="col-md-1 d-flex align-items-center"><button type="button" class="btn btn-danger btn-sm remove-variant">&times;</button></div>
        <div class="mt-1"><button type="button" class="btn btn-sm btn-secondary add-option-btn">+ Tambah Opsi</button></div>
    `;

                document.getElementById('variant-container').appendChild(row);
                variantIndex++;
            });

            // Event Delegation
            document.addEventListener('click', function(e) {
                // Remove Variant
                if (e.target.classList.contains('remove-variant')) {
                    e.target.closest('.variant-row').remove();
                }

                // Add Option
                if (e.target.classList.contains('add-option-btn')) {
                    const variantRow = e.target.closest('.variant-row');
                    const container = variantRow.querySelector('.variant-options');
                    const optionIndex = container.querySelectorAll('.input-group').length;
                    container.insertAdjacentHTML('beforeend', createOptionSelect(optionIndex));
                }
            });

            // Dependent Select (Option => Value)
            document.addEventListener('change', function(e) {
                if (e.target.name.includes('[option_id]')) {
                    const optionSelect = e.target;
                    const selectedOptionId = optionSelect.value;
                    const valueSelect = optionSelect.closest('.input-group').querySelector(
                        '[name*="[value_id]"]');

                    // Memastikan variantValues adalah array
                    const valuesArray = Array.isArray(variantValues) ? variantValues : Object.values(
                        variantValues);

                    let valueOptions = '<option value="">-- Nilai --</option>';
                    valuesArray.filter(v => v.variant_option_id == selectedOptionId).forEach(v => {
                        valueOptions += `<option value="${v.id}">${v.value}</option>`;
                    });

                    valueSelect.innerHTML = valueOptions;
                }
            });

        });
    </script>
@endpush
