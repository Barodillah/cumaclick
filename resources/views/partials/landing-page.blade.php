@push('styles')
<style>
    /* Global Section Styling */
    .section-title {
        position: relative;
        display: inline-block;
        margin-bottom: 3rem;
    }
    
    /* Modern Glass Card */
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 20px;
        transition: all 0.3s ease;
    }
    .glass-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        border-color: rgba(13, 110, 253, 0.3);
    }

    /* Icon Styling */
    .feature-icon-box {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        margin: 0 auto 20px;
    }

    /* Modern Accordion */
    .accordion-item {
        border: none;
        background: transparent;
        margin-bottom: 1rem;
    }
    .accordion-button {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(5px);
        border-radius: 15px !important;
        font-weight: 600;
        box-shadow: none !important;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .accordion-button:not(.collapsed) {
        background: rgba(180, 90, 113, 0.05);
        color: #B45A71;
        border-color: rgba(180, 90, 113, 0.2);
    }

    /* Contact Info Badge */
    .contact-link-item {
        padding: 15px;
        border-radius: 15px;
        background: #f8f9fa;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: flex;
        align-items: center;
    }
    .contact-link-item:hover {
        background: white;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        color: #B45A71;
    }
    
</style>
@endpush
<hr class="my-5 opacity-0">

<section id="fitur" class="py-5 position-relative overflow-hidden">
    <div class="secure-icons opacity-25 d-none d-md-block">
        <i class="fa-solid fa-link" style="top: 5%; right: 10%;"></i>
        <i class="fa-solid fa-chart-line" style="bottom: 10%; left: 5%;"></i>
    </div>

    <div class="container position-relative">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Fitur Utama <span class="text-primary text-gradient">cuma.click</span></h2>
            <p class="text-muted">Infrastruktur canggih untuk keamanan data Anda</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="glass-card h-100 p-4 text-center border-0 shadow-sm">
                    <div class="feature-icon-box bg-primary bg-opacity-10">
                        <i class="fa-solid fa-link fa-xl text-primary"></i>
                    </div>
                    <h5 class="fw-bold">Smart Shortlink</h5>
                    <p class="text-muted small">Custom alias sesuka hati, atur tanggal kadaluarsa, dan redirect instan tanpa jeda iklan.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="glass-card h-100 p-4 text-center border-0 shadow-sm">
                    <div class="feature-icon-box bg-success bg-opacity-10">
                        <i class="fa-solid fa-shield-halved fa-xl text-success"></i>
                    </div>
                    <h5 class="fw-bold">Keamanan PIN & OTP</h5>
                    <p class="text-muted small">Proteksi link rahasia Anda dengan verifikasi PIN atau OTP sebelum tujuan akhir dibuka.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="glass-card h-100 p-4 text-center border-0 shadow-sm">
                    <div class="feature-icon-box bg-warning bg-opacity-10">
                        <i class="fa-solid fa-chart-line fa-xl text-warning"></i>
                    </div>
                    <h5 class="fw-bold">Analitik Real-Time</h5>
                    <p class="text-muted small">Pantau lokasi pengunjung, perangkat yang digunakan, dan performa link secara transparan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-md-6 text-center position-relative">
                <div class="position-absolute top-50 start-50 translate-middle bg-primary opacity-10 rounded-circle" style="width: 300px; height: 300px; filter: blur(50px);"></div>
                <img src="/secure.svg" alt="Security" class="img-fluid position-relative shadow-sm rounded-4">
            </div>

            <div class="col-md-6">
                <h2 class="fw-bold mb-4">Kenapa Profesional Memilih <span class="text-primary">Kami?</span></h2>
                <div class="d-flex align-items-start mb-4">
                    <div class="me-3 p-2 bg-primary bg-opacity-10 rounded-3 text-primary">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Proteksi End-to-End</h6>
                        <p class="text-muted small">Link dan file Anda dienkripsi untuk memastikan hanya pihak berhak yang dapat mengakses.</p>
                    </div>
                </div>
                <div class="d-flex align-items-start mb-4">
                    <div class="me-3 p-2 bg-primary bg-opacity-10 rounded-3 text-primary">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Kontrol Penuh 100%</h6>
                        <p class="text-muted small">Ubah tujuan link atau hapus file kapan saja tanpa perlu mengganti URL yang sudah dibagikan.</p>
                    </div>
                </div>
                <div class="d-flex align-items-start">
                    <div class="me-3 p-2 bg-primary bg-opacity-10 rounded-3 text-primary">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Siap untuk Bisnis & UMKM</h6>
                        <p class="text-muted small">Dashboard intuitif yang dirancang untuk skala personal hingga campaign digital marketing besar.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="faq" class="py-5" style="background: #fbfcfe;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Paling Sering Ditanyakan</h2>
            <p class="text-muted">Masalah umum yang diselesaikan oleh cuma.click</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Bagaimana cara kerja proteksi OTP pada link?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show">
                            <div class="accordion-body text-muted small">
                                Saat diaktifkan, pengunjung wajib memasukkan kode unik yang dikirim via kontak yang ditentukan sebelum sistem melakukan redirect ke URL tujuan.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Apakah statistik klik bersifat publik?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse">
                            <div class="accordion-body text-muted small">
                                Tidak. Statistik klik sepenuhnya bersifat pribadi dan hanya bisa diakses oleh pembuat link melalui dashboard akun masing-masing.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Ada batasan untuk jumlah file yang diunggah?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse">
                            <div class="accordion-body text-muted small">
                                Batasan tergantung pada Tier akun Anda. Guest dibatasi 5MB, sementara Diamond bisa hingga 100MB per file dengan jumlah penyimpanan yang luas.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Apakah link bisa diberi masa berlaku (expired)?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse">
                            <div class="accordion-body text-muted small">
                                Ya. Anda dapat mengatur tanggal atau jumlah klik maksimum. Setelah batas tercapai, link otomatis tidak dapat diakses.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faq5">
                                Apakah sistem mendeteksi klik bot atau spam?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse">
                            <div class="accordion-body text-muted small">
                                Sistem secara otomatis memfilter klik mencurigakan menggunakan analisis user agent, IP, dan pola perilaku sehingga data statistik tetap akurat.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<section id="kontak" class="py-5 bg-white">
    <div class="container">
        <div class="main-tool-card p-4 p-md-5">
            <div class="row g-5 align-items-center">
                <div class="col-md-5">
                    <h2 class="fw-bold mb-3">Mari <span class="text-primary">Bekerja Sama</span></h2>
                    <p class="text-muted mb-5">Punya pertanyaan teknis atau ingin integrasi API khusus? Tim kami siap merespon pesan Anda segera.</p>
                    
                    <div class="contact-links">
                        <a href="mailto:gmail@cuma.click" class="contact-link-item mb-3">
                            <i class="fa-solid fa-envelope text-primary me-3 fs-4"></i>
                            <div>
                                <small class="text-muted d-block">Kirim Email</small>
                                <span class="fw-bold">gmail@cuma.click</span>
                            </div>
                        </a>
                        <a href="https://wa.me/6281999934451" class="contact-link-item">
                            <i class="fa-brands fa-whatsapp text-success me-3 fs-4"></i>
                            <div>
                                <small class="text-muted d-block">Fast Response</small>
                                <span class="fw-bold">+62 819-9993-4451</span>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="bg-light p-4 rounded-4">
                        <form method="POST" action="{{ route('contact.send') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Nama</label>
                                    <input type="text" name="nama" class="form-control border-0 py-3 shadow-sm rounded-3" placeholder="Nama Anda">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Email</label>
                                    <input type="email" name="email" class="form-control border-0 py-3 shadow-sm rounded-3" placeholder="email@anda.com">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Pesan</label>
                                    <textarea name="pesan" rows="4" class="form-control border-0 shadow-sm rounded-3" placeholder="Tuliskan pesan Anda..."></textarea>
                                </div>
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary px-5 py-3 fw-bold shadow">
                                        Kirim Pesan <i class="fa-solid fa-paper-plane ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>