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

                <form method="POST" action="{{ route('redirect.pin', $code) }}" id="pinForm">
                    @csrf

                    <div class="d-flex justify-content-center gap-2 mb-3">
                        @for ($i = 1; $i <= 4; $i++)
                            <input type="text"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                maxlength="1"
                                class="form-control text-center pin-input"
                                style="width:60px;height:60px;font-size:24px;"
                                required>
                        @endfor
                    </div>

                    <input type="hidden" name="pin" id="pin">

                    @error('pin')
                        <div class="alert alert-danger py-2 text-center">
                            {{ $message }}
                        </div>
                    @enderror

                    @if(session('error'))
                        <div class="alert alert-danger py-2 text-center">
                            {{ session('error') }}
                        </div>
                    @endif

                    <button class="btn btn-primary w-100 d-none" id="submitBtn">
                        Open
                    </button>
                </form>

                <a href="/" class="text-decoration-none d-block mt-3">close</a>
            </div>
        </div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('.pin-input');
    const hiddenPin = document.getElementById('pin');
    const form = document.getElementById('pinForm');

    inputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            input.value = input.value.replace(/\D/g, '');

            if (input.value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }

            const pin = Array.from(inputs).map(i => i.value).join('');
            hiddenPin.value = pin;

            if (pin.length === 4) {
                form.submit(); // auto submit
            }
        });

        input.addEventListener('keydown', e => {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });

    inputs[0].focus();
});
</script>

@endsection
