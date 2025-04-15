@extends('layouts.app')

@section('title', 'Toko Online')

@section('content')
    <!-- Hero Section -->
    <section class="hero bg-primary text-white text-center py-5">
        <div class="container">
            <h1 class="display-4">Selamat Datang di Toko Kami!</h1>
            <p class="lead">Temukan produk berkualitas dengan harga terbaik.</p>
            <a href="#produk" class="btn btn-light btn-lg">Lihat Produk</a>
        </div>
    </section>

    <!-- Kategori Produk -->
    <section id="kategori" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Kategori Produk</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <img src="https://via.placeholder.com/300" class="card-img-top" alt="Kategori 1">
                        <div class="card-body">
                            <h5 class="card-title">Kategori 1</h5>
                            <p class="card-text">Temukan produk-produk terbaik di kategori ini.</p>
                            <a href="#" class="btn btn-primary">Lihat Semua</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <img src="https://via.placeholder.com/300" class="card-img-top" alt="Kategori 2">
                        <div class="card-body">
                            <h5 class="card-title">Kategori 2</h5>
                            <p class="card-text">Temukan produk-produk terbaik di kategori ini.</p>
                            <a href="#" class="btn btn-primary">Lihat Semua</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <img src="https://via.placeholder.com/300" class="card-img-top" alt="Kategori 3">
                        <div class="card-body">
                            <h5 class="card-title">Kategori 3</h5>
                            <p class="card-text">Temukan produk-produk terbaik di kategori ini.</p>
                            <a href="#" class="btn btn-primary">Lihat Semua</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Produk Unggulan -->
    <section id="produk" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Produk Unggulan</h2>
            <div class="row">
                @foreach ($products as $product)
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top"
                                    alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-secondary text-white text-center py-5">Tidak ada gambar</div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">Lihat
                                    Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonial Section (Optional) -->
    <section class="testimonials py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Apa Kata Pelanggan</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <p class="card-text">"Produk yang saya beli sangat bagus dan sesuai dengan deskripsi. Pengiriman
                                cepat!"</p>
                            <p class="text-muted">- Pelanggan A</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <p class="card-text">"Pelayanan pelanggan yang sangat baik dan ramah. Saya pasti akan belanja
                                lagi."</p>
                            <p class="text-muted">- Pelanggan B</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <p class="card-text">"Produk berkualitas tinggi dengan harga yang terjangkau. Sangat puas!"</p>
                            <p class="text-muted">- Pelanggan C</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; 2025 Toko Online. Semua hak dilindungi.</p>
            <p>Follow us:
                <a href="#" class="text-white">Facebook</a> |
                <a href="#" class="text-white">Instagram</a> |
                <a href="#" class="text-white">Twitter</a>
            </p>
        </div>
    </footer>
@endsection
