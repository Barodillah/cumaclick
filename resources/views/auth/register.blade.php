@extends('layouts.master')

@section('content')
<style>
    /* Custom Styling untuk mempercantik */
    body {
        background: linear-gradient(135deg, #ffffffff 0%, #F9ECEF 100%);
        min-height: 100vh;
    }
    .card-register {
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }
    .input-group-text {
        background-color: transparent;
        border-right: none;
        color: #6c757d;
    }
    .form-control {
        border-left: none;
    }
    .form-control:focus {
        box-shadow: none;
        border-color: #dee2e6;
    }
    
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        color: #888;
        margin: 20px 0;
    }
    .divider::before, .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #dee2e6;
    }
    .divider:not(:empty)::before { margin-right: .5em; }
    .divider:not(:empty)::after { margin-left: .5em; }
    .btn-google {
        background-color: #fff;
        color: #444;
        border: 1px solid #ddd;
    }

    .btn-google:hover {
        background-color: #f8f9fa;
        color: #000;
    }

</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            <div class="card card-register shadow-lg p-4">
                <div class="text-center mb-3">
                    <h3 class="fw-bold text-primary">Buat Akun</h3>
                    <p class="text-muted">Mulai perjalananmu bersama kami hari ini.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show small" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                @endif

                <a href="{{ route('google.redirect') }}"
                class="btn btn-google w-100 py-2 mb-2 shadow-sm d-flex align-items-center justify-content-center gap-2 text-decoration-none">

                    <img src="{{ asset('web_light_rd_na@3x.png') }}"
                        alt="Login with Google"
                        style="height:28px;">

                    <span class="fw-medium">Daftar dengan Google</span>
                </a>

                <div class="divider">atau</div>

                <form method="POST" action="{{ route('register.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="name" value="{{ old('name') }}"
                                placeholder="Contoh: Budi Santoso"
                                class="form-control @error('name') is-invalid @enderror" required>
                        </div>
                        @error('name') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="nama@email.com"
                                class="form-control @error('email') is-invalid @enderror" required>
                        </div>
                        @error('email') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>

                            <input type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            data-placeholder-show="Masukan Password"
                            class="form-control @error('password') is-invalid @enderror"
                            required>

                            <button type="button"
                                    class="input-group-text bg-white toggle-password"
                                    data-target="password"
                                    style="cursor:pointer">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Konfirmasi</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-check-double"></i></span>

                            <input type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="••••••••"
                                data-placeholder-show="Konfirmasi Password"
                                class="form-control"
                                required>

                            <button type="button"
                                    class="input-group-text bg-white toggle-password"
                                    data-target="password_confirmation"
                                    style="cursor:pointer">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <script>
                    document.querySelectorAll('.toggle-password').forEach(button => {
                        button.addEventListener('click', function () {
                            const targetId = this.getAttribute('data-target');
                            const input = document.getElementById(targetId);
                            const icon = this.querySelector('i');

                            const hiddenPlaceholder = '••••••••';
                            const showPlaceholder = input.dataset.placeholderShow || 'Password terlihat';

                            if (input.type === 'password') {
                                input.type = 'text';
                                input.placeholder = showPlaceholder;

                                icon.classList.remove('fa-eye');
                                icon.classList.add('fa-eye-slash');
                            } else {
                                input.type = 'password';
                                input.placeholder = hiddenPlaceholder;

                                icon.classList.remove('fa-eye-slash');
                                icon.classList.add('fa-eye');
                            }
                        });
                    });
                    </script>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input @error('terms') is-invalid @enderror" 
                                   type="checkbox" name="terms" id="terms" {{ old('terms') ? 'checked' : '' }}>
                            <label class="form-check-label small" for="terms">
                                Saya menyetujui <a href="{{ route('terms') }}" class="text-decoration-none">Syarat & Ketentuan</a>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mb-3 shadow-sm">
                        Daftar Sekarang
                    </button>
                </form>

                <div class="text-center mt-3">
                    <p class="small text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="fw-bold text-decoration-none">Login di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection