@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card p-4 shadow text-center">
                <h5 class="mb-3">Register</h5>

                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register.post') }}">
                    @csrf
                    <div class="mb-3 text-start">
                        <label>Nama</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama kamu" class="form-control" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 text-start">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email kamu" class="form-control" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 text-start">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Masukkan password kamu" class="form-control" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 text-start">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" placeholder="Masukkan konfirmasi password kamu" class="form-control" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button class="btn btn-success w-100">Register</button>
                </form>

                <p class="mt-3">
                    Sudah punya akun? <a href="{{ route('login') }}">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
