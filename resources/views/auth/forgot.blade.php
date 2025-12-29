@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card p-4 shadow text-center">
                <h5 class="mb-3">Lupa Password</h5>
                <p class="mb-4">Masukkan email Anda untuk menerima kode OTP</p>

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

                <form method="POST" action="{{ route('forgot.send') }}">
                    @csrf
                    <div class="mb-3 text-start">
                        <label>Email</label>
                        <input type="text" name="email" class="form-control"
                        placeholder="Masukkan email kamu"
                        value="{{ old('email') }}" required>
                    </div>
                    <button class="btn btn-primary w-100">Kirim OTP</button>
                </form>

                <p class="mt-3">
                    Sudah ingat? <a href="{{ route('login') }}">Kembali ke Login</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
