@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card p-4 shadow text-center">
                <h5 class="mb-3">Reset Password</h5>
                <p class="mb-4">Buat password baru kamu!</p>

                @if (session('error'))
                    <div class="alert alert-danger">
                    {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                    {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('forgot.reset.post') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('reset_email') }}">
                    <div class="mb-3 text-start">
                        <label>Password Baru</label>
                        <input type="password" name="password" placeholder="Masukkan password baru"
                         class="form-control @error('password') is-invalid @enderror"
                         required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 text-start">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" placeholder="Konfirmasi password baru"
                         class="form-control @error('password_confirmation') is-invalid @enderror"
                         required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button class="btn btn-primary w-100">Reset Password</button>
                </form>

                <p class="mt-3">
                    Sudah ingat? <a href="{{ route('login') }}">Kembali ke Login</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
