@extends('layouts.master')

@section('content')
<div class="container py-4">
    <h4 class="mb-4"><a href="{{ route('links.index') }}"><i class="fa-solid fa-angle-left me-2"></i></a> Edit Shortlink</h4>

    @if(session('msg'))
        <div class="alert alert-success">{{ session('msg') }}</div>
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

    {{-- INITIAL FEATURE STATE --}}
    <input type="hidden"
       id="initial_features"
       value='@json($initialFeatures)'>

    <form id="editForm" method="POST" action="{{ route('links.update', $link->short_code) }}">
        @csrf
        @method('PUT')

        {{-- BASIC --}}
        <div class="card mb-4">
            <div class="card-header">Basic</div>
            <div class="card-body row g-3">

                {{-- SHORT CODE --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Short Link <span class="text-danger">*</span>
                    </label>

                    <div class="input-group">
                        <span class="input-group-text" readonly>
                            {{ url('/') }}/
                        </span>
                        <input name="short_code" id="short_code"
                               class="form-control @error('short_code') is-invalid @enderror"
                               value="{{ old('short_code', $link->short_code) }}">
                        @error('short_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- CUSTOM ALIAS --}}
                <div class="col-md-6">
                    <label class="form-label">Custom Alias</label>
                    <input name="custom_alias" id="custom_alias"
                           class="form-control @error('custom_alias') is-invalid @enderror"
                           value="{{ old('custom_alias', $link->custom_alias) }}"
                           placeholder="Minimal 6 karakter, huruf atau angka">
                    @error('custom_alias')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <div class="card p-3">
                        <h5 class="fw-bold mb-3">Spen Coins for Short Code & Custom Alias</h5>
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <div class="border rounded p-2 text-center">
                                    <div class="fw-bold">4 Karakter</div>
                                    <div><i class="fa-solid fa-coins me-1"></i> {{ featurePrice('custom_4', auth()->user()->tier) }} coins</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="border rounded p-2 text-center">
                                    <div class="fw-bold">3 Karakter</div>
                                    <div><i class="fa-solid fa-coins me-1"></i> {{ featurePrice('custom_3', auth()->user()->tier) }} coins</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="border rounded p-2 text-center">
                                    <div class="fw-bold">2 Karakter</div>
                                    <div><i class="fa-solid fa-coins me-1"></i> {{ featurePrice('custom_2', auth()->user()->tier) }} coins</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="border rounded p-2 text-center">
                                    <div class="fw-bold">1 Karakter</div>
                                    <div><i class="fa-solid fa-coins me-1"></i> {{ featurePrice('custom_1', auth()->user()->tier) }} coins</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DESTINATION URL (ONLY IF TYPE = URL) --}}
                @if($link->destination_type === 'url')
                <div class="col-md-12">
                    <label class="form-label">
                        Destination URL <span class="text-danger">*</span>
                    </label>
                    <input name="destination_url"
                           class="form-control @error('destination_url') is-invalid @enderror"
                           value="{{ old('destination_url', $link->destination_url) }}">
                    @error('destination_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @endif

                {{-- FILE PREVIEW (ONLY IF TYPE = FILE) --}}
                @if($link->destination_type === 'file')
                <div class="col-md-12">
                    <label class="form-label">Destination File</label>

                    <div class="alert alert-info mb-3">
                        File terhubung dengan shortlink ini dan tidak dapat diubah.
                    </div>

                    {{-- INLINE PREVIEW --}}
                    @php
                        $url = $link->destination_url;
                        $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));

                        $images = ['jpg','jpeg','png','webp','gif'];
                        $videos = ['mp4','webm','ogg'];
                    @endphp
                    @if(in_array($ext, $images))
                    <div class="d-flex justify-content-center mb-3">
                        <div class="border rounded shadow-sm p-2"
                            style="max-width:720px;">
                            <img
                                src="{{ route('file.stream', $link->short_code) }}"
                                class="img-fluid rounded"
                                style="max-height:400px; object-fit:contain;">
                        </div>
                    </div>
                    @endif
                    @if($ext === 'pdf')
                    <div class="d-flex justify-content-center mb-3">
                        <div class="border rounded shadow-sm overflow-hidden"
                            style="width:100%; max-width:720px; height:400px;">
                            <embed
                                src="{{ route('file.stream', $link->short_code) }}"
                                type="application/pdf"
                                width="100%"
                                height="100%">
                        </div>
                    </div>
                    @endif
                    @if(in_array($ext, $videos))
                    <div class="d-flex justify-content-center mb-3">
                        <video
                            src="{{ route('file.stream', $link->short_code) }}"
                            controls
                            class="rounded shadow-sm"
                            style="max-width:720px; width:100%; max-height:400px;">
                        </video>
                    </div>
                    @endif
                    @if(!in_array($ext, array_merge($images, $videos, ['pdf'])))
                    <div class="text-center mb-3">
                        <div class="alert alert-secondary">
                            Preview tidak tersedia untuk file <strong>.{{ $ext }}</strong>
                        </div>

                        <a href="{{ route('file.stream', $link->short_code) }}"
                        target="_blank"
                        class="btn btn-outline-primary btn-sm">
                            <i class="fa fa-download"></i> Unduh File
                        </a>
                    </div>
                    @endif

                    <div class="text-center">
                        <a href="{{ route('file.stream', $link->short_code) }}"
                        target="_blank"
                        class="btn btn-sm btn-outline-secondary">
                            <i class="fa fa-up-right-from-square"></i> Buka di Tab Baru
                        </a>
                    </div>
                </div>
                @endif

            </div>
        </div>

        {{-- SECURITY --}}
        <div class="card mb-4">
            <div class="card-header">Security</div>
            <div class="card-body row g-3">

                <div class="col-md-4">
                    <label class="form-label">PIN Code ( <i class="fa-solid fa-coins me-1"></i> {{ featurePrice('pin_code', auth()->user()->tier) }} coins )</label>
                    <input name="pin_code" id="pin_code"
                        class="form-control @error('pin_code') is-invalid @enderror"
                        value="{{ old('pin_code', $link->pin_code) }}"
                        maxlength="4"
                        placeholder="Harus 4 angka"
                        oninput="validatePinCode(this)">
                    @error('pin_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <script>
                    function validatePinCode(input) {
                        // Hapus semua karakter selain angka
                        input.value = input.value.replace(/\D/g, '');
                        
                        // Batasi panjang maksimal 4 digit
                        if (input.value.length > 4) {
                            input.value = input.value.slice(0, 4);
                        }
                    }
                    </script>
                </div>

                <div class="col-md-8">
                    <label class="form-label">Password Hint</label>
                    <input name="password_hint"
                            placeholder="Petunjuk singkat PIN"
                           class="form-control @error('password_hint') is-invalid @enderror"
                           value="{{ old('password_hint', $link->password_hint) }}">
                    @error('password_hint')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                            type="checkbox"
                            name="require_otp"
                            id="require_otp"
                            {{ old('require_otp', $link->require_otp) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="require_otp">
                            Require OTP
                            <span class="text-muted ms-1">
                                ( <i class="fa-solid fa-coins me-1"></i>
                                {{ featurePrice('require_otp', auth()->user()->tier) }} coins )
                            </span>
                        </label>
                    </div>
                </div>

            </div>
        </div>

        {{-- RULES & STATUS --}}
        <div class="card mb-4">
            <div class="card-header">Rules & Status</div>
            <div class="card-body row g-3">

                <div class="col-md-4">
                    <label>Max Click</label>
                    <input type="number" name="max_click"
                            placeholder="Setting maximal click"
                           class="form-control @error('max_click') is-invalid @enderror"
                           value="{{ old('max_click', $link->max_click) }}">
                    @error('max_click')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label>Active From</label>
                    <input type="datetime-local" name="active_from"
                           class="form-control"
                           value="{{ optional($link->active_from)->format('Y-m-d\TH:i') }}">
                </div>

                <div class="col-md-4">
                    <label>Active Until</label>
                    <input type="datetime-local" name="active_until"
                           class="form-control"
                           value="{{ optional($link->active_until)->format('Y-m-d\TH:i') }}">
                </div>

                <div class="col-md-12">
                    <label>Expired At</label>
                    <input type="datetime-local" name="expired_at"
                           class="form-control"
                           value="{{ optional($link->expired_at)->format('Y-m-d\TH:i') }}">
                </div>

                <div class="col-md-12">
                    <div class="row g-3">

                        <div class="col-md-6 col-lg-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="is_active"
                                    id="is_active"
                                    {{ old('is_active', $link->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="one_time"
                                    id="one_time"
                                    {{ old('one_time', $link->one_time) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="one_time">
                                    One Time
                                    <span class="text-muted ms-1">
                                        ( <i class="fa-solid fa-coins me-1"></i>
                                        {{ featurePrice('one_time', auth()->user()->tier) }} coins )
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="enable_preview"
                                    id="enable_preview"
                                    {{ old('enable_preview', $link->enable_preview) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="enable_preview">
                                    Enable Preview
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="enable_ads"
                                    id="enable_ads"
                                    {{ old('enable_ads', $link->enable_ads) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="enable_ads">
                                    Disable Ads
                                    <span class="text-muted ms-1">
                                        ( <i class="fa-solid fa-coins me-1"></i>
                                        {{ featurePrice('enable_ads', auth()->user()->tier) }} coins )
                                    </span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        {{-- META --}}
        <div class="card mb-4">
            <div class="card-header">Meta</div>
            <div class="card-body">
                <label>Title</label>
                <input name="title"
                       class="form-control mb-2"
                       value="{{ old('title', $link->title) }}">

                <label>Description</label>
                <textarea name="description"
                          class="form-control mb-2">{{ old('description', $link->description) }}</textarea>

                <label>Note</label>
                <textarea name="note"
                          class="form-control">{{ old('note', $link->note) }}</textarea>
            </div>
        </div>

        <button class="btn btn-primary">
            <i class="fa-solid fa-save me-1"></i> Simpan Perubahan
        </button>
    </form>
</div>
<script>
let FEATURE_PRICES = {};

fetch("{{ route('features.price-map') }}")
    .then(res => res.json())
    .then(data => FEATURE_PRICES = data);
</script>
<script>
document.getElementById('editForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const initial = JSON.parse(document.getElementById('initial_features').value);
    const charges = [];

    /* ===== SHORT CODE ===== */
    const newLen = document.getElementById('short_code').value.length;
    if (newLen <= 4 && newLen !== initial.short_code) {
        const price = FEATURE_PRICES[`custom_${newLen}`] ?? 0;
        if (price > 0) {
            charges.push({
                key: `custom_${newLen}`,
                label: `Custom Short Code (${newLen} char)`,
                price
            });
        }
    }

    /* ===== CUSTOM ALIAS ===== */
    if (document.getElementById('custom_alias').value.trim() && !initial.custom_alias) {
        const price = FEATURE_PRICES.custom_alias ?? 0;
        if (price > 0) charges.push({
            key: 'custom_alias',
            label: 'Custom Alias',
            price
        });
    }

    /* ===== PIN CODE ===== */
    if (document.getElementById('pin_code').value.trim() && !initial.pin_code) {
        const price = FEATURE_PRICES.pin_code ?? 0;
        if (price > 0) charges.push({
            key: 'pin_code',
            label: 'PIN Code',
            price
        });
    }

    /* ===== CHECKBOX ===== */
    [
        ['require_otp', 'Require OTP'],
        ['enable_ads', 'Enable Ads'],
        ['one_time', 'One Time Link'],
    ].forEach(([key, label]) => {
        if (!initial[key] && document.getElementById(key).checked) {
            const price = FEATURE_PRICES[key] ?? 0;
            if (price > 0) charges.push({ key, label, price });
        }
    });

    /* ===== GRATIS ===== */
    if (charges.length === 0) {
        this.submit();
        return;
    }

    /* ===== SUMMARY ===== */
    let total = 0;
    let html = '<ul class="text-start">';
    charges.forEach(c => {
        total += c.price;
        html += `<li>${c.label} — <b>${c.price} coins</b></li>`;
    });
    html += `</ul><hr><h5>Total: <i class="fa-solid fa-coins me-1"></i>${total} coins</h5>`;

    Swal.fire({
        title: 'Confirm Feature Upgrade',
        html,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Pay & Save',
        confirmButtonColor: '#B45A71'
    }).then(res => {
        if (!res.isConfirmed) return;

        fetch("{{ route('features.pay') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ charges })
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'error') {
                Swal.fire('Error', res.message, 'error');
                return;
            }

            // PAYMENT OK → SUBMIT UPDATE LINK
            document.getElementById('editForm').submit();
        })
        .catch(() => {
            Swal.fire('Error', 'Payment failed', 'error');
        });
    });
});
</script>

@if(session('msg'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('msg') }}',
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif


@endsection
