@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
                <div class="col-lg-6 col-md-9 mx-auto">

                    <div class="card p-4 shadow text-center">

                        <div class="brand-logo mb-4">
                            <a href="/">
                                <img style="width: 50px; height: auto;" src="/favicon.png" alt="logo">
                            </a>
                        </div>
                        @php
                            $title = match(request('type')) {
                                'password_reset' => 'Reset Password Verification',
                                'open_link' => 'Secure Link Verification',
                                default => 'Email Verification',
                            };
                        @endphp

                        <h4 class="mb-2">
                            {{ $title }}
                        </h4>
                        <p class="font-weight-light mb-4">
                            Enter the 6-digit code we sent to:
                            <br>
                            <strong>{{ $email }}</strong>
                        </p>

                        <form method="POST" action="{{ route('otp.verify') }}" id="otpForm">
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
                                @for ($i = 0; $i < 6; $i++)
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
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('.otp-input');
    const otpForm = document.getElementById('otpForm');
    const otpCodeInput = document.getElementById('otpCode');

    inputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            if(input.value.length > 0 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }

            // Collect code
            let code = Array.from(inputs).map(i => i.value).join('');
            otpCodeInput.value = code;

            // Auto submit if complete
            if(code.length === inputs.length) {
                otpForm.submit();
            }
        });

        input.addEventListener('keydown', (e) => {
            if(e.key === 'Backspace' && input.value === '' && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });
});
</script>
@endsection
