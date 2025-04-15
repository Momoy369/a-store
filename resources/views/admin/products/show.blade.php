@extends('admin.layout')

@section('content-admin')
    <div class="container py-4">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center bg-light dark:bg-dark">
                <h5 class="mb-0">Detail Produk: {{ $product->name }}
                    @if ($product->is_active)
                        <span class="badge bg-success ms-2">Aktif</span>
                    @else
                        <span class="badge bg-secondary ms-2">Non-Aktif</span>
                    @endif
                </h5>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body row g-4">
                <!-- Gambar -->
                <div class="col-md-4">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded shadow-sm"
                            alt="{{ $product->name }}">
                    @else
                        <div class="text-muted text-center border rounded p-4">Tidak ada gambar</div>
                    @endif
                </div>

                <!-- Detail Utama -->
                <div class="col-md-8">
                    <h4 class="fw-bold">{{ $product->name }}</h4>
                    <p class="mb-2">
                        @if ($product->discount)
                            <span
                                class="text-decoration-line-through text-muted">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                            <span
                                class="fw-bold text-danger">Rp{{ number_format($product->price - $product->price * ($product->discount->discount_percentage / 100), 0, ',', '.') }}</span>
                            <span class="badge bg-success ms-2">{{ $product->discount->discount_percentage }}% OFF</span>
                        @else
                            <span class="fw-bold">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                        @endif
                    </p>

                    <p class="mb-2">
                        <strong>Kategori:</strong> {{ $product->category->name ?? '-' }}
                    </p>
                    <p class="mb-2">
                        <strong>Status:</strong>
                        @if ($product->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Non-Aktif</span>
                        @endif
                    </p>

                    @php
                        $total_variant_stock = $product->variants->sum('stock');
                        $total_stock = max($product->stock, $total_variant_stock);
                    @endphp
                    <p class="mb-2">
                        <strong>Stok:</strong> <span class="badge bg-primary">{{ $total_stock }}</span>
                    </p>

                    <div class="p-3 border rounded bg-light">
                        <p class="mb-2"><strong>Deskripsi:</strong><br>
                            {!! nl2br(e($product->description ?? '-')) !!}
                        </p>
                    </div>
                </div>

                <!-- Daftar Varian -->
                <div class="col-12">
                    <h5 class="fw-bold">Daftar Varian</h5>
                    @if ($product->variants->count())
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Nilai</th>
                                        <th>Harga (Sebelum Diskon)</th>
                                        <th>Diskon</th>
                                        <th>Stok</th>
                                        <th>Harga (Setelah Diskon)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product->variants as $variant)
                                        @php
                                            $base_price = $variant->price > 0 ? $variant->price : $product->price;
                                            $final_price = $base_price;

                                            if ($variant->discount_type === 'percent') {
                                                $final_price -= $base_price * ($variant->discount_value / 100);
                                            } elseif ($variant->discount_type === 'fixed') {
                                                $final_price -= $variant->discount_value;
                                            }

                                            $final_price = max(0, $final_price);
                                        @endphp
                                        <tr>
                                            <td>{{ $variant->name }}</td>
                                            <td>{{ $variant->value }}</td>
                                            <td>Rp{{ number_format($base_price, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($variant->discount_type === 'percent')
                                                    {{ $variant->discount_value }}%
                                                @elseif ($variant->discount_type === 'fixed')
                                                    Rp{{ number_format($variant->discount_value, 0, ',', '.') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $variant->stock }}</span>
                                            </td>
                                            <td>Rp{{ number_format($final_price, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Tidak ada varian yang tersedia.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
