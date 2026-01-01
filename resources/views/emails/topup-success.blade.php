<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Topup Berhasil</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f7f8fa;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 30px;
        }
        h2 {
            color: #C86B82;
            margin-bottom: 20px;
            font-size: 24px;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
        }
        .highlight {
            font-weight: bold;
            color: #C86B82;
        }
        .btn {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 24px;
            background-color: #C86B82;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #C86B82;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Topup Berhasil! 🎉</h2>
        <p>Halo <span class="highlight">{{ $topup->user->name }}</span>,</p>
        <p>Koin Anda bertambah <span class="highlight">{{ $topup->coins }}</span> setelah topup dengan Order ID: <span class="highlight">{{ $topup->order_id }}</span>.</p>
        <p>Terima kasih telah melakukan topup di Cuma.Click. Anda dapat menggunakan koin ini untuk menikmati semua fitur premium yang tersedia.</p>
        <!-- <a href="{{ route('links.premium') }}" class="btn">Gunakan Koin Sekarang</a> -->
        <div class="footer">
            &copy; {{ date('Y') }} Cuma.Click Semua hak dilindungi.
        </div>
    </div>
</body>
</html>
