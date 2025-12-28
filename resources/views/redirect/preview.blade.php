@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row g-4 justify-content-center align-items-start">

        {{-- PREVIEW + COUNTDOWN --}}
        <div class="col-lg-4 order-1 order-lg-2">
            <div class="card p-3 shadow text-center">

                <h6 class="mb-2">
                    <i class="fa-solid fa-eye me-2"></i>
                    Preview Tujuan
                </h6>

                {{-- IFRAME PREVIEW --}}
                <div class="ratio ratio-16x9 mb-3 border rounded overflow-hidden">
                    <iframe
                        src="{{ $target }}"
                        frameborder="0"
                        loading="lazy"
                        referrerpolicy="no-referrer"
                        sandbox="allow-scripts allow-forms allow-same-origin">
                    </iframe>
                </div>

                <p class="mb-1 text-muted small">
                    Anda akan dialihkan ke:
                </p>

                <div class="alert alert-secondary text-truncate mb-2" title="{{ $url }}">
                    {{ $url }}
                </div>
                @if(!empty($note))
                <div class="alert alert-warning small">
                    {{ $note }}
                </div>
                @endif

                <p class="small">
                    Redirect dalam <strong><span id="counter">5</span></strong> detik...
                </p>

                <div class="progress mb-3" style="height:6px;">
                    <div id="progressBar"
                         class="progress-bar progress-bar-striped progress-bar-animated"
                         style="width:0%"></div>
                </div>

                <a href="{{ $target }}" class="btn btn-primary w-100">
                    <i class="fa-solid fa-arrow-up-right-from-square me-2"></i>
                    Langsung Buka
                </a>
                <a href="{{ url('/') }}" class="btn btn-outline-secondary w-100 mt-2">
                    <i class="fa-solid fa-xmark me-2"></i>
                    Batalkan
                </a>
            </div>
        </div>

        {{-- SLOT IKLAN KIRI --}}
        @include('redirect.partials.ads')

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let count = 5;
    const counter = document.getElementById('counter');
    const bar = document.getElementById('progressBar');
    const target = @json($target);

    bar.style.transition = "width 5s linear";
    bar.style.width = "100%";

    function tick() {
        counter.innerText = count;
        if (count <= 0) {
            window.top.location.href = target;
            return;
        }
        count--;
        setTimeout(tick, 1000);
    }

    tick();
});
</script>
@endsection
