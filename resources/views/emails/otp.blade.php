<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cuma.Click - OTP Code</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding:40px 15px;">
                <table width="100%" max-width="520" cellpadding="0" cellspacing="0"
                       style="background:#ffffff; border-radius:8px; padding:30px; box-shadow:0 2px 8px rgba(0,0,0,0.05);">

                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <h2 style="margin:0; color:#222;">
                                {{ $type === 'password_reset' ? 'Reset Password' : ($type === 'open_link' ? 'Buka Tautan Aman' : 'Verifikasi Email') }}
                            </h2>
                            <p style="margin:5px 0 0; color:#777; font-size:14px;">
                                Cuma.Click
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="color:#444; font-size:14px; line-height:1.6;">
                            <p>Halo,</p>

                            <p>
                                Berikut adalah kode OTP Anda:
                            </p>

                            <div style="text-align:center; margin:25px 0;">
                                <span style="
                                    display:inline-block;
                                    padding:12px 24px;
                                    font-size:22px;
                                    letter-spacing:4px;
                                    font-weight:bold;
                                    color:#ffffff;
                                    background:#B45A71;
                                    border-radius:6px;
                                ">
                                    {{ $otp }}
                                </span>
                            </div>

                            @if($type === 'password_reset')
                                <p>
                                    Gunakan kode ini untuk mereset password akun JB Events Anda.
                                </p>
                            @elseif($type === 'open_link')
                                <p>
                                    Gunakan kode ini untuk membuka tautan aman yang Anda minta.
                                </p>
                            @else
                                <p>
                                    Gunakan kode ini untuk memverifikasi akun baru JB Events Anda.
                                </p>
                            @endif

                            <p style="color:#888; font-size:13px;">
                                Kode OTP berlaku selama <strong>5 menit</strong>.
                            </p>

                            <p style="margin-top:30px;">
                                Terima kasih,<br>
                                <strong>Tim Cuma.Click</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding-top:30px; border-top:1px solid #eee;">
                            <p style="margin:0; font-size:12px; color:#aaa;">
                                © {{ date('Y') }} Cuma.Click All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
