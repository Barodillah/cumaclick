<nav class="navbar-custom d-flex align-items-center justify-content-between px-4 py-3">
    <span class="brand fs-4 fw-bold">cuma.click</span>

    <div class="nav-user">
        @auth
            <span class="me-3 text-dark">
                👋 Halo, <strong>{{ auth()->user()->name }}</strong>
            </span>
            <a href="{{ route('logout') }}" class="logout-link">
                <i class="fa-solid fa-power-off"></i>
            </a>
        @else
            <a href="{{ route('login') }}" class="login-link">
                Login
            </a>
        @endauth
    </div>
</nav>
