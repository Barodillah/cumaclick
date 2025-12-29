@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row g-4 justify-content-center align-items-start">

        {{-- Card Redirect --}}
        <div class="col-lg-4 order-1 order-lg-2">
            <div class="card p-4 shadow text-center redirect-card">
                <h5 class="mb-3">
                    <i class="fa-solid fa-hourglass-half me-2"></i>
                    Tunggu sebentar...
                </h5>

                <p>Anda akan dialihkan ke:</p>
                <div class="alert alert-secondary text-truncate" title="{{ $target }}">
                    {{ $url }}
                </div>

                <p>
                    Redirect dalam
                    <span id="counter" class="countdown-number">3</span>
                    detik...
                </p>

                <div class="progress mb-3">
                    <div id="progressBar"
                         class="progress-bar progress-bar-striped progress-bar-animated"
                         style="width:0%"></div>
                </div>

                <a href="{{ $target }}" class="btn btn-primary w-100">
                    <i class="fa-solid fa-arrow-up-right-from-square me-2"></i>
                    Langsung Buka Link
                </a>
            </div>
        </div>

        {{-- SLOT IKLAN KIRI --}}
        @include('redirect.partials.ads')

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const target = @json($target);

    const duration = 5000; // 5 detik (ms)
    const startTime = performance.now();

    const counter = document.getElementById('counter');
    const bar = document.getElementById('progressBar');

    function animate(now) {
        const elapsed = now - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // Progress bar smooth
        bar.style.width = `${progress * 100}%`;

        // Countdown akurat (5 → 4 → 3 → 2 → 1 → 0)
        const remaining = Math.max(
            0,
            Math.ceil((duration - elapsed) / 1000)
        );
        counter.innerText = remaining;

        if (progress < 1) {
            requestAnimationFrame(animate);
        } else {
            // ekstra delay kecil biar UX terasa halus
            setTimeout(() => {
                window.location.href = target;
            }, 200);
        }
    }

    requestAnimationFrame(animate);
});
</script>
@endsection
