<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pesan Kontak Baru</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; background-color:#f4f6f8; padding:20px;">

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                       style="background:#ffffff; border-radius:8px; padding:24px; box-shadow:0 2px 8px rgba(0,0,0,0.05);">

                    <tr>
                        <td style="text-align:center; padding-bottom:16px;">
                            <h2 style="margin:0; color:#1f2937;">Pesan Kontak Baru</h2>
                            <p style="margin:6px 0 0; color:#6b7280; font-size:14px;">
                                Anda menerima pesan baru dari formulir kontak
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <table width="100%" cellpadding="6" cellspacing="0"
                                   style="background:#f9fafb; border-radius:6px;">
                                <tr>
                                    <td width="120"><strong>Nama</strong></td>
                                    <td>: {{ $nama }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong></td>
                                    <td>: {{ $email }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:16px;">
                            <strong>Pesan:</strong>
                            <div style="margin-top:8px; padding:12px; background:#f3f4f6; border-radius:6px; line-height:1.6;">
                                {{ $pesan }}
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:24px; font-size:12px; color:#6b7280; text-align:center;">
                            Email ini dikirim otomatis dari sistem website Anda.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
