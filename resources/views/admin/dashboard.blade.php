@extends('admin.layout')

@section('title', 'Dashboard')

@section('content-admin')

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body">
                    <h5>Total Pesanan</h5>
                    <h3>{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-sm hoverable">
                <div class="card-body">
                    <h5>Total Pendapatan</h5>
                    <h3>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <div class="card-title">
                        <h5 class="card-title">Total Produk</h5>
                        <h3>{{ $totalProducts }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <div class="card-title">
                        <h5 class="card-title">Total Kategori</h5>
                        <h3>{{ $totalCategories }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h4>Low Stock Product</h4>
    <!-- Widget for Low Stock Products -->
    <div class="col-lg-4 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $lowStockCount }}</h3>
                <p>Produk dengan Stok Rendah</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <a href="{{ route('admin.low-stock') }}" class="small-box-footer">
                Lihat lebih lanjut <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <h4>Status Pesanan</h4>
    <canvas id="orderStatusChart" width="400" height="200"></canvas>

    <hr>
    <h4>Pesanan Terbaru</h4>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pelanggan</th>
                    <th>Status</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recentOrders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->name }}</td>
                        <td><span class="badge bg-secondary">{{ $order->status }}</span></td>
                        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card mb-4">
        <div class="card-header">Statistik Produk per Kategori</div>
        <div class="card-body">
            <canvas id="productChart" height="120"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Produk Terbaru</div>
        <div class="card-body">
            <ul class="list-group">
                @forelse ($latestProducts as $product)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $product->name }}
                        <span
                            class="badge bg-primary rounded-pill">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-info">Edit</a>
                    </li>
                @empty
                    <li class="list-group-item">Belum ada produk.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .hoverable:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('orderStatusChart');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($orderStatuses->keys()) !!},
                datasets: [{
                    label: 'Jumlah Pesanan',
                    data: {!! json_encode($orderStatuses->values()) !!},
                    backgroundColor: function(context) {
                        const value = context.raw;
                        return value > 10 ? 'rgba(54, 162, 235, 0.7)' : 'rgba(255, 99, 132, 0.7)';
                    },
                }]
            }
        });
    </script>
    <script>
        const ctx = document.getElementById('productChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($productsPerCategory->pluck('name')) !!},
                datasets: [{
                    label: 'Jumlah Produk',
                    data: {!! json_encode($productsPerCategory->pluck('products_count')) !!},
                    backgroundColor: '#4e73df'
                }]
            }
        });
    </script>
@endpush
