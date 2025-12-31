@extends('layouts.master')

@section('title', 'Admin Wallet Tools')

@section('content')
<div class="container p-2">
    <h4 class="mb-4 mt-2"><a href="{{ route('admin.index') }}"><i class="fa-solid fa-angle-left me-2"></i></a>Admin Wallet Adjustment (DEV / TEST)</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.wallet.adjust') }}" class="card p-3">
        @csrf

        <div class="mb-3">
            <label>User</label>
            <select name="user_id" class="form-control" required>
                <option value="">-- pilih user --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">
                        {{ $user->email }} ({{ $user->wallet->balance }} coin)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Action</label>
            <select name="type" class="form-control" required>
                <option value="add">Tambah Coin</option>
                <option value="subtract">Kurangi Coin</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Jumlah Coin</label>
            <input type="number" name="amount" class="form-control" min="1" required>
        </div>

        <div class="mb-3">
            <label>Keterangan (opsional)</label>
            <input type="text" name="description" class="form-control" placeholder="Testing bug / kompensasi">
        </div>

        <button class="btn btn-primary">
            Simpan
        </button>
    </form>

    <hr class="my-4">

    <h5 class="mb-3">Admin Balance Adjustment (DEV Stabilizer)</h5>

    <div class="card p-3 border-warning">
        <div class="mb-3">
            <strong>Saldo Admin:</strong>
            {{ $adminWallet->balance ?? 0 }} coin
        </div>

        <form method="POST" action="{{ route('admin.wallet.admin.adjust') }}">
            @csrf

            <div class="mb-3">
                <label>Action</label>
                <select name="type" class="form-control" required>
                    <option value="add">Tambah Coin Admin</option>
                    <option value="subtract">Kurangi Coin Admin</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Jumlah Coin</label>
                <input type="number" name="amount" class="form-control" min="1" required>
            </div>

            <div class="mb-3">
                <label>Keterangan</label>
                <input type="text" name="description" class="form-control" placeholder="DEV stabilizer">
            </div>

            <button class="btn btn-warning">
                Update Admin Balance
            </button>
        </form>
    </div>
</div>

@endsection
