<nav class="navbar-custom d-flex align-items-center justify-content-between px-4 py-3">
<a href="{{ url('/') }}">
    <span class="brand fs-4 fw-bold">cuma.click</span>
</a>    

    <div class="nav-user">
        @auth
            <span class="me-3 text-dark">
                <i class="fa-brands fa-bilibili me-1"></i> Halo, 
                <a href="javascript:void(0)" data-bs-toggle="dropdown"><strong>{{ auth()->user()->name }}</strong></a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="{{ route('profile') }}">
                        <i class="fa-solid fa-user me-2"></i>Profile
                    </a>
                    <a class="dropdown-item" href="{{ route('logout') }}">
                        <i class="fa-solid fa-power-off me-2"></i>Logout
                    </a>
                </div>
            </span>
        @else
            <a href="{{ route('login') }}" class="login-link">
                Login
            </a>
        @endauth
    </div>
</nav>
