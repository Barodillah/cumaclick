<footer class="bg-dark text-light pt-5 pb-4">
    <div class="container">
        <div class="row gy-4">

            <!-- BRAND -->
            <div class="col-md-4">
                <img src="/favicon.png" alt="cuma.click logo"
                    width="32" height="32"
                    class="mb-2">

                <h5 class="fw-bold mb-1">cuma.click</h5>
                <p class="text-light small mb-0">
                    Platform shortlink aman dan profesional untuk kebutuhan personal,
                    UMKM, dan digital marketing.
                </p>
            </div>

            <!-- MENU -->
            <div class="col-md-4">
                <h6 class="fw-semibold mb-3">Menu</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Beranda</a></li>
                    <li class="mb-2"><a href="#fitur" class="text-light text-decoration-none">Fitur</a></li>
                    <li class="mb-2"><a href="#faq" class="text-light text-decoration-none">FAQ</a></li>
                    <li><a href="#kontak" class="text-light text-decoration-none">Kontak</a></li>
                </ul>
            </div>

            <!-- LEGAL & SOSIAL -->
            <div class="col-md-4">
                <h6 class="fw-semibold mb-3">Legal & Sosial</h6>

                <div class="mb-3">
                    <a href="{{ route('terms') }}" class="text-light text-decoration-none small d-block mb-1">
                        Syarat & Ketentuan
                    </a>
                    <a href="{{ route('privacy') }}" class="text-light text-decoration-none small">
                        Kebijakan Privasi
                    </a>
                </div>

                <div>
                    <a href="#" class="text-light me-3 fs-5">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="#" class="text-light me-3 fs-5">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                    <a href="#" class="text-light fs-5">
                        <i class="fa-brands fa-github"></i>
                    </a>
                </div>
            </div>

        </div>

        <hr class="border-secondary my-4">

        <div class="text-center small text-light">
            © <?= date('Y') ?> cuma.click. All rights reserved.
        </div>
    </div>
</footer>
