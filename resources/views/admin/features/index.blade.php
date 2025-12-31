@extends('layouts.master')

@section('title', 'Feature Management')

@section('content')
<div class="container py-4">

    <h3 class="mb-4"><a href="{{ route('admin.index') }}"><i class="fa-solid fa-angle-left me-2"></i></a> Admin Feature Management</h3>

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- ================= ADD FEATURE ================= --}}
    <div class="card mb-4 p-3">
        <h5 class="mb-1">➕ Tambah Fitur Baru</h5>
        <small class="text-muted">
            Contoh kode: <code>custom_alias</code>, <code>otp_link</code>, <code>remove_delay</code>
        </small>

        <form method="POST" action="{{ route('admin.features.store') }}" class="row g-2 mt-3">
            @csrf
            <div class="col-md-3">
                <input class="form-control" name="code" placeholder="feature_code" required>
            </div>
            <div class="col-md-5">
                <input class="form-control" name="name" placeholder="Nama fitur" required>
            </div>
            <div class="col-md-auto">
                <button class="btn btn-primary">
                    Tambah Feature
                </button>
            </div>
        </form>
    </div>

    {{-- ================= FEATURE & PRICING ================= --}}
    @foreach($features as $feature)
    <div class="card mb-4 p-3">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong class="fs-5">{{ $feature->name }}</strong><br>
                <small class="text-muted">{{ $feature->code }}</small>
            </div>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 120px;">Tier</th>
                        <th style="width: 150px;">Harga Saat Ini</th>
                        <th>Set / Update Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tiers as $tier)
                        @php
                            $price = $feature->prices->firstWhere('tier', $tier);
                        @endphp
                        <tr>
                            <td>
                                <span class="badge
                                    @if($tier === 'basic') bg-secondary
                                    @elseif($tier === 'premium') bg-warning text-dark
                                    @else bg-info
                                    @endif">
                                    {{ ucfirst($tier) }}
                                </span>
                            </td>

                            <td>
                                @if($price)
                                    <strong>{{ $price->price_coins }}</strong> Coins
                                @else
                                    <span class="text-muted fst-italic">Belum diset</span>
                                @endif
                            </td>

                            <td>
                                <form method="POST"
                                      action="{{ route('admin.features.prices.store', $feature) }}"
                                      class="d-flex gap-2 align-items-center">
                                    @csrf
                                    <input type="hidden" name="tier" value="{{ $tier }}">

                                    <input type="number"
                                           name="price_coins"
                                           class="form-control form-control-sm"
                                           placeholder="Coins (0 = gratis)"
                                           min="0"
                                           required>

                                    <button class="btn btn-sm btn-outline-success">
                                        Simpan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
    @endforeach

    {{-- ================= DISCOUNT ================= --}}
    <div class="card mb-4 p-3">
        <h5 class="mb-1">🏷️ Buat Diskon</h5>
        <small class="text-muted">Berlaku global atau dikaitkan ke fitur tertentu (opsional)</small>

        <form method="POST" action="{{ route('admin.discounts.store') }}" class="row g-2 mt-3">
            @csrf
            <div class="col-md-3">
                <input class="form-control" name="code" placeholder="PROMO10" required>
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select" required>
                    <option value="percentage">Persentase (%)</option>
                    <option value="fixed">Potongan Coins</option>
                </select>
            </div>
            <div class="col-md-2">
                <input class="form-control" name="value" placeholder="Nilai" required>
            </div>
            <div class="col-md-auto">
                <button class="btn btn-warning">
                    Buat Diskon
                </button>
            </div>
        </form>
    </div>

    {{-- ================= FEATURE GRANT ================= --}}
    <div class="card p-3">
        <h5 class="mb-1">🎁 Grant / Gratisin Fitur ke User</h5>
        <small class="text-muted">
            Untuk kompensasi komplain, loyalty, atau trial user
        </small>

        <form method="POST" action="{{ route('admin.feature-grants.store') }}" class="row g-2 mt-3">
            @csrf

            <div class="col-md-4">
                <select name="user_id" class="form-select" required>
                    <option value="">-- Pilih User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->email }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="feature_id" class="form-select" required>
                    <option value="">-- Pilih Feature --</option>
                    @foreach($features as $feature)
                        <option value="{{ $feature->id }}">
                            {{ $feature->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <input class="form-control"
                       name="quota"
                       placeholder="Quota (opsional)">
            </div>

            <div class="col-md-auto">
                <button class="btn btn-success">
                    Grant Feature
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
