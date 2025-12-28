@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row g-4 justify-content-center align-items-start">

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

        {{-- SLOT IKLAN KIRI --}}
        @include('redirect.partials.ads')

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
