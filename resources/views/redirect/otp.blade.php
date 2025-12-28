@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row g-4 justify-content-center align-items-center">

        {{-- CARD PIN --}}
        <div class="col-lg-4 order-1 order-lg-2">
            <div class="card p-4 shadow text-center">

                <h5 class="mb-3">
                    <i class="fa-solid fa-lock text-warning me-2"></i>
                    Open Secure Link
                </h5>
                <p class="font-weight-light mb-4">
                    Enter the 4-digit code we sent to:
                    <br>
                    <strong>{{ $email }}</strong>
                </p>

                <form method="POST" action="{{ route('redirect.otp', $code) }}" id="otpForm">
                    @csrf

                    <input type="hidden" name="email" value="{{ $email }}">
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" name="code" id="otpCode">

                    @if(session('error'))
                        <div class="alert alert-danger py-2 text-center">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success py-2 text-center">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-center gap-2 mb-3">
                        @for ($i = 0; $i < 4; $i++)
                            <input type="text"
                                    @if ($i === 0) autofocus @endif
                                    inputmode="numeric"
                                    pattern="[0-9]*"
                                    maxlength="1"
                                    class="form-control text-center otp-input"
                                    style="width:50px;height:50px;font-size:24px;"
                                    required>
                        @endfor
                    </div>

                    <button type="submit" class="btn btn-primary w-100 d-none" id="submitBtn">
                        Verify
                    </button>
                </form>

                <div class="text-center mt-3 font-weight-light">
                    Didn’t receive the code?
                    <a href="{{ route('otp.resend', ['email' => $email, 'type' => $type]) }}" class="text-primary">Resend</a>
                </div>
            </div>
        </div>

        {{-- SLOT IKLAN KIRI --}}
        @include('redirect.partials.ads')

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('.otp-input');
    const hiddenInput = document.getElementById('otpCode');
    const form = document.getElementById('otpForm');

    inputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            // Hanya angka
            input.value = input.value.replace(/\D/g, '');

            // Fokus ke input berikutnya jika ada
            if (input.value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }

            // Gabungkan kode OTP dan simpan di hidden input
            const otp = Array.from(inputs).map(i => i.value).join('');
            hiddenInput.value = otp;

            // Submit otomatis jika semua input terisi
            if (otp.length === inputs.length) {
                form.submit();
            }
        });

        input.addEventListener('keydown', e => {
            // Backspace pindah ke input sebelumnya
            if (e.key === 'Backspace' && !input.value && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });

    // Fokus di input pertama
    inputs[0].focus();
});
</script>
@endsection
