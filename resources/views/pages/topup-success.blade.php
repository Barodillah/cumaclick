<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Topup Berhasil</title>
    <meta http-equiv="refresh" content="5;url={{ $redirectUrl }}">
</head>
<body style="font-family: Arial, sans-serif; text-align:center; margin-top:50px;">
    <h2>Topup Berhasil!</h2>
    <p>Coin Anda bertambah <strong>{{ $coins }}</strong>.</p>
    <p>Anda akan diarahkan otomatis dalam 5 detik...</p>
    <a href="{{ $redirectUrl }}">Klik di sini jika tidak diarahkan otomatis</a>
</body>
</html>
