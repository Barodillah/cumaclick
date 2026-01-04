@extends('layouts.master')

@section('content')
<style>
    .card-premium {
        color: white;
        border: none;
    
        @auth
            @if(auth()->user()->tier === 'diamond')
                /* 💎 DIAMOND */
                background: linear-gradient(135deg,
                    #1e3c72 0%,
                    #2a5298 50%,
                    #6dd5ed 100%
                );
            @elseif(auth()->user()->tier === 'premium')
                /* ⭐ PREMIUM */
                background: linear-gradient(135deg,
                    #b45309 0%,
                    #f59e0b 50%,
                    #fde68a 100%
                );
            @else
                /* ⚪ BASIC */
                background: linear-gradient(135deg,
                    #4b5563 0%,
                    #9ca3af 50%,
                    #e5e7eb 100%
                );
                color: #111827;
            @endif
        @else
            /* Guest fallback */
            background: linear-gradient(135deg,
                #4b5563 0%,
                #9ca3af 50%,
                #e5e7eb 100%
            );
            color: #111827;
        @endauth
    }
    .coin-card { transition: all 0.3s ease; border: 2px solid transparent; cursor: pointer; }
    .coin-card:hover { transform: translateY(-5px); border-color: #ffc107; box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .coin-card.popular { border-color: #ffc107; position: relative; }
    .feature-icon { width: 35px; height: 35px; background: rgba(25, 135, 84, 0.1); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; }
    .table thead th { background-color: #f8f9fa; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; border-bottom: none; }
    .badge-tier { font-size: 0.8rem; letter-spacing: 0.5px; border-radius: 30px; }
</style>

<div class="container py-5">
    <div class="row g-4">
        {{-- Kiri: Status & Saldo --}}
        <div class="col-lg-4">
            {{-- Balance Card --}}
            <div class="card card-premium shadow-lg mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <p class="text-white-50 mb-1">Total Balance</p>
                            <h2 class="display-6 fw-bold mb-0">
                                <i class="fa-solid fa-coins text-warning me-2"></i>{{ number_format($balance) }}
                            </h2>
                            <small class="text-white-50">Virtual Coins Available</small>
                        </div>
                        @php
                            $tiers = [
                                'basic' => ['label' => 'BASIC', 'class' => 'bg-light text-secondary', 'icon' => 'fa-user'],
                                'premium' => ['label' => 'PREMIUM', 'class' => 'bg-warning text-dark', 'icon' => 'fa-star'],
                                'diamond' => ['label' => 'DIAMOND', 'class' => 'bg-light text-info', 'icon' => 'fa-gem']
                            ];
                            $currentTier = $tiers[auth()->user()->tier] ?? $tiers['basic'];
                        @endphp
                        <a href="javascript:void(0)" id="upgradeTier" class="text-decoration-none">
                            <span class="badge {{ $currentTier['class'] }} badge-tier px-3 py-2 shadow-sm">
                                <i class="fa-solid {{ $currentTier['icon'] }} me-1"></i> {{ $currentTier['label'] }}
                            </span>
                        </a>
                    </div>
                    <button onclick="document.getElementById('nominal').focus()" class="btn btn-light w-100 fw-bold py-2 text-primary">
                        <i class="fa-solid fa-plus-circle me-1"></i> Top Up Now
                    </button>
                </div>
            </div>

            {{-- Benefits Card --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Premium Perks</h5>
                    <div class="d-flex mb-3">
                        <div class="feature-icon me-3"><i class="fa-solid fa-check text-success"></i></div>
                        <div><h6 class="mb-0">Ad-Free Experience</h6><small class="text-muted">No more annoying redirects</small></div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="feature-icon me-3"><i class="fa-solid fa-check text-success"></i></div>
                        <div><h6 class="mb-0">Priority Support</h6><small class="text-muted">24/7 Fast response</small></div>
                    </div>
                    <div class="d-flex">
                        <div class="feature-icon me-3"><i class="fa-solid fa-check text-success"></i></div>
                        <div><h6 class="mb-0">Advanced Analytics</h6><small class="text-muted">Detailed link tracking</small></div>
                    </div>
                </div>
            </div>

            {{-- Ad-Free Toggle --}}
            @if(!auth()->user()->enabled_ads)
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body text-center p-4">
                    <div class="mb-3 text-warning">
                        <i class="fa-solid fa-shield-halved fa-2x"></i>
                    </div>
                    <h6>Disable Ads Permanently?</h6>
                    <p class="small text-muted">Use 20 coins to remove all advertisements from your links.</p>
                    <button id="enableAdFreeBtn" class="btn btn-warning btn-sm px-4" data-price="20">
                        Enable Ad-Free
                    </button>
                </div>
            </div>
            @else
            <div class="alert alert-success border-0 shadow-sm d-flex align-items-center">
                <i class="fa-solid fa-circle-check me-2"></i>
                <small>Ad-Free Experience is Active</small>
            </div>
            @endif
        </div>

        {{-- Kanan: Topup UI --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h4 class="fw-bold">Pilih Paket Coin</h4>
                        <p class="text-muted">Dapatkan lebih banyak keuntungan dengan paket coin hemat di bawah ini.</p>
                    </div>

                    {{-- Instant Buy Cards --}}
                    <div class="row g-3 mb-5">
                        <div class="col-md-4">
                            <div class="card h-100 coin-card p-3 text-center" data-instant data-nominal="10000" data-coins="20">
                                <h2 class="mb-1 text-primary fw-bold">20</h2>
                                <p class="text-muted small mb-3"><i class="fa-solid fa-coins me-1"></i>Coins</p>
                                <h5 class="fw-bold">Rp 10.000</h5>
                                <button
                                data-instant
                                data-nominal="10000"
                                data-coins="20"
                                class="btn btn-outline-warning btn-sm mt-2">Pilih</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 coin-card p-3 text-center popular" data-instant data-nominal="30000" data-coins="45">
                                <span class="badge bg-warning text-dark position-absolute top-0 start-50 translate-middle">BEST VALUE</span>
                                <h2 class="mb-1 text-primary fw-bold">45</h2>
                                <p class="text-muted small mb-3"><i class="fa-solid fa-coins me-1"></i>Coins</p>
                                <h5 class="fw-bold">Rp 30.000</h5>
                                <button
                                data-instant
                                data-nominal="30000"
                                data-coins="45"
                                class="btn btn-warning btn-sm mt-2">Pilih</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 coin-card p-3 text-center" data-instant data-nominal="50000" data-coins="60">
                                <h2 class="mb-1 text-primary fw-bold">60</h2>
                                <p class="text-muted small mb-3"><i class="fa-solid fa-coins me-1"></i>Coins</p>
                                <h5 class="fw-bold">Rp 50.000</h5>
                                <button
                                data-instant
                                data-nominal="50000"
                                data-coins="60"
                                class="btn btn-outline-warning btn-sm mt-2">Pilih</button>
                            </div>
                        </div>
                    </div>

                    <div class="separator d-flex align-items-center text-center my-4">
                        <div class="flex-grow-1 border-bottom"></div>
                        <span class="px-3 text-muted small">ATAU INPUT NOMINAL</span>
                        <div class="flex-grow-1 border-bottom"></div>
                    </div>

                    {{-- Manual Input --}}
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-bold small">Nominal (IDR)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">Rp</span>
                                <input type="number" inputmode=numeric
                                min="5000" id="nominal" class="form-control border-start-0" placeholder="Min. 5.000">
                            </div>
                        </div>
                        <div class="col-md-2 text-center pb-2 d-none d-md-block">
                            <i class="fa-solid fa-arrow-right text-muted"></i>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold small">Coin yang didapat</label>
                            <div class="input-group">
                                <input type="text" id="coins" class="form-control bg-light fw-bold text-primary" readonly placeholder="0 Coins">
                                <span class="input-group-text bg-white"><i class="fa-solid fa-coins text-warning"></i></span>
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <button id="manualBuyBtn" class="btn btn-warning w-100 fw-bold py-3 shadow-sm">
                                <i class="fa-solid fa-bolt me-2"></i> BAYAR SEKARANG
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Transaction History --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Riwayat Transaksi Terakhir</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Tipe</th>
                                <th>Amount</th>
                                <th>Source</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trx)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge {{ $trx->type === 'credit' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-3">
                                        {{ strtoupper($trx->type) }}
                                    </span>
                                </td>
                                <td class="fw-bold {{ $trx->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                    {{ $trx->type === 'credit' ? '+' : '-' }}{{ number_format($trx->amount) }}
                                </td>
                                <td><small class="text-muted">{{ $trx->source ?? 'System' }}</small></td>
                                <td><small class="text-muted">{{ $trx->created_at->diffForHumans() }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">Belum ada transaksi saat ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="upgradeTierModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <div class="text-center w-100 pt-3">
                    <div class="feature-icon-big mb-2">
                        <i class="fa-solid fa-rocket text-primary fa-2x"></i>
                    </div>
                    <h5 class="modal-title fw-bold">Elevate Your Experience</h5>
                    <p class="text-muted small">Pilih paket terbaik untuk kebutuhan profesional Anda</p>
                </div>
                <button class="btn-close position-absolute end-0 top-0 m-3" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div id="upgradeOptions" class="d-grid gap-3">
                    </div>

                @if(auth()->user()->tier === 'diamond')
                <div class="alert alert-info border-0 bg-light-info d-flex align-items-center mt-3">
                    <i class="fa-solid fa-circle-check me-2 fa-lg text-info"></i>
                    <div>
                        <span class="fw-bold d-block">Ultimate Status</span>
                        <small>Anda telah menikmati fitur tertinggi kami.</small>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Confirm Topup Modal -->
<div class="modal fade" id="confirmTopupModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-0 py-3">
                <h6 class="modal-title fw-bold text-uppercase letter-spacing-1">
                    <i class="fa-solid fa-shield-halved text-success me-2"></i>Konfirmasi Pembayaran
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                    <div class="avatar-circle bg-primary-subtle text-primary me-3">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0" id="confirmName">Loading...</h6>
                        <small class="text-muted" id="confirmEmail">loading@mail.com</small>
                    </div>
                </div>

                <div class="bg-light rounded-3 p-3 mb-4 border border-dashed">
                    <h6 class="fw-bold mb-3 small text-muted text-uppercase">Ringkasan Pesanan</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Item</span>
                        <span class="fw-bold"><i class="fa-solid fa-coins text-warning me-1"></i><span id="confirmCoins">0</span> Coins</span>
                    </div>
                    <div class="d-flex justify-content-between mb-0 pt-2 border-top">
                        <span class="fw-bold">Total Pembayaran</span>
                        <span class="fw-bold text-success fs-5">Rp <span id="confirmNominal">0</span></span>
                    </div>
                </div>

                <h6 class="fw-bold mb-3 small text-muted text-uppercase">Lengkapi Data Penagihan</h6>
                <div class="row g-3">
                    <div class="col-12">
                        <div class="form-floating mb-1">
                            <input type="text" class="form-control border-light-subtle" id="topupAddress" placeholder="Alamat">
                            <label for="topupAddress">Alamat Lengkap</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-light-subtle" id="topupPhone" placeholder="WhatsApp">
                            <label for="topupPhone">Nomor WhatsApp (Aktif)</label>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning border-0 bg-warning-subtle py-2 mb-0">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-lock me-2 small"></i>
                        <small style="font-size: 0.7rem;">Data Anda dilindungi secara enkripsi 256-bit SSL.</small>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 p-4 pt-0">
                <button class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary fw-bold px-4 flex-grow-1 py-2 shadow-sm" id="confirmPayBtn">
                    Lanjut ke Pembayaran <i class="fa-solid fa-chevron-right ms-1 small"></i>
                </button>
            </div>
        </div>
    </div>
</div>
<style>
    .avatar-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .bg-primary-subtle {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
    .border-dashed {
        border-style: dashed !important;
    }
</style>

<script 
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>
let selectedNominal = 0;
let selectedCoins = 0;

const userName = @json(auth()->user()->name);
const userEmail = @json(auth()->user()->email);

function openConfirmModal(nominal, coins) {
    selectedNominal = nominal;
    selectedCoins = coins;

    document.getElementById('confirmName').innerText = userName;
    document.getElementById('confirmEmail').innerText = userEmail;
    document.getElementById('confirmNominal').innerText = nominal.toLocaleString('id-ID');
    document.getElementById('confirmCoins').innerText = coins;

    new bootstrap.Modal(document.getElementById('confirmTopupModal')).show();
}

// Link the new manualBuyBtn ID
document.getElementById('manualBuyBtn').addEventListener('click', function () {
    const nominal = parseInt(document.getElementById('nominal').value);
    if (!nominal || nominal < 5000) {
        Swal.fire('Oops!', 'Minimal top up adalah Rp 5.000', 'warning');
        return;
    }
    
    // Use the calculation logic already present
    const coins = calculateCoins(nominal);
    openConfirmModal(nominal, coins);
});

// Helper function to centralize calculation
function calculateCoins(nominal) {
    const MIN = 5000;
    const MAX = 50000;
    const RATE_MIN = 500; 
    const RATE_MAX = 833; 
    const effectiveNominal = Math.min(nominal, MAX);
    const progress = (effectiveNominal - MIN) / (MAX - MIN);
    const rate = RATE_MIN + (RATE_MAX - RATE_MIN) * progress;
    return Math.floor(nominal / rate);
}

/* Manual Buy */
// document.querySelector('.btn.btn-warning.w-100.mb-4')
//     .addEventListener('click', function () {
//         const nominal = parseInt(document.getElementById('nominal').value);
//         if (!nominal || nominal < 5000) {
//             alert('Minimal top up 5.000');
//             return;
//         }

//         const MIN = 5000;
//         const MAX = 50000;

//         const RATE_MIN = 500; // 10K → 20
//         const RATE_MAX = 833; // 50K → 60

//         // Clamp nominal supaya tidak lebih dari MAX
//         const effectiveNominal = Math.min(nominal, MAX);

//         // Hitung progress (0 - 1)
//         const progress = (effectiveNominal - MIN) / (MAX - MIN);

//         // Hitung rate (semakin besar nominal → semakin murah)
//         const rate = RATE_MIN + (RATE_MAX - RATE_MIN) * progress;

//         // Hitung coins
//         const coins = Math.floor(nominal / rate); // contoh rate
//         openConfirmModal(nominal, coins);
//     });

/* Instant Buy */
// Ambil hanya tombol
document.querySelectorAll('.coin-card button').forEach(btn => {
    btn.addEventListener('click', function () {
        // Ambil data dari tombol itu sendiri
        const nominal = parseInt(this.dataset.nominal);
        const coins = parseInt(this.dataset.coins);

        openConfirmModal(nominal, coins);
    });
});

</script>
<script>
document.getElementById('confirmPayBtn').addEventListener('click', function () {
    const address = document.getElementById('topupAddress').value.trim();
    const phone = document.getElementById('topupPhone').value.trim();

    if (!address || !phone) {
        alert('Alamat dan telepon wajib diisi!');
        return;
    }

    fetch('/topup', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
        },
        body: JSON.stringify({
            nominal: selectedNominal,
            coins: selectedCoins,
            address: address,
            phone: phone
        })
    })
    .then(res => res.json())
    .then(res => {
        snap.pay(res.snap_token, {
            onSuccess: function(result){
                // Payment sukses, redirect ke page.topup-success
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("topup.success") }}';

                const token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = document.querySelector('meta[name=csrf-token]').content;
                form.appendChild(token);

                const coinsInput = document.createElement('input');
                coinsInput.type = 'hidden';
                coinsInput.name = 'coins';
                coinsInput.value = selectedCoins;
                form.appendChild(coinsInput);

                const redirectInput = document.createElement('input');
                redirectInput.type = 'hidden';
                redirectInput.name = 'redirectUrl';
                redirectInput.value = '/premium';
                form.appendChild(redirectInput);

                document.body.appendChild(form);
                form.submit();
            },
            onPending: function(result){
                alert("Menunggu pembayaran..."); 
                console.log(result);
            },
            onError: function(result){
                alert("Pembayaran gagal!"); 
                console.log(result);
            },
            onClose: function(){
                alert('Anda menutup popup pembayaran tanpa menyelesaikan.');
            }
        });
    });
});
</script>

<script>
/* Update fungsi upgradeTier click listener */
document.getElementById('upgradeTier').addEventListener('click', function () {
    fetch('/tier/upgrade-options')
        .then(res => res.json())
        .then(res => {
            let html = '';
            res.data.forEach(item => {
                // Menentukan warna icon berdasarkan tier
                let colorClass = item.code === 'upgrade_premium' ? 'text-warning' : 'text-info';
                let icon = item.code === 'upgrade_premium' ? 'fa-star' : 'fa-gem';

                // Ubah benefit menjadi list custom
                let benefitHtml = item.benefit.map(b => `<div style="font-size:0.75rem;">
                <i class="fa-regular fa-circle-check text-success me-1"></i> ${b}</div>`).join('');

                html += `
                    <div class="card border-2 card-hover-shadow mb-2" style="cursor:pointer" onclick="confirmUpgrade('${item.code}', ${item.price})">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="flex-shrink-0 me-3">
                                <i class="fa-solid ${icon} ${colorClass} fa-2x"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0">${item.name}</h6>
                                <div class="text-muted">
                                    ${benefitHtml}
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary-subtle text-dark rounded-pill px-3 py-2">
                                    ${item.price} <i class="fa-solid fa-coins small"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                `;
            });

            document.getElementById('upgradeOptions').innerHTML = html;
            new bootstrap.Modal('#upgradeTierModal').show();
        });
});


function confirmUpgrade(code, price) {
    Swal.fire({
        title: 'Confirm Upgrade?',
        text: `This will cost ${price} coins`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Upgrade'
    }).then(result => {
        if (!result.isConfirmed) return;

        fetch('/tier/upgrade', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ code })
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) throw data;
            return data;
        })
        .then(res => {
            Swal.fire('Success', res.message, 'success')
                .then(() => location.reload());
        })
        .catch(err => {
            Swal.fire(
                'Error',
                err.message ?? 'Upgrade failed',
                'error'
            );
        });
    });
}
</script>

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

<script>
document.getElementById('enableAdFreeBtn').addEventListener('click', function () {
    const price = this.dataset.price;

    Swal.fire({
        title: 'Enable Ad-Free?',
        html: `This will deduct <b><i class="fa-solid fa-coins me-1"></i>${price} coins</b> from your wallet.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Enable',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#B45A71'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("{{ route('wallet.enableAdFree') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: data.message
                    });
                }
            });
        }
    });
});
</script>
@endsection
