@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card p-4 shadow text-center">
                <h5 class="mb-3">Login</h5>

                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">
                    {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="mb-3 text-start">
                        <label>Email</label>
                        <input type="text" name="email" placeholder="Masukkan email"
                         class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3 text-start">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Masukkan password"
                         class="form-control" required>
                    </div>
                    <a href="{{ route('forgot') }}" class="d-block mb-3">Forgot Your Password?</a>
                    <button class="btn btn-primary w-100">Login</button>
                </form>

                <p class="mt-3">
                    Belum punya akun? <a href="{{ route('register') }}">Register</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
