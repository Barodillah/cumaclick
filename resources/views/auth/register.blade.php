@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-8">
            <div class="card p-4 shadow text-center">
                <h5 class="mb-3">Register</h5>

                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register.post') }}">
                    @csrf

                    <!-- Nama -->
                    <div class="mb-3 text-start">
                        <label>Nama</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            placeholder="Masukkan nama kamu"
                            class="form-control @error('name') is-invalid @enderror"
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3 text-start">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            placeholder="Masukkan email kamu"
                            class="form-control @error('email') is-invalid @enderror"
                            required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3 text-start">
                        <label>Password</label>
                        <input type="password" name="password"
                            placeholder="Masukkan password kamu"
                            class="form-control @error('password') is-invalid @enderror"
                            required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-3 text-start">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                            placeholder="Masukkan konfirmasi password kamu"
                            class="form-control"
                            required>
                    </div>

                    <!-- ✅ CHECKBOX SYARAT & KETENTUAN -->
                    <div class="mb-3 text-start">
                        <div class="form-check">
                            <input class="form-check-input @error('terms') is-invalid @enderror"
                                type="checkbox"
                                name="terms"
                                id="terms"
                                {{ old('terms') ? 'checked' : '' }}>

                            <label class="form-check-label" for="terms">
                                Saya menyetujui
                                <a href="{{ route('terms') }}" target="_blank">
                                    Syarat & Ketentuan
                                </a>
                            </label>

                            @error('terms')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <button class="btn btn-primary w-100">Register</button>
                </form>

                <p class="mt-3">
                    Sudah punya akun? <a href="{{ route('login') }}">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
