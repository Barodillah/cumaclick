@extends('layouts.master')

@section('content')
<style>
    /* Konsistensi background dengan halaman Register */
    body {
        background: linear-gradient(135deg, #ffffffff 0%, #F9ECEF 100%);
        min-height: 100vh;
    }
    .card-login {
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
    .btn-google {
        background-color: #fff;
        color: #444;
        border: 1px solid #ddd;
    }

    .btn-google:hover {
        background-color: #f8f9fa;
        color: #000;
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
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-7">
            <div class="card card-login shadow-lg p-4">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary">Selamat Datang</h3>
                    <p class="text-muted small">Silakan masuk ke akun Anda</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show small" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="text" name="email" value="{{ old('email') }}"
                                placeholder="Masukkan email"
                                class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-bold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>

                            <input type="password"
                                id="password_single"
                                name="password"
                                placeholder="••••••••"
                                data-placeholder-show="Masukan Password"
                                class="form-control"
                                required>

                            <button type="button"
                                    class="input-group-text bg-white toggle-password"
                                    data-target="password_single"
                                    style="cursor:pointer">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <script>
                    document.querySelectorAll('.toggle-password').forEach(button => {
                        button.addEventListener('click', function () {
                            const targetId = this.dataset.target;
                            const input = document.getElementById(targetId);
                            const icon = this.querySelector('i');

                            const hiddenPlaceholder = '••••••••';
                            const showPlaceholder = input.dataset.placeholderShow || 'Password terlihat';

                            if (input.type === 'password') {
                                input.type = 'text';
                                input.placeholder = showPlaceholder;
                                icon.classList.replace('fa-eye', 'fa-eye-slash');
                            } else {
                                input.type = 'password';
                                input.placeholder = hiddenPlaceholder;
                                icon.classList.replace('fa-eye-slash', 'fa-eye');
                            }
                        });
                    });
                    </script>

                    <div class="text-end mb-4">
                        <a href="{{ route('forgot') }}" class="text-decoration-none small">Lupa Password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm mb-3">
                        Login
                    </button>

                    <div class="divider">atau</div>

                    <a href="{{ route('google.redirect') }}"
                    class="btn btn-google w-100 py-2 mb-3 shadow-sm d-flex align-items-center justify-content-center gap-2 text-decoration-none">

                        <img src="{{ asset('web_light_rd_na@3x.png') }}"
                            alt="Login with Google"
                            style="height:28px;">

                        <span class="fw-medium">Login dengan Google</span>
                    </a>
                </form>

                <div class="text-center mt-4">
                    <p class="small text-muted">Belum punya akun? <a href="{{ route('register') }}" class="fw-bold text-decoration-none">Daftar Sekarang</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection