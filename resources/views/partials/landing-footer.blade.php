<footer class="footer-modern bg-dark text-light pt-5 pb-4">
    <div class="container">
        <div class="row gy-5">

            <div class="col-lg-4 col-md-6">
                <div class="footer-brand-wrapper mb-3">
                    <img src="/favicon.png" alt="cuma.click logo" width="40" height="40" class="mb-3 filter-white">
                    <h4 class="fw-bold text-white mb-2">cuma<span class="text-primary">.</span>click</h4>
                    <p class="text-secondary small lh-lg">
                        Solusi manajemen tautan yang aman, cepat, dan profesional. Dirancang untuk memperkuat branding digital UMKM dan marketer di seluruh Indonesia.
                    </p>
                </div>
                <div class="d-flex gap-3">
                    <a href="https://www.instagram.com/cumadotclick" class="social-icon" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://x.com/cumaclick" class="social-icon" aria-label="X"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#" class="social-icon" aria-label="Github"><i class="fa-brands fa-github"></i></a>
                    <a href="https://www.linkedin.com/in/barod-abdillah-284509169/" class="social-icon" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <h6 class="text-white fw-bold mb-4 text-uppercase tracking-wider">Layanan</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="{{ route('home') }}#">Shortener</a></li>
                    <li><a href="{{ route('home') }}#">Custom Link</a></li>
                    <li><a href="{{ route('home') }}#fitur">Analitik Real-time</a></li>
                    <li><a href="{{ route('home') }}#kontak">Developer API</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-6">
                <h6 class="text-white fw-bold mb-4 text-uppercase tracking-wider">Dukungan</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="{{ route('home') }}#faq">Pusat Bantuan</a></li>
                    <li><a href="{{ route('terms') }}">Syarat & Ketentuan</a></li>
                    <li><a href="{{ route('privacy') }}">Kebijakan Privasi</a></li>
                    <li><a href="{{ route('home') }}#kontak">Hubungi Kami</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6">
                <h6 class="text-white fw-bold mb-4 text-uppercase tracking-wider">Langganan Update</h6>
                <p class="text-secondary small mb-3">Dapatkan tips digital marketing dan fitur terbaru.</p>
                <form class="newsletter-form">
                    <div class="input-group mb-2">
                        <input type="email" class="form-control bg-transparent text-white border-secondary" placeholder="Email Anda" aria-label="Email">
                        <button class="btn btn-primary" type="button">Gabung</button>
                    </div>
                    <small class="text-light" style="font-size: 0.7rem;">*Tenang, kami juga benci spam.</small>
                </form>
            </div>

        </div>

        <hr class="border-secondary opacity-25 my-5">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <span class="small text-secondary">© <?= date('Y') ?> <span class="text-primary">cuma.click</span> Dibuat dengan <i class="fa-solid fa-heart text-danger mx-1"></i> di Indonesia.</span>
            </div>
            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                <div class="status-indicator d-inline-flex align-items-center bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill small">
                    <span class="dot me-2"></span> Semua Sistem Berjalan Normal
                </div>
            </div>
        </div>
    </div>
</footer>
@push('styles')
<style>
    .footer-modern {
        background-color: #0f1113 !important; /* Warna dark yang lebih rich */
    }

    .tracking-wider { letter-spacing: 0.1rem; font-size: 0.85rem; }

    /* Hover effect pada link */
    .footer-links li { margin-bottom: 0.75rem; }
    .footer-links a {
        color: #adb5bd;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }
    .footer-links a:hover {
        color: #B45A71; /* Warna primary */
        padding-left: 8px;
    }

    /* Social Media Icons */
    .social-icon {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.05);
        color: #fff;
        border-radius: 10px;
        transition: 0.3s;
        text-decoration: none;
    }
    .social-icon:hover {
        background: #B45A71;
        color: #fff;
        transform: translateY(-3px);
    }

    /* Newsletter Input */
    .newsletter-form .form-control:focus {
        box-shadow: none;
        border-color: #B45A71;
    }

    /* Status Indicator Dot */
    .dot {
        height: 8px;
        width: 8px;
        background-color: #198754;
        border-radius: 50%;
        display: inline-block;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(25, 135, 84, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
    }
</style>
@endpush