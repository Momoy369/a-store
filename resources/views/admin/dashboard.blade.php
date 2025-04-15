@extends('admin.layout')

@section('title', 'Dashboard')

@section('content-admin')

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body">
                    <h5>Total Pesanan</h5>
                    <h3>{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <h5>Total Pendapatan</h5>
                    <h3>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <h4>Status Pesanan</h4>
    <canvas id="orderStatusChart" width="400" height="200"></canvas>

    <hr>
    <h4>Pesanan Terbaru</h4>
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

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-white bg-primary shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Produk</h5>
                    <h3>{{ $totalProducts }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-success shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Kategori</h5>
                    <h3>{{ $totalCategories }}</h3>
                </div>
            </div>
        </div>
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
                    </li>
                @empty
                    <li class="list-group-item">Belum ada produk.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection

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
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
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
