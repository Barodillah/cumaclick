@extends('layouts.master')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-4">

            {{-- Balance Card --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Your Balance</small>
                        <h1 class="mb-0 fw-semibold mt-2">
                            <i class="fa-solid fa-coins text-warning me-1"></i>
                            {{ $balance }} Coins
                        </h1>
                    </div>
                    <span class="badge bg-light text-dark px-3 py-2">
                        Premium Account
                    </span>
                </div>
            </div>

            {{-- Info Card --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Premium Features</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Custom domain support</li>
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Advanced analytics</li>
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> PIN protection</li>
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> OTP protection</li>
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Ad-free experience</li>
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> One time link</li>
                    </ul>
                </div>
            </div>

            {{-- Ads Free --}}
            @if(auth()->user()->enabled_ads)
            <div class="alert alert-info text-center mb-4">
                You have enabled Ad-Free experience.
            </div>
            @else
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Enable Ad-Free Experience</h5>
                    <p class="text-muted mb-3">
                        By enabling this option, you will not see ads when redirecting through your links.
                    </p>
                    <a href="#" class="btn btn-warning w-100">
                        <i class="fa-solid fa-coins me-1"></i> Enable Ad-Free for 20 Coins
                    </a>
                </div>
            </div>
            @endif
        </div>
        <div class="col-lg-8">
            {{-- Purchase Card --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body py-4">

                    <div class="text-center mb-2">
                        <i class="fa-solid fa-coins fa-3x text-warning mb-3"></i>
                        <h3 class="fw-bold mb-2">Top Up Coins</h3>
                        <p class="text-muted mb-0">
                            Enter amount or choose instant package below
                        </p>
                    </div>

                    {{-- Manual Input --}}
                    <div class="row mb-2">
                        <div class="col-md-6 mb-1">
                            <label class="form-label fw-semibold">Nominal (IDR)</label>
                            <input type="number" min="5000" id="nominal"
                                class="form-control"
                                placeholder="Minimal 5.000">
                        </div>

                        <div class="col-md-6 mb-1">
                            <label class="form-label fw-semibold">Coins You Get</label>
                            <input type="text" id="coins"
                                class="form-control bg-light"
                                readonly>
                        </div>
                    </div>

                    <button class="btn btn-warning w-100 mb-4">
                        <i class="fa-solid fa-bolt me-1"></i> Buy Now
                    </button>

                    <hr>

                    {{-- Instant Buy --}}
                    <div class="row g-3 text-center">
                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100">
                                <h6 class="fw-semibold">20 Coins</h6>
                                <p class="text-muted small mb-3">Perfect for starters</p>
                                <a href="#" class="btn btn-warning w-100">
                                    10K IDR
                                </a>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100 position-relative">
                                <span class="badge bg-warning text-dark position-absolute top-0 start-50 translate-middle px-3">
                                    Popular
                                </span>
                                <h6 class="fw-semibold mt-2">45 Coins</h6>
                                <p class="text-muted small mb-3">Best value</p>
                                <a href="#" class="btn btn-warning w-100">
                                    30K IDR
                                </a>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100">
                                <h6 class="fw-semibold">60 Coins</h6>
                                <p class="text-muted small mb-3">For power users</p>
                                <a href="#" class="btn btn-warning w-100">
                                    50K IDR
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="col-lg-12"> 
            <div class="card border-0 shadow-sm">
                <div class="card-body py-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">
                            <i class="fa-solid fa-wallet me-1"></i>
                            Wallet Transaction History
                        </h6>

                        <span class="badge bg-primary">
                            Balance: {{ number_format($balance) }} coin
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    @if($isAdmin)
                                        <th>User</th>
                                    @endif
                                    <th>Tipe</th>
                                    <th>Amount</th>
                                    <th>Source</th>
                                    <th>Description</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $trx)
                                    <tr>
                                        @if($isAdmin)
                                            <td>
                                                {{ $trx->wallet->user->email ?? '-' }}
                                            </td>
                                        @endif

                                        <td>
                                            <span class="badge 
                                                {{ $trx->type === 'credit' ? 'bg-success' : 'bg-danger' }}">
                                                {{ strtoupper($trx->type) }}
                                            </span>
                                        </td>

                                        <td class="{{ $trx->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                            {{ $trx->type === 'credit' ? '+' : '-' }}
                                            {{ number_format($trx->amount) }}
                                        </td>

                                        <td>
                                            <small class="text-muted">
                                                {{ $trx->source ?? '-' }}
                                            </small>
                                        </td>

                                        <td>
                                            <small>
                                                {{ $trx->description ?? '-' }}
                                            </small>
                                        </td>

                                        <td>
                                            <small class="text-muted">
                                                {{ $trx->created_at->format('d M Y H:i') }}
                                            </small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $isAdmin ? 6 : 5 }}" class="text-center text-muted py-4">
                                            Belum ada transaksi
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
<script>
document.getElementById('nominal').addEventListener('input', function () {

    const nominal = parseInt(this.value) || 0;
    const coinField = document.getElementById('coins');

    const MIN = 5000;
    const MAX = 50000;

    const RATE_MIN = 500; // 10K → 20
    const RATE_MAX = 833; // 50K → 60

    if (nominal < MIN) {
        coinField.value = '';
        return;
    }

    // Clamp nominal supaya tidak lebih dari MAX
    const effectiveNominal = Math.min(nominal, MAX);

    // Hitung progress (0 - 1)
    const progress = (effectiveNominal - MIN) / (MAX - MIN);

    // Hitung rate (semakin besar nominal → semakin murah)
    const rate = RATE_MIN + (RATE_MAX - RATE_MIN) * progress;

    // Hitung coins
    const coins = Math.floor(nominal / rate);

    coinField.value = coins + ' Coins';
});
</script>
@endsection
