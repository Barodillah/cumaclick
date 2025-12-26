@extends('layouts.master')

@section('content')
<div class="container">

    {{-- Hero --}}
    <section class="hero text-center py-5">
        <h1 class="fw-bold">Lebih dari Sekedar Link.</h1>
        <p class="mt-3">
            Platform pemendek link yang kuat dengan analitik, proteksi PIN, hosting file, dan banyak lagi.
        </p>
    </section>

    {{-- Card --}}
    <div class="rounded-lg border p-2 bg-white/10 backdrop-blur-lg border-white/20 shadow-2xl mb-4">

        {{-- Tabs --}}
        <ul class="nav nav-pills justify-content-center bg-black/20 rounded-md p-1">
            <li class="nav-item flex-fill">
                <button class="nav-link active w-100" data-bs-toggle="tab" data-bs-target="#url">
                    <i class="fa-solid fa-link me-2"></i> Perpendek URL
                </button>
            </li>
            <li class="nav-item flex-fill">
                <button class="nav-link w-100" data-bs-toggle="tab" data-bs-target="#file">
                    <i class="fa-solid fa-folder-open me-2"></i> Unggah File
                </button>
            </li>
        </ul>

        {{-- Content --}}
        <div class="tab-content p-4 mt-3">

            {{-- URL --}}
            <div class="tab-pane fade show active" id="url">
                <form method="POST" action="{{ route('shorten') }}">
                    @csrf
                    <div class="row g-2">
                        <div class="col-lg-9">
                            <input type="text" name="destination_url" class="form-control" placeholder="https://example.com">
                        </div>
                        <div class="col-lg-3">
                            <button class="btn btn-primary w-100">
                                <i class="fa-solid fa-bolt me-1"></i> Perpendek
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Upload --}}
            <div class="tab-pane fade" id="file">
                <form method="POST" action="{{ route('upload') }}" enctype="multipart/form-data">
                    @csrf

                    <div id="dropArea" class="border border-dashed rounded-3 p-4 text-center">
                        <i class="fa-solid fa-cloud-arrow-up fa-3x mb-2"></i>
                        <p>Seret & lepas file, atau klik untuk memilih</p>
                        <input type="file" name="file" id="fileInput" class="d-none">
                    </div>

                    <div id="preview" class="mt-3 text-center"></div>

                    <div class="text-center mt-2">
                        <button class="btn btn-primary">
                            <i class="fa-solid fa-cloud-arrow-up me-2"></i> Upload & Shorten
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @auth
        <div class="text-center my-3">
            <a href="{{ route('links.index') }}" class="text-dark">
                <i class="fa-solid fa-clipboard-list me-2"></i> Lihat Semua Shortlink Anda
            </a>
        </div>
    @else
        <p class="text-center my-3">
            Daftar untuk mendapatkan fitur lengkap secara gratis!
        </p>
    @endauth

</div>
@endsection
