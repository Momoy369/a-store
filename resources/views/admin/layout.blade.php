<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title') {{ \App\Models\Setting::get('site_name', 'Nama Toko') }} | Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="{{ \App\Models\Setting::get('meta_keywords', '') }}">
    <meta name="description" content="{{ \App\Models\Setting::get('meta_description', '') }}">

    <link rel="icon" href="{{ asset('storage/' . \App\Models\Setting::get('favicon')) }}" type="image/x-icon">

    {{-- AdminLTE CSS --}}
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

    {{-- Tambahan Bootstrap 5 jika dibutuhkan --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        {{-- Navbar --}}
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Sidebar toggle button-->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
        </nav>

        {{-- Sidebar --}}
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('admin.dashboard') }}" class="brand-link">
                <span class="brand-text font-weight-light"><img
                        src="{{ asset('storage/' . \App\Models\Setting::get('logo')) }}" alt="Logo" height="40">
                    - {{ \App\Models\Setting::get('site_name', 'Nama Toko') }}</span>
            </a>

            <!-- Sidebar Menu -->
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}"
                                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li
                            class="nav-item has-treeview {{ request()->routeIs('admin.products.*') ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-box"></i>
                                <p>
                                    Produk
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.products.index') }}"
                                        class="nav-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Daftar Produk</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.products.combinations.index') }}"
                                        class="nav-link {{ request()->routeIs('admin.products.combinations.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Kombinasi Varian</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.categories.index') }}"
                                class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>Kategori</p>
                            </a>
                        </li>
                        @can('view orders')
                            <li class="nav-item">
                                <a href="{{ route('admin.orders.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-shopping-cart"></i>
                                    <p>Pesanan</p>
                                </a>
                            </li>
                        @endcan

                        <!-- Menu Diskon Baru -->
                        <li class="nav-item">
                            <a href="{{ route('admin.discounts.index') }}"
                                class="nav-link {{ request()->routeIs('admin.discounts.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-percent"></i>
                                <p>Diskon</p>
                            </a>
                        </li>

                        <!-- Menu Kupon -->
                        <li class="nav-item">
                            <a href="{{ route('admin.coupons.index') }}"
                                class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-ticket-alt"></i>
                                <p>Kupon</p>
                            </a>
                        </li>

                        <!-- Low Stock -->
                        <li class="nav-item">
                            <a href="{{ route('admin.low-stock') }}"
                                class="nav-link {{ request()->routeIs('admin.low-stock.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-box"></i>
                                <p>Stok Rendah</p>
                            </a>
                        </li>

                        <!-- Menu Pengguna -->
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}"
                                class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user"></i>
                                <p>Pengguna</p>
                            </a>
                        </li>

                        <!-- Menu Pengaturan -->
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.index') }}"
                                class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>Pengaturan</p>
                            </a>
                        </li>

                        <li class="nav-item mt-3">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="btn btn-danger w-100">Logout</button>
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>

        </aside>

        {{-- Content Wrapper --}}
        <div class="content-wrapper p-4">
            @yield('content-admin')
        </div>

        {{-- Footer --}}
        <footer class="main-footer text-sm">
            <strong>&copy; {{ date('Y') }} Admin Panel.</strong> All rights reserved.
        </footer>
    </div>


    {{-- AdminLTE JS --}}
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 pada elemen select dengan id atau class yang sesuai
            $('select[name="product_id"]').select2();
        });
    </script>

    @stack('scripts')

    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    @endpush

    @push('scripts')
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    @endpush
</body>

</html>
