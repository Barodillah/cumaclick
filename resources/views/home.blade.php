@extends('layouts.master')

@section('content')
<div class="container">

    {{-- Hero --}}
    <section class="hero text-center py-5">
        <h1 class="fw-bold">Lebih dari Sekedar Link.</h1>
        <p class="mt-3">
            Platform pemendek link yang kuat dengan analitik, proteksi PIN, hosting file, dan banyak lagi.
        </p>
    </section>

    {{-- Alerts --}}
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

    {{-- Card --}}
    <div class="rounded-lg border p-2 bg-white/10 backdrop-blur-lg border-white/20 shadow-2xl mb-4">

        {{-- Tabs --}}
        <ul class="nav nav-pills justify-content-center bg-black/20 rounded-md p-1">
            <li class="nav-item flex-fill">
                <button class="nav-link @if(!$errors->has('short_code')) active @endif w-100" data-bs-toggle="tab" data-bs-target="#url">
                    <i class="fa-solid fa-link me-2"></i> Perpendek URL
                </button>
            </li>
            <li class="nav-item flex-fill">
                <button class="nav-link w-100" data-bs-toggle="tab" data-bs-target="#file">
                    <i class="fa-solid fa-folder-open me-2"></i> Unggah File
                </button>
            </li>
            @auth
            <li class="nav-item flex-fill">
                <button class="nav-link @if($errors->has('short_code')) active @endif w-100" data-bs-toggle="tab" data-bs-target="#claim">
                    <i class="fa-solid fa-key me-2"></i> Claim Shortlink
                </button>
            </li>
            @endauth
        </ul>

        {{-- Content --}}
        <div class="tab-content p-2 mt-1">

            {{-- URL --}}
            <div class="tab-pane fade @if(!$errors->has('short_code')) show active @endif" id="url">
                <form method="POST" action="{{ route('shorten') }}">
                    @csrf
                    <div class="row g-2">
                        <div class="col-lg-9">
                            <input 
                                type="text" 
                                name="destination_url"
                                class="form-control @error('destination_url') is-invalid @enderror"
                                placeholder="https://example.com/long-url"
                                value="{{ old('destination_url') }}"
                            >

                            @error('destination_url')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-lg-3">
                            <button class="btn btn-primary w-100">
                                <i class="fa-solid fa-bolt me-1"></i> Perpendek
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Upload --}}
            <div class="tab-pane fade" id="file">
                <form method="POST" action="{{ route('upload') }}" enctype="multipart/form-data">
                    @csrf

                    <div id="dropArea" class="border border-dashed rounded-3 p-4 text-center">
                        <i class="fa-solid fa-cloud-arrow-up fa-3x mb-2"></i>
                        <p>Seret & lepas file, atau klik untuk memilih</p>
                        <input type="file" name="file" id="fileInput" class="d-none">
                    </div>
                    <small class="text-muted d-block mt-2 text-center">
                        Maksimal ukuran file:
                        @guest 5 MB atau login dulu @endguest
                        @auth
                            @if(auth()->user()->tier === 'basic') 10 MB
                            @elseif(auth()->user()->tier === 'premium') 30 MB
                            @elseif(auth()->user()->tier === 'diamond') 100 MB
                            @endif
                        @endauth
                    </small>


                    <div id="preview" class="mt-3 text-center"></div>

                    <div class="text-center mt-2">
                        <button class="btn btn-primary">
                            <i class="fa-solid fa-cloud-arrow-up me-2"></i> Upload & Shorten
                        </button>
                    </div>
                </form>
            </div>

            {{-- Claim --}}
            @auth
            <div class="tab-pane fade {{ $errors->has('short_code') ? 'show active' : '' }}" id="claim">
                <form method="POST" action="{{ route('claim') }}">
                    @csrf
                    <div class="row g-2">
                        <div class="col-lg-9">
                            <input 
                                type="text" 
                                name="short_code"
                                class="form-control @error('short_code') is-invalid @enderror"
                                placeholder="Enter short code to claim"
                                value="{{ old('short_code') }}">                           
                                @error('short_code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                        </div>

                        <div class="col-lg-3">
                            <button class="btn btn-primary w-100">
                                <i class="fa-solid fa-key me-1"></i> Claim
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            @endauth
        </div>
    </div>

    @auth
    {{-- optional: CTA lain untuk user login --}}
    @else
        <div class="text-center my-4">
            <p class="text-muted mb-0">
                Daftar gratis dan nikmati fitur lengkap cuma.click
            </p>
            <a href="{{ route('register') }}"
            class="btn btn-primary btn-sm px-5 shadow-sm mt-2">
                <i class="fa-solid fa-person-running me-2"></i>
                Gabung Sekarang
            </a>
        </div>
    @endauth


@include('partials.landing-page')
</div>
@include('partials.landing-footer')
<script>
    window.uploadConfig = {
        isAuth: @json(auth()->check()),
        tier: @json(auth()->check() ? auth()->user()->tier : 'guest'),
        limits: {
            guest: 5,     // MB
            basic: 10,    // MB
            premium: 30,  // MB
            diamond: 100  // MB
        }
    };
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const fileInput = document.getElementById('fileInput');
    const form = fileInput.closest('form');
    const preview = document.getElementById('preview');

    function getMaxSizeMB() {
        if (!window.uploadConfig.isAuth) {
            return window.uploadConfig.limits.guest;
        }
        return window.uploadConfig.limits[window.uploadConfig.tier] ?? 5;
    }

    function showAlert(maxMB) {
        Swal.fire({
            icon: 'warning',
            title: 'Ukuran file terlalu besar',
            html: `
                Maksimal upload <b>${maxMB} MB</b><br>
                Anda adalah <b>${window.uploadConfig.tier.toUpperCase()}</b>
            `,
            confirmButtonText: 'Mengerti'
        });
    }

    fileInput.addEventListener('change', () => {
        preview.innerHTML = '';

        const file = fileInput.files[0];
        if (!file) return;

        const maxMB = getMaxSizeMB();
        const maxBytes = maxMB * 1024 * 1024;

        if (file.size > maxBytes) {
            fileInput.value = '';
            showAlert(maxMB);
            return;
        }

        // preview info
        preview.innerHTML = `
            <div class="alert alert-success">
                <i class="fa-solid fa-file me-1"></i>
                ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)
            </div>
        `;
    });

    // blok submit kalau maksa
    form.addEventListener('submit', (e) => {
        const file = fileInput.files[0];
        if (!file) return;

        const maxMB = getMaxSizeMB();
        if (file.size > maxMB * 1024 * 1024) {
            e.preventDefault();
            showAlert(maxMB);
        }
    });
});
</script>

@endsection
