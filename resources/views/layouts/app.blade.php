<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" height="40"> <!-- Add your logo here -->
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('store.index') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('store.index') }}">Shop</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">Cart</a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile') }}">Profile</a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="btn btn-primary" href="{{ route('login') }}">Login</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-dark text-white py-4 mt-5">
            <div class="container text-center">
                <p>&copy; {{ date('Y') }} Toko Online. All rights reserved.</p>
                <div>
                    <a href="#" class="text-white">Facebook</a> |
                    <a href="#" class="text-white">Instagram</a> |
                    <a href="#" class="text-white">Twitter</a>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
</body>

</html>
