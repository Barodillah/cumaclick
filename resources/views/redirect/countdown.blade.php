@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row g-4 justify-content-center align-items-start">
        
        {{-- Slot Iklan --}}
        <div class="col-lg-3 col-md-6 order-2 order-lg-1">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-center">

                    {{-- GANTI DENGAN SCRIPT IKLAN --}}
                    <div class="ad-slot text-center w-100">
                        <small class="text-muted d-block mb-2">Iklan</small>

                        <div class="ad-placeholder">
                            <span>Responsive Ads Here</span>
                        </div>

                        {{-- contoh Google AdSense --}}
                        {{--
                        <ins class="adsbygoogle"
                             style="display:block"
                             data-ad-client="ca-pub-XXXX"
                             data-ad-slot="XXXX"
                             data-ad-format="auto"
                             data-full-width-responsive="true"></ins>
                        --}}
                    </div>

                </div>
            </div>
        </div>

        {{-- Card Redirect --}}
        <div class="col-lg-4 order-1 order-lg-2">
            <div class="card p-4 shadow text-center">
                <h5 class="mb-3">
                    <i class="fa-solid fa-hourglass-half me-2"></i>
                    Tunggu sebentar...
                </h5>

                <p>Anda akan dialihkan ke:</p>
                <div class="alert alert-secondary text-truncate" title="{{ $target }}">
                    {{ $url }}
                </div>

                <p>Redirect dalam <span id="counter">3</span> detik...</p>

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

        {{-- Slot Iklan --}}
        <div class="col-lg-3 col-md-6 order-3 order-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-center">

                    {{-- GANTI DENGAN SCRIPT IKLAN --}}
                    <div class="ad-slot text-center w-100">
                        <small class="text-muted d-block mb-2">Iklan</small>

                        <div class="ad-placeholder">
                            <span>Responsive Ads Here</span>
                        </div>

                        {{-- contoh Google AdSense --}}
                        {{--
                        <ins class="adsbygoogle"
                             style="display:block"
                             data-ad-client="ca-pub-XXXX"
                             data-ad-slot="XXXX"
                             data-ad-format="auto"
                             data-full-width-responsive="true"></ins>
                        --}}
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let count = 3;
    const counter = document.getElementById('counter');
    const bar = document.getElementById('progressBar');
    const target = @json($target);

    bar.style.transition = "width 3s linear";
    bar.style.width = "100%";

    function tick() {
        counter.innerText = count;
        if (count <= 0) {
            window.location.href = target;
        }
        count--;
        setTimeout(tick, 1000);
    }

    tick();
});
</script>
@endsection
