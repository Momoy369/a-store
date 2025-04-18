@extends('admin.layout')

@section('content-admin')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Semua Kombinasi Varian Produk</h5>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @foreach ($products as $product)
                    <div class="mb-4">
                        <h6 class="fw-bold">{{ $product->name }}</h6>

                        @if ($product->variantCombinations->count())
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Varian</th>
                                            <th>Harga</th>
                                            <th>Diskon</th>
                                            <th>Stok</th>
                                            <th>Harga Final</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product->variantCombinations as $combination)
                                            @php
                                                $base_price = $combination->price ?? $product->price;
                                                $final_price = $base_price;

                                                if ($combination->discount_type === 'percent') {
                                                    $final_price -= $base_price * ($combination->discount_value / 100);
                                                } elseif ($combination->discount_type === 'fixed') {
                                                    $final_price -= $combination->discount_value;
                                                }

                                                $final_price = max(0, $final_price);
                                            @endphp
                                            <tr>
                                                <td>
                                                    @foreach ($combination->variantValues as $value)
                                                        <span class="badge bg-light border text-dark">
                                                            {{ $value->option->name }}: {{ $value->value }}
                                                        </span>
                                                    @endforeach
                                                </td>
                                                <td>Rp{{ number_format($base_price, 0, ',', '.') }}</td>
                                                <td>
                                                    @if ($combination->discount_type === 'percent')
                                                        {{ $combination->discount_value }}%
                                                    @elseif ($combination->discount_type === 'fixed')
                                                        Rp{{ number_format($combination->discount_value, 0, ',', '.') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td><span class="badge bg-primary">{{ $combination->stock }}</span></td>
                                                <td>Rp{{ number_format($final_price, 0, ',', '.') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.products.combinations.edit', [$product, $combination]) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form
                                                        action="{{ route('admin.products.combinations.destroy', [$product, $combination]) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus kombinasi ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger"><i
                                                                class="fas fa-trash-alt"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted fst-italic">Belum ada kombinasi untuk produk ini.</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
