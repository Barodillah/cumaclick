@extends('layouts.master')

@push('styles')
<style>
    :root {
        --primary-glow: rgba(180, 90, 113, 0.15);
        --accent-color: #B45A71;
    }

    /* Hero Floating Icons */
    .hero-wrapper { position: relative; padding: 80px 0 60px; }
    .secure-icons i {
        position: absolute;
        font-size: 2rem;
        opacity: 0.2;
        color: var(--accent-color);
        z-index: 0;
        filter: blur(0.5px);
    }

    /* Main Card Styling */
    .main-tool-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    /* Custom Nav Pills */
    .nav-custom {
        background: #f1f3f5;
        padding: 6px;
        border-radius: 16px;
        display: inline-flex;
        width: 100%;
    }
    .nav-custom .nav-link {
        border-radius: 12px;
        color: #6c757d;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }
    .nav-custom .nav-link.active {
        background: white !important;
        color: #B45A71 !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    /* Drop Area Styling */
    #dropArea {
        border: 2px dashed #dee2e6;
        background: #f8f9fa;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    #dropArea:hover, #dropArea.dragover {
        border-color: #B45A71;
        background: rgba(180, 90, 113, 0.02);
        transform: scale(1.01);
    }

    /* Input Styling */
    .form-control-lg-custom {
        padding: 1.2rem;
        border-radius: 15px;
        border: 1px solid #e9ecef;
        background: #fdfdfd;
        transition: all 0.2s;
    }
    .form-control-lg-custom:focus {
        box-shadow: 0 0 0 4px var(--primary-glow);
        border-color: #B45A71;
    }

    /* Tier Badge */
    .tier-badge {
        font-size: 0.75rem;
        padding: 4px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
    }
    .paste-wrapper {
        position: relative;
    }

    .paste-input {
        transition: all .25s ease;
    }

    /* Tombol menyatu */
    .paste-btn {
        border: none;
        background: #f8f9fa;
        transition: all .25s ease;
    }

    .paste-btn:hover {
        background: #e9ecef;
    }

    /* Success state */
    .paste-success {
        border-color: #13c58aff !important;
        box-shadow: 0 0 0 .2rem rgba(40, 167, 69, .25);
    }

    /* Icon success */
    .paste-btn.success {
        color: #13c58aff;
        transform: scale(1.15);
    }

</style>
@endpush

@section('content')
<div class="container">
    
    {{-- Hero Section --}}
    <section class="hero-wrapper text-center">
        <div class="secure-icons d-none d-md-block">
            <i class="fa-solid fa-shield-halved floating-icon" style="top: 10%; left: 10%;"></i>
            <i class="fa-solid fa-lock floating-icon" style="top: 25%; right: 15%;"></i>
            <i class="fa-solid fa-bolt floating-icon" style="bottom: 20%; left: 20%;"></i>
            <i class="fa-solid fa-fingerprint floating-icon" style="bottom: 30%; right: 10%;"></i>
        </div>

        <div class="position-relative" style="z-index: 1;">
            <h1 class="display-4 fw-800 mb-3" style="letter-spacing: -1px;">Sederhana. <span class="text-primary text-gradient">Aman.</span> Terukur.</h1>
            <p class="mx-auto text-muted lead" style="max-width: 650px;">
                Platform manajemen link profesional dengan proteksi PIN, hosting file terenkripsi, dan analitik performa tingkat lanjut.
            </p>
        </div>
    </section>

    {{-- Main Interface --}}
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            
            @if(session('msg'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('msg') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="main-tool-card p-3 p-md-4 mb-5">
                {{-- Dynamic Tabs --}}
                <ul class="nav nav-custom mb-4" id="toolTabs" role="tablist">
                    <li class="nav-item flex-fill text-center">
                        <button class="nav-link w-100 @if(!$errors->has('short_code')) active @endif" data-bs-toggle="tab" data-bs-target="#url">
                            <i class="fa-solid fa-link me-2"></i>URL
                        </button>
                    </li>
                    <li class="nav-item flex-fill text-center">
                        <button class="nav-link w-100" data-bs-toggle="tab" data-bs-target="#file">
                            <i class="fa-solid fa-cloud-arrow-up me-2"></i>File
                        </button>
                    </li>
                    @auth
                    <li class="nav-item flex-fill text-center">
                        <button class="nav-link w-100 @if($errors->has('short_code')) active @endif" data-bs-toggle="tab" data-bs-target="#claim">
                            <i class="fa-solid fa-key me-2"></i>Claim
                        </button>
                    </li>
                    @endauth
                </ul>

                <div class="tab-content">
                    {{-- URL Tab --}}
                    <div class="tab-pane fade @if(!$errors->has('short_code')) show active @endif" id="url">
                        <form method="POST" action="{{ route('shorten') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-9">
                                    <div class="input-group paste-wrapper">
                                        <input type="text" name="destination_url" id="destination_url"
                                            class="form-control form-control-lg-custom paste-input
                                            @error('destination_url') is-invalid @enderror" 
                                            placeholder="Tempel link panjang Anda di sini..."
                                            value="{{ old('destination_url') }}" required>

                                        <button class="btn paste-btn" type="button" id="btn-paste" title="Tempel dari clipboard">
                                            <i class="fas fa-clipboard"></i>
                                        </button>

                                        @error('destination_url')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-primary btn-lg w-100 py-3 shadow-sm fw-bold">
                                        Perpendek <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                    document.getElementById('btn-paste').addEventListener('click', async function () {
                        const input = document.getElementById('destination_url');
                        const icon  = this.querySelector('i');

                        try {
                            const text = await navigator.clipboard.readText();

                            // Clear dulu jika ada isi
                            if (input.value.trim() !== '') {
                                input.value = '';
                            }

                            // Smooth insert
                            setTimeout(() => {
                                input.value = text;
                                input.focus();

                                // Success effect
                                input.classList.add('paste-success');
                                this.classList.add('success');

                                // Ganti icon
                                icon.classList.remove('fa-clipboard');
                                icon.classList.add('fa-check');

                                // Balikin normal
                                setTimeout(() => {
                                    input.classList.remove('paste-success');
                                    this.classList.remove('success');
                                    icon.classList.remove('fa-check');
                                    icon.classList.add('fa-clipboard');
                                }, 1200);
                            }, 100);

                        } catch (err) {
                            alert('Gagal mengakses clipboard. Pastikan HTTPS & izin browser aktif.');
                            console.error(err);
                        }
                    });
                    </script>
                    {{-- File Tab --}}
                    <div class="tab-pane fade" id="file">
                        <form method="POST" action="{{ route('upload') }}" enctype="multipart/form-data" id="uploadForm">
                            @csrf
                            <div id="dropArea" class="rounded-4 p-5 text-center position-relative">
                                <input type="file" name="file" id="fileInput" class="d-none">
                                <div class="upload-icon-wrapper mb-3">
                                    <i class="fa-solid fa-folder-plus fa-3x text-primary opacity-50"></i>
                                </div>
                                <h5 class="fw-bold">Tarik file ke sini</h5>
                                <p class="text-muted small">Atau klik untuk menjelajah file dari perangkat Anda</p>
                                
                                <div class="mt-3">
                                    <span class="badge bg-light text-dark border tier-badge">
                                        Limit: 
                                        @guest 5 MB @else
                                            {{ auth()->user()->tier === 'diamond' ? '100' : (auth()->user()->tier === 'premium' ? '30' : '10') }} MB
                                        @endguest
                                    </span>
                                </div>
                            </div>
                            
                            <div id="preview" class="mt-3"></div>

                            <button class="btn btn-primary btn-lg w-100 mt-4 py-3 fw-bold shadow">
                                <i class="fa-solid fa-cloud-arrow-up me-2"></i>Upload & Amankan
                            </button>
                        </form>
                    </div>

                    {{-- Claim Tab (Auth Only) --}}
                    @auth
                    <div class="tab-pane fade {{ $errors->has('short_code') ? 'show active' : '' }}" id="claim">
                        <form method="POST" action="{{ route('claim') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-9">
                                    <input type="text" name="short_code" 
                                        class="form-control form-control-lg-custom @error('short_code') is-invalid @enderror" 
                                        placeholder="Masukkan kode unik (contoh: abC123)"
                                        value="{{ old('short_code') }}">
                                        @error('short_code')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-primary btn-lg w-100 py-3 fw-bold">
                                        Klaim Link
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endauth
                </div>
            </div>

            {{-- Informative Section (Quick Stats/Features) --}}
            <div class="row g-4 text-center mb-5">
                <div class="col-4">
                    <h4 class="fw-bold mb-0">10k+</h4>
                    <small class="text-muted">Link Aman</small>
                </div>
                <div class="col-4 border-start border-end">
                    <h4 class="fw-bold mb-0">99.9%</h4>
                    <small class="text-muted">Uptime</small>
                </div>
                <div class="col-4">
                    <h4 class="fw-bold mb-0">End-to-End</h4>
                    <small class="text-muted">Encrypted</small>
                </div>
            </div>

            @guest
            {{-- CTA Register --}}
            {{-- HAPUS animate__animated animate__fadeInUp, TAMBAHKAN class cta-banner-container --}}
            <div class="cta-banner-container bg-primary rounded-4 p-4 text-white d-flex flex-column flex-md-row align-items-center justify-content-between shadow-lg mb-5" style="opacity: 0; visibility: hidden;"> {{-- Set awal opacity 0 agar tidak flicker --}}
                <div class="mb-3 mb-md-0 text-center text-md-start cta-text-group">
                    <h5 class="fw-bold mb-1">Dapatkan Kontrol Penuh!</h5>
                    <p class="mb-0 opacity-75 small">Daftar sekarang untuk mengelola link, kustomisasi alias, dan statistik detail.</p>
                </div>
                <a href="{{ route('register') }}" class="btn btn-light fw-bold px-4 py-2 rounded-pill cta-btn-pulse">
                    Gabung Gratis <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
            @endguest
        </div>
    </div>

    @include('partials.landing-page')
</div>

@include('partials.landing-footer')

{{-- Konfigurasi JS --}}
<script>
    window.uploadConfig = {
        isAuth: @json(auth()->check()),
        tier: @json(auth()->check() ? auth()->user()->tier : 'guest'),
        limits: { guest: 5, basic: 10, premium: 30, diamond: 100 }
    };
</script>

{{-- GSAP Animations --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Floating Icons Animation
        gsap.to(".floating-icon", {
            y: "random(-20, 20)",
            x: "random(-20, 20)",
            rotation: "random(-15, 15)",
            duration: "random(2, 4)",
            repeat: -1,
            yoyo: true,
            ease: "sine.inOut"
        });

        // Drop Area Logic
        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('fileInput');
        const preview = document.getElementById('preview');

        dropArea.addEventListener('click', () => fileInput.click());

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, e => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        dropArea.addEventListener('dragover', () => dropArea.classList.add('dragover'));
        dropArea.addEventListener('dragleave', () => dropArea.classList.remove('dragover'));
        dropArea.addEventListener('drop', (e) => {
            dropArea.classList.remove('dragover');
            fileInput.files = e.dataTransfer.files;
            fileInput.dispatchEvent(new Event('change'));
        });

        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            if (!file) return;

            const maxMB = window.uploadConfig.limits[window.uploadConfig.tier] || 5;
            if (file.size > maxMB * 1024 * 1024) {
                fileInput.value = '';
                preview.innerHTML = '';
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: `Maksimal upload untuk tier ${window.uploadConfig.tier.toUpperCase()} adalah ${maxMB}MB.`
                });
                return;
            }

            preview.innerHTML = `
                <div class="alert alert-primary d-flex align-items-center animate__animated animate__fadeIn">
                    <i class="fa-solid fa-file-circle-check fa-lg me-3"></i>
                    <div>
                        <div class="fw-bold">${file.name}</div>
                        <small>${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                    </div>
                </div>
            `;
        });
    });
</script>
@include('partials.gsap')
@endsection