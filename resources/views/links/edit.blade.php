@extends('layouts.master')

@section('content')
<style>
    /* Custom Styles for a more premium look */
    .card { border: none; border-radius: 12px; transition: all 0.3s ease; }
    .shadow-sm-custom { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
    .card-header { background-color: transparent; border-bottom: 1px solid rgba(0,0,0,0.05); font-weight: 700; font-size: 1.1rem; padding: 1.25rem; }
    .form-label { font-weight: 600; color: #444; font-size: 0.9rem; }
    .input-group-text { background-color: #f8f9fa; border-right: none; color: #6c757d; }
    .form-control:focus { border-color: #B45A71; box-shadow: 0 0 0 0.25rem rgba(180, 90, 113, 0.1); }
    .coin-text { color: #000000; font-weight: 700; }
    .badge-price { background: #fff5f7; border: 1px solid #f8d7da; color: #b45a71; border-radius: 8px; padding: 10px; }
    .feature-icon { width: 35px; height: 35px; background: #f0f2f5; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; margin-right: 10px; color: #B45A71; }
    .sticky-bottom-bar { position: sticky; bottom: 20px; z-index: 100; background: rgba(255,255,255,0.8); backdrop-filter: blur(10px); border-radius: 15px; padding: 15px; box-shadow: 0 -5px 20px rgba(0,0,0,0.05); }
</style>

<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <h3 class="fw-bold mb-0"><a href="{{ route('links.index') }}"><i class="fa-solid fa-angle-left me-2"></i></a> Edit Shortlink</h3>
    </div>

    @if(session('msg'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('msg') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
    $initialFeatures = [
        'short_code'   => strlen($link->short_code),
        'custom_alias' => !empty($link->custom_alias),
        'pin_code'     => !empty($link->pin_code),
        'require_otp'  => (bool) $link->require_otp,
        'enable_ads'   => (bool) $link->enable_ads,
        'one_time'     => (bool) $link->one_time,
    ];
    @endphp

    <input type="hidden" id="initial_features" value='@json($initialFeatures)'>

    <form id="editForm" method="POST" action="{{ route('links.update', $link->short_code) }}">
        @csrf
        @method('PUT')
        <input type="hidden" id="link_id" value="{{ $link->id }}">
        <div class="row">
            <div class="col-lg-8">
                {{-- BASIC SECTION --}}
                <div class="card shadow-sm-custom mb-4">
                    <div class="card-header d-flex align-items-center">
                        <div class="feature-icon"><i class="fa-solid fa-link"></i></div>
                        Informasi Dasar
                    </div>
                    <div class="card-body p-4 row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Short Link <span class="text-danger">*</span></label>
                            <div class="input-group mb-1">
                                <span class="input-group-text">{{ url('/') }}/</span>
                                <input name="short_code" id="short_code"
                                       class="form-control @error('short_code') is-invalid @enderror"
                                       value="{{ old('short_code', $link->short_code) }}">
                            </div>
                            <small class="text-muted">Code unik untuk tautan Anda.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Custom Alias</label>
                            <input name="custom_alias" id="custom_alias"
                                   class="form-control @error('custom_alias') is-invalid @enderror"
                                   value="{{ old('custom_alias', $link->custom_alias) }}"
                                   placeholder="Contoh: promo-awal-tahun">
                            <small class="text-muted">Alias yang lebih mudah diingat (Min. 6 Karakter).</small>
                        </div>

                        <div class="col-12 mt-4">
                            <div class="p-3 bg-light rounded-3">
                                <h6 class="fw-bold mb-3"><i class="fa-solid fa-tag me-2 text-primary"></i>Biaya Karakter Pendek</h6>
                                <div class="row g-2 text-center">
                                    @foreach([4,3,2,1] as $num)
                                    <div class="col-md-3">
                                        <div class="badge-price">
                                            <div class="small fw-bold">{{ $num }} Karakter</div>
                                            <div class="small coin-text"><i class="fa-solid fa-coins"></i> {{ featurePrice('custom_'.$num, auth()->user()->tier) }} Coins</div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        @if($link->destination_type === 'url')
                        <div class="col-md-12 mt-3">
                            <label class="form-label">Destination URL</label>
                            <input name="destination_url"
                                   class="form-control @error('destination_url') is-invalid @enderror"
                                   value="{{ old('destination_url', $link->destination_url) }}">
                        </div>
                        @endif
                    </div>
                </div>

                {{-- FILE PREVIEW SECTION --}}
                @if($link->destination_type === 'file')
                <div class="card shadow-sm-custom mb-4">
                    <div class="card-header d-flex align-items-center">
                        <div class="feature-icon"><i class="fa-solid fa-file-invoice"></i></div>
                        Preview File
                    </div>
                    <div class="card-body text-center p-4">
                        @php
                            $url = $link->destination_url;
                            $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
                            $images = ['jpg','jpeg','png','webp','gif'];
                            $videos = ['mp4','webm','ogg'];
                        @endphp

                        <div class="bg-light rounded p-3 mb-3">
                            @if(in_array($ext, $images))
                                <img src="{{ route('file.stream', $link->short_code) }}" class="img-fluid rounded shadow-sm" style="max-height:300px;">
                            @elseif($ext === 'pdf')
                                <embed src="{{ route('file.stream', $link->short_code) }}" type="application/pdf" width="100%" height="350px" class="rounded">
                            @elseif(in_array($ext, $videos))
                                <video src="{{ route('file.stream', $link->short_code) }}" controls class="w-100 rounded" style="max-height:300px;"></video>
                            @else
                                <div class="py-4">
                                    <i class="fa-solid fa-file-zipper fa-3x text-secondary mb-3"></i>
                                    <p>Preview tidak tersedia untuk <strong>.{{ $ext }}</strong></p>
                                </div>
                            @endif
                        </div>
                        <a href="{{ route('file.stream', $link->short_code) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-external-link me-1"></i> Lihat Fullscreen
                        </a>
                    </div>
                </div>
                @endif

                {{-- SECURITY SECTION --}}
                <div class="card shadow-sm-custom mb-4">
                    <div class="card-header d-flex align-items-center text-danger">
                        <div class="feature-icon"><i class="fa-solid fa-shield-halved text-danger"></i></div>
                        Keamanan & Privasi
                    </div>
                    <div class="card-body p-4 row g-3">
                        <div class="col-md-5">
                            <label class="form-label">PIN Code <span class="coin-text small ms-2">( <i class="fa-solid fa-coins"></i> {{ featurePrice('pin_code', auth()->user()->tier) }} Coins )</span></label>
                            <input name="pin_code" id="pin_code" inputmode="numeric" maxlength="4" class="form-control text-center fw-bold border-danger" 
                                   style="letter-spacing: 5px;" value="{{ old('pin_code', $link->pin_code) }}" oninput="this.value = this.value.replace(/\D/g, '')">
                        </div>
                        <div class="col-md-7">
                            <label class="form-label">Password Hint</label>
                            <input name="password_hint" class="form-control" placeholder="Contoh: Tanggal lahir" value="{{ old('password_hint', $link->password_hint) }}">
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch p-3 border rounded">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="require_otp" id="require_otp" {{ old('require_otp', $link->require_otp) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="require_otp">Verifikasi OTP 
                                    <span class="coin-text small ms-2">( <i class="fa-solid fa-coins"></i> {{ featurePrice('require_otp', auth()->user()->tier) }} Coins )</span>
                                </label>
                                <p class="text-muted small mb-0 ms-4">Saat membuka link harus memasukkan kode yang dikirim ke email.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- RULES SECTION --}}
                <div class="card shadow-sm-custom mb-4">
                    <div class="card-header d-flex align-items-center">
                        <div class="feature-icon"><i class="fa-solid fa-sliders"></i></div>
                        Aturan & Status
                    </div>
                    <div class="card-body p-4 g-3">
                        <div class="mb-3">
                            <label class="form-label">Maksimal Klik</label>
                            <input type="number" inputmode="numeric" name="max_click" class="form-control" value="{{ old('max_click', $link->max_click) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Aktif Dari</label>
                            <input type="datetime-local" name="active_from" class="form-control form-control-sm" value="{{ optional($link->active_from)->format('Y-m-d\TH:i') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Aktif Sampai (Expire)</label>
                            <input type="datetime-local" name="expired_at" class="form-control form-control-sm" value="{{ optional($link->expired_at)->format('Y-m-d\TH:i') }}">
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex flex-column gap-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', $link->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_active">Status Link Aktif</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="one_time" id="one_time" {{ old('one_time', $link->one_time) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="one_time">Sekali Lihat <span class="coin-text small ms-1">( <i class="fa-solid fa-coins me-1"></i>{{ featurePrice('one_time', auth()->user()->tier) }} Coins )</span></label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="enable_ads" id="enable_ads" {{ old('enable_ads', $link->enable_ads) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="enable_ads">Nonaktifkan Iklan <span class="coin-text small ms-1">( <i class="fa-solid fa-coins me-1"></i>{{ featurePrice('enable_ads', auth()->user()->tier) }} Coins )</span></label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- META SECTION --}}
                <div class="card shadow-sm-custom mb-4">
                    <div class="card-header d-flex align-items-center">
                        <div class="feature-icon"><i class="fa-solid fa-earth-americas"></i></div>
                        Meta (SEO) & Catatan
                    </div>
                    <div class="card-body p-4">
                        <label class="form-label">SEO Title</label>
                        <input name="title" class="form-control mb-3" value="{{ old('title', $link->title) }}">

                        <label class="form-label">SEO Description</label>
                        <textarea name="description" class="form-control mb-3" rows="2">{{ old('description', $link->description) }}</textarea>
                        
                        <label class="form-label">Catatan Pribadi</label>
                        <textarea name="note" class="form-control" rows="2">{{ old('note', $link->note) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- STICKY ACTION BAR --}}
        <div class="sticky-bottom-bar d-flex justify-content-between align-items-center">
            <div class="text-muted small d-none d-md-block">
                <i class="fa-solid fa-info-circle me-1"></i> Perubahan fitur mungkin memerlukan koin tambahan.
            </div>
            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow">
                <i class="fa-solid fa-save me-2"></i> Simpan Semua Perubahan
            </button>
        </div>
    </form>
</div>

{{-- SCRIPT TETAP SAMA (Logika Bisnis Tidak Diubah) --}}
<script>
let FEATURE_PRICES = {};
fetch("{{ route('features.price-map') }}")
    .then(res => res.json())
    .then(data => FEATURE_PRICES = data);

document.getElementById('editForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const initial = JSON.parse(document.getElementById('initial_features').value);
    const charges = [];
    const linkId = document.getElementById('link_id').value;

    // Short Code Length
    const newLen = document.getElementById('short_code').value.length;
    if (newLen <= 4 && newLen !== initial.short_code) {
        const price = FEATURE_PRICES[`custom_${newLen}`] ?? 0;
        if (price > 0) charges.push({ key: `custom_${newLen}`, label: `Short Code (${newLen} char)`, price });
    }

    // Alias
    if (document.getElementById('custom_alias').value.trim() && !initial.custom_alias) {
        const price = FEATURE_PRICES.custom_alias ?? 0;
        if (price > 0) charges.push({ key: 'custom_alias', label: 'Custom Alias', price });
    }

    // Pin
    if (document.getElementById('pin_code').value.trim() && !initial.pin_code) {
        const price = FEATURE_PRICES.pin_code ?? 0;
        if (price > 0) charges.push({ key: 'pin_code', label: 'PIN Code', price });
    }

    // Switches
    [['require_otp', 'OTP'], ['enable_ads', 'Nonaktifkan Iklan'], ['one_time', 'Sekali Lihat']].forEach(([key, label]) => {
        if (!initial[key] && document.getElementById(key).checked) {
            const price = FEATURE_PRICES[key] ?? 0;
            if (price > 0) charges.push({ key, label, price });
        }
    });

    if (charges.length === 0) { this.submit(); return; }

    let total = 0;
    let html = '<div class="text-start">';
    charges.forEach(c => {
        total += c.price;
        html += `<div class="d-flex justify-content-between"><span>${c.label}</span><b>${c.price} coins</b></div>`;
    });
    html += `<hr><div class="d-flex justify-content-between h5"><span>Total</span><span class="coin-text"><i class="fa-solid fa-coins"></i> ${total}</span></div></div>`;

    Swal.fire({
        title: 'Konfirmasi Upgrade',
        html,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Bayar & Simpan',
        confirmButtonColor: '#B45A71',
        cancelButtonText: 'Batal'
    }).then(res => {
        if (!res.isConfirmed) return;
        fetch("{{ route('features.pay') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({
                link_id: linkId,
                charges
            })
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'error') { Swal.fire('Error', res.message, 'error'); return; }
            document.getElementById('editForm').submit();
        });
    });
});
</script>

@if(session('msg'))
<script>
    Swal.fire({ icon: 'success', title: 'Berhasil', text: '{{ session('msg') }}', timer: 2000, showConfirmButton: false });
</script>
@endif
@endsection