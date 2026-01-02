<nav class="navbar navbar-expand-md navbar-custom px-4 py-3 d-flex justify-content-between align-items-center">
    <a href="{{ url('/') }}" class="text-primary navbar-brand fw-bold fs-4">
        cuma.click
    </a>

    {{-- ===== JIKA LOGIN ===== --}}
    @auth
        <div class="dropdown">
            <a href="#" class="text-dark fw-semibold text-decoration-none"
               data-bs-toggle="dropdown">
                <i class="fa-brands fa-bilibili me-1"></i>
                Halo, {{ auth()->user()->name }}
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li>
                    <a class="dropdown-item" href="{{ route('profile') }}">
                        <i class="fa-solid fa-user me-2"></i> Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}">
                        <i class="fa-solid fa-power-off me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>

    {{-- ===== JIKA GUEST ===== --}}
    @else
        {{-- hamburger: hanya muncul < md --}}
        <button class="navbar-toggler d-md-none" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarGuest">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- menu: langsung tampil di desktop --}}
        <div class="collapse navbar-collapse justify-content-end" id="navbarGuest">
            <ul class="navbar-nav">

                @if (Request::is('/'))
                    <li class="nav-item">
                        <a class="nav-link" href="#fitur">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#faq">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Contact</a>
                    </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="{{ route('login') }}">
                        Login
                    </a>
                </li>
            </ul>
        </div>
    @endauth
</nav>
