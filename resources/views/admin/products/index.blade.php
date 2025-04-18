@extends('admin.layout')

@section('title', 'Daftar Produk')

@section('content-admin')
    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="m-0">Daftar Produk</h1>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Produk
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            @forelse ($products as $product)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top"
                                style="height: 200px; object-fit: cover;" alt="Gambar Produk">
                        @else
                            <div class="bg-secondary text-white text-center d-flex align-items-center justify-content-center"
                                style="height: 200px;">
                                Tidak ada gambar
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title mb-2 text-truncate d-block w-100" title="{{ $product->name }}">
                                {{ $product->name }}
                            </h5>
                            <div class="mb-2">
                                <span class="badge bg-secondary">
                                    {{ $product->category->name ?? '-' }}
                                    @if ($product->category->parent)
                                        - {{ $product->category->parent->name }}
                                    @endif
                                </span>
                            </div>

                            @php
                                // Hitung total stok dari semua kombinasi varian
                                $total_variant_stock = $product->variantCombinations->sum('stock');

                                // Gunakan stok dari kombinasi varian jika lebih besar dari stok produk
                                $display_stock =
                                    $total_variant_stock > $product->stock ? $total_variant_stock : $product->stock;
                            @endphp

                            <div class="mb-2">
                                <span class="badge bg-success">Stok: {{ $display_stock }}</span>
                            </div>


                            <!-- Menampilkan harga dengan diskon jika ada -->
                            @php
                                // Hitung harga produk setelah diskon (jika ada)
                                $product_discount_price = $product->price;
                                if ($product->discount) {
                                    $product_discount_price -=
                                        $product_discount_price * ($product->discount->discount_percentage / 100);
                                }

                                // Cari harga kombinasi varian terendah (dengan diskon jika ada)
                                $lowest_variant_price = null;
                                foreach ($product->variantCombinations as $combination) {
                                    $variant_price = $combination->price > 0 ? $combination->price : $product->price;

                                    if ($combination->discount_type === 'percent') {
                                        $variant_price -= $variant_price * ($combination->discount_value / 100);
                                    } elseif ($combination->discount_type === 'fixed') {
                                        $variant_price -= $combination->discount_value;
                                    }

                                    if (is_null($lowest_variant_price) || $variant_price < $lowest_variant_price) {
                                        $lowest_variant_price = $variant_price;
                                    }
                                }

                                // Tentukan harga yang akan ditampilkan (paling rendah dari keduanya)
                                $final_display_price =
                                    $lowest_variant_price !== null && $lowest_variant_price < $product_discount_price
                                        ? $lowest_variant_price
                                        : $product_discount_price;

                                // Hitung potongan jika memang lebih rendah dari harga asli
                                $has_discount = $final_display_price < $product->price;
                                $discount_percentage = $has_discount
                                    ? round((($product->price - $final_display_price) / $product->price) * 100)
                                    : 0;
                            @endphp

                            <p class="text-primary fw-bold mb-3">
                                @if ($has_discount)
                                    <span
                                        class="text-decoration-line-through">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                    Rp{{ number_format($final_display_price, 0, ',', '.') }}
                                    <small class="text-success">-{{ $discount_percentage }}%</small>
                                @else
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                @endif
                            </p>


                            @if ($product->variantCombinations && $product->variantCombinations->count())
                                <div class="mb-3">
                                    <strong class="d-block mb-1">Varian:</strong>
                                    <ul class="list-unstyled mb-0">
                                        @php
                                            $lowest_price = null;
                                            $shown_combinations = $product->variantCombinations->take(2); // Ambil dua kombinasi pertama
                                        @endphp
                                        @foreach ($shown_combinations as $combination)
                                            @php
                                                $base_price =
                                                    $combination->price > 0 ? $combination->price : $product->price;
                                                $final_price = $base_price;

                                                if ($combination->discount_type === 'percent') {
                                                    $final_price -= $base_price * ($combination->discount_value / 100);
                                                } elseif ($combination->discount_type === 'fixed') {
                                                    $final_price -= $combination->discount_value;
                                                }

                                                $final_price = max(0, $final_price);

                                                if (is_null($lowest_price) || $final_price < $lowest_price) {
                                                    $lowest_price = $final_price;
                                                }
                                            @endphp
                                            <li class="small text-muted text-truncate">
                                                • @foreach ($combination->variantValues as $value)
                                                    <span>{{ $value->name }}: {{ $value->value }}</span><br>
                                                @endforeach
                                                — Stok: {{ $combination->stock }} —
                                                Harga: Rp{{ number_format($final_price, 0, ',', '.') }}
                                                @if ($combination->discount_type == 'percent')
                                                    <span class="text-danger"> (Diskon:
                                                        {{ number_format($combination->discount_value, 0, ',', '.') }}%)</span>
                                                @elseif($combination->discount_type == 'fixed')
                                                    <span class="text-danger"> (Diskon:
                                                        Rp{{ number_format($combination->discount_value, 0, ',', '.') }})</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                    <a href="{{ route('admin.products.show', $product) }}"
                                        class="btn btn-sm btn-outline-secondary mt-2">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </a>
                                </div>
                            @endif

                            <div class="d-flex align-items-center">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning me-2">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <!-- Tombol Toggle Aktif/Nonaktif -->
                                <form action="{{ route('admin.products.toggleStatus', $product) }}" method="POST"
                                    class="me-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="btn btn-sm {{ $product->is_active ? 'btn-success' : 'btn-secondary' }}">
                                        <i class="fas fa-toggle-{{ $product->is_active ? 'on' : 'off' }}"></i>
                                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>

                                <!-- Tombol Delete Trigger Modal -->
                                <button
                                    title="Klik untuk {{ $product->is_active ? 'menonaktifkan' : 'mengaktifkan' }} produk"
                                    type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-box-open fa-2x mb-2"></i>
                        <p class="mb-0">Belum ada produk yang ditambahkan.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    </div>


    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p>Yakin ingin menghapus produk <strong id="deleteProductName"></strong>?</p>
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
                const productId = button.getAttribute('data-id');
                const productName = button.getAttribute('data-name');

                // Update teks produk di dalam modal
                deleteModal.querySelector('#deleteProductName').textContent = productName;

                // Update action form
                deleteModal.querySelector('#deleteForm').action = `/admin/products/${productId}`;
            });
        });
    </script>
@endpush
