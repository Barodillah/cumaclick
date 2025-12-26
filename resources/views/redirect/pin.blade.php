@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center align-items-center">

        <div class="col-lg-4">
            <div class="card p-4 shadow text-center">
                <h5 class="mb-3">
                    <i class="fa-solid fa-lock text-warning me-2"></i>
                    Link ini terkunci
                </h5>

                <form method="POST">
                    @csrf
                    <input type="password"
                           name="pin"
                           class="form-control mb-3"
                           maxlength="4"
                           placeholder="Masukkan PIN 4 digit"
                           required>

                    <button class="btn btn-primary w-100">
                        <i class="fa-solid fa-lock-open me-2"></i> Open
                    </button>
                </form>

                <a href="/" class="text-decoration-none d-block mt-3">close</a>
            </div>
        </div>

    </div>
</div>
@endsection
