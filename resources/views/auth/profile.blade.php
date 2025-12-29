@extends('layouts.master')
@section('title', 'Edit Profile')
@section('content')
<div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <h4 class="mb-4"><a href="{{ route('links.index') }}"><i class="fas fa-angle-left me-2"></i></a>Edit Profile</h4>

    {{-- Section Update Name --}}
    <div class="card mb-4">
        <div class="card-header">Update Profile</div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
            </div>
            <form action="{{ route('profile.updateName') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update</button>
            </form>
        </div>
    </div>

    {{-- Section Update Password --}}
    <div class="card">
        <div class="card-header">Update Password</div>
        <div class="card-body">
            <form action="{{ route('profile.updatePassword') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" 
                           class="form-control @error('password_confirmation') is-invalid @enderror" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required>
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Password</button>
            </form>
        </div>
    </div>

</div>
@endsection
