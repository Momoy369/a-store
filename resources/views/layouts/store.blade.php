<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('store.index') }}">TokoKita</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarStore">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarStore">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('store.index') }}">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('cart.index') }}">Keranjang</a></li>
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('orders.history') }}">Riwayat</a></li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">@csrf
                            <button class="nav-link btn btn-link text-danger" style="text-decoration: none;">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
