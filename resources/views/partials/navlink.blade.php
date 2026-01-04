<style>
/* Container Sub-Navbar */
.sub-nav-container {
    background: #ffffff; /* Background putih bersih */
    border: 1px solid #edf2f7;
    border-radius: 16px; /* Sudut membulat modern */
    max-width: 800px;    /* Agar tidak terlalu lebar di desktop */
    margin: 0 auto;
}

/* Base Link Style */
.custom-sub-item {
    color: #64748b !important;
    font-size: 0.9rem;
    font-weight: 500;
    padding: 10px 15px !important;
    border-radius: 12px !important;
    transition: all 0.25s ease;
    border: 1px solid transparent;
    white-space: nowrap;
    align-items: center;       /* center vertikal */
    justify-content: center;        /* jarak icon & text */
    text-align: center;
    display: flex !important;
}

/* Hover Effect */
.custom-sub-item:hover {
    background-color: #f3f3f3ff;
    color: #B45A71 !important;
}

/* Active State (Pill Background) */
.custom-sub-item.active {
    background-color: #F9ECEF !important; /* Biru Primary */
    color: #B45A71 !important;
    box-shadow: 0 4px 12px rgba(180, 90, 113, 0.1);
}
 
/* Khusus Badge Koin */
.coin-badge {
    background: rgba(255, 193, 7, 0.1);
    color: #856404;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.custom-sub-item.active .coin-badge {
    background: rgba(255, 255, 255, 0.2);
    color: #B45A71;
}

/* Hilangkan Scrollbar tapi tetap bisa di-scroll (untuk mobile) */
.overflow-x-auto::-webkit-scrollbar {
    display: none;
}
.overflow-x-auto {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
/* Sticky Sub Navbar */
.sub-nav-wrapper {
    position: sticky;
    top: 100px; /* sesuaikan dengan tinggi navbar utama */
    z-index: 1020;
}
</style>
@auth
    @php
        // Daftar route di mana sub-navbar ini akan muncul
        $allowedRoutes = ['links', 'dashboard', 'premium', 'admin', '/', 'files'];
        $isAllowed = false;
        foreach($allowedRoutes as $route) {
            if (Request::is($route) || Request::is($route . '/*')) {
                $isAllowed = true;
                break;
            }
        }
    @endphp

    @if($isAllowed)
    <div class="container mt-3 mb-4 sub-nav-wrapper">
        <div class="sub-nav-container p-1 shadow-sm d-flex align-items-center">
            <div class="nav nav-pills flex-nowrap w-100 overflow-x-auto">
                
                {{-- ADMIN --}}
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.index') }}" 
                       @class(['nav-link flex-fill text-center custom-sub-item', 'active' => Request::is('admin*')])>
                        <i class="fa-solid fa-shield-halved me-1 d-none d-md-inline"></i> Admin
                    </a>
                @endif

                {{-- DASHBOARD --}}
                <a href="{{ route('links.dashboard') }}" 
                   @class(['nav-link flex-fill text-center custom-sub-item', 'active' => Request::is('dashboard*')])>
                    <i class="fa-solid fa-chart-pie me-1 d-none d-md-inline"></i> Dashboard
                </a>

                {{-- MY LINKS --}}
                <a href="{{ route('links.index') }}" 
                   @class(['nav-link flex-fill text-center custom-sub-item', 'active' => Request::is('links*')])>
                    <i class="fa-solid fa-link me-1 d-none d-md-inline"></i> Links
                </a>

                {{-- MY FILES --}}
                <a href="{{ route('files.index') }}" 
                   @class(['nav-link flex-fill text-center custom-sub-item', 'active' => Request::is('files*')])>
                    <i class="fa-solid fa-folder-open me-1 d-none d-md-inline"></i> Files
                </a>

                {{-- COINS / PREMIUM --}}
                <a href="{{ route('links.premium') }}" 
                   @class(['nav-link flex-fill text-center custom-sub-item premium-pill', 'active' => Request::is('premium*')])>
                    <div class="coin-badge px-2 py-1 rounded-pill d-inline-block">
                        <i class="fa-solid fa-coins text-warning me-1"></i> 
                        <span class="fw-bold">{{ number_format($coinBalance) }}</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
    @endif
@endauth