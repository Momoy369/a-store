@extends('admin.layout')

@section('title', 'Edit Varian Produk')

@section('content-admin')
    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="m-0">Edit Varian Produk: {{ $product->name }}</h1>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Produk
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            @foreach ($product->variants as $variant)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title mb-2">{{ $variant->name }} - {{ $variant->value }}</h5>

                            <form action="{{ route('admin.products.variants.update', [$product, $variant]) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <!-- Nama Varian -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Varian</label>
                                    <input type="text" name="name" class="form-control" value="{{ $variant->name }}" required>
                                </div>

                                <!-- Nilai Varian -->
                                <div class="mb-3">
                                    <label for="value" class="form-label">Nilai Varian</label>
                                    <input type="text" name="value" class="form-control" value="{{ $variant->value }}" required>
                                </div>

                                <!-- Harga Varian -->
                                <div class="mb-3">
                                    <label for="price" class="form-label">Harga Varian</label>
                                    <input type="number" name="price" class="form-control" value="{{ $variant->price }}" required>
                                </div>

                                <!-- Stok Varian -->
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stok Varian</label>
                                    <input type="number" name="stock" class="form-control" value="{{ $variant->stock }}" required>
                                </div>

                                <!-- Diskon Varian -->
                                <div class="mb-3">
                                    <label for="discount_type" class="form-label">Tipe Diskon</label>
                                    <select name="discount_type" class="form-select">
                                        <option value="none" {{ $variant->discount_type == 'none' ? 'selected' : '' }}>Tidak Ada Diskon</option>
                                        <option value="percent" {{ $variant->discount_type == 'percent' ? 'selected' : '' }}>Diskon Persentase</option>
                                        <option value="fixed" {{ $variant->discount_type == 'fixed' ? 'selected' : '' }}>Diskon Tetap</option>
                                    </select>
                                </div>

                                <!-- Nilai Diskon -->
                                <div class="mb-3">
                                    <label for="discount_value" class="form-label">Nilai Diskon</label>
                                    <input type="number" name="discount_value" class="form-control" value="{{ $variant->discount_value }}" {{ $variant->discount_type == 'none' ? 'disabled' : '' }}>
                                </div>

                                <!-- Tombol Simpan -->
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $variant->id }}" data-name="{{ $variant->name }}">
                                        <i class="fas fa-trash-alt"></i> Hapus Varian
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Varian -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Varian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p>Yakin ingin menghapus varian <strong id="deleteVariantName"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const variantId = button.getAttribute('data-id');
                const variantName = button.getAttribute('data-name');

                // Update teks varian di dalam modal
                deleteModal.querySelector('#deleteVariantName').textContent = variantName;

                // Update action form
                deleteModal.querySelector('#deleteForm').action = `/admin/products/{{ $product->id }}/variants/${variantId}`;
            });
        });
    </script>
@endpush
