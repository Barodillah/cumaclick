@extends('layouts.master')
@section('title', 'Edit Profile')

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1"><a href="{{ route('links.index') }}">
            <i class="fas fa-angle-left text-primary me-1"></i></a> Setelan Profil</h4>
            <p class="text-muted small">Kelola informasi akun dan keamanan kata sandi Anda.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px; font-size: 2rem; font-weight: 600;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small mb-0">{{ $user->email }}</p>
                    <hr class="my-4 opacity-50">
                    <div class="text-start">
                        <p class="small text-muted mb-2"><i class="fas fa-info-circle me-2"></i> Terdaftar sejak:</p>
                        <p class="small fw-semibold">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h6 class="fw-bold mb-0">Informasi Dasar</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.updateName') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Alamat Email</label>
                                <input type="email" class="form-control bg-light border-0 shadow-none" value="{{ $user->email }}" disabled>
                                <div class="form-text text-muted" style="font-size: 0.75rem;">Email tidak dapat diubah untuk saat ini.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label small fw-bold text-muted">Nama Lengkap</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror shadow-sm" 
                                       id="name" name="name" 
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm">
                                <i class="fas fa-check me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h6 class="fw-bold mb-0 text-danger">Keamanan Akun</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.updatePassword') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label small fw-bold text-muted">Kata Sandi Baru</label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror shadow-sm" 
                                       id="password" name="password" 
                                       placeholder="Minimal 8 karakter" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label small fw-bold text-muted">Konfirmasi Sandi</label>
                                <input type="password" 
                                       class="form-control @error('password_confirmation') is-invalid @enderror shadow-sm" 
                                       id="password_confirmation" name="password_confirmation" 
                                       placeholder="Ulangi kata sandi baru" required>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-outline-danger px-4 rounded-pill shadow-sm">
                                <i class="fas fa-key me-1"></i> Perbarui Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection