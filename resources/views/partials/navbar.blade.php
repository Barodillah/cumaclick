
<style>
    /* Styling Navbar

/* Logo Tracking */
.tracking-tight {
    letter-spacing: -1px;
}

/* Nav Link Animation */
.nav-link-custom {
    color: #4b5563 !important;
    font-weight: 500;
    position: relative;
    transition: color 0.3s ease;
}

.nav-link-custom:hover {
    color: #B45A71 !important;
}

/* User Dropdown Button */
.btn-user-dropdown {
    background-color: #f8f9fa;
    transition: background-color 0.2s ease;
}

.btn-user-dropdown:hover {
    background-color: #F9ECEF;
}
 
.avatar-circle {
    width: 32px;
    height: 32px;
    background: #B45A71;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
}

/* Dropdown Menu Item Hover */
.dropdown-item:hover {
    background-color: #F9ECEF;
    color: #B45A71;
}

.fs-xs {
    font-size: 0.75rem;
}

.navbar {
    z-index: 1050 !important;
}
</style>
<nav class="navbar navbar-expand-md navbar-light bg-white sticky-top navbar-custom px-lg-5 py-3 shadow-sm">
    <div class="container-fluid">
        <a href="{{ url('/') }}" class="navbar-brand fw-bold fs-4 text-primary tracking-tight">
            cuma<span class="text-dark">.click</span>
        </a>

        {{-- ===== JIKA LOGIN ===== --}}
        @auth
            <div class="dropdown ms-auto">
                <button class="btn btn-user-dropdown d-flex align-items-center gap-2 px-3 py-2 rounded-pill border-0" 
                        type="button" data-bs-toggle="dropdown">
                    <div class="avatar-circle">
                        <i class="fa-solid fa-user-ninja"></i>
                    </div>
                    <span class="fw-semibold d-none d-md-inline text-dark">
                        {{ auth()->user()->name }}
                    </span>
                    <i class="fa-solid fa-chevron-down fs-xs text-muted"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-3 p-2 rounded-4">
                    <li>
                        <a class="dropdown-item rounded-3 py-2" href="{{ route('profile') }}">
                            <i class="fa-solid fa-address-card me-2 text-primary"></i> Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider opacity-50"></li>
                    <li>
                        <a class="dropdown-item rounded-3 py-2 text-danger" href="{{ route('logout') }}">
                            <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>

        {{-- ===== JIKA GUEST ===== --}}
        @else
            <button class="navbar-toggler border-0 shadow-none" type="button" 
                    data-bs-toggle="collapse" data-bs-target="#navbarGuest">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarGuest">
                <ul class="navbar-nav ms-auto align-items-md-center gap-2">
                    @if (Request::is('/'))
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom px-3" href="#fitur">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom px-3" href="#faq">FAQ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom px-3" href="#kontak">Contact</a>
                        </li>
                    @endif

                    <li class="nav-item ms-md-2">
                        <a class="btn btn-primary px-4 py-2 rounded-pill fw-bold shadow-sm" href="{{ route('login') }}">
                            Login
                        </a>
                    </li>
                </ul>
            </div>
        @endauth
    </div>
</nav>
