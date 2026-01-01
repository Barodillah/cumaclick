<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Topup Berhasil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        /* Reset sederhana */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #e0f7fa, #ffffff);
            color: #212529;
            padding: 15px;
        }

        .container {
            background: #ffffff;
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
            transition: transform 0.3s ease;
        }

        .container:hover {
            transform: translateY(-5px);
        }

        h2 {
            margin-bottom: 15px;
            font-size: 1.8rem;
        }

        p {
            margin: 10px 0;
            font-size: 1rem;
            line-height: 1.5;
        }

        .coins {
            font-weight: bold;
            font-size: 1.3rem;
        }

        .redirect {
            margin-top: 20px;
            font-size: 0.95rem;
        }

        .redirect a {
            text-decoration: none;
        }

        .redirect a:hover {
            text-decoration: underline;
        }

        /* Responsif untuk layar kecil */
        @media (max-width: 480px) {
            h2 { font-size: 1.5rem; }
            .coins { font-size: 1.1rem; }
            .container { padding: 25px 15px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Topup Berhasil!</h2>
        <p>Coin Anda bertambah <span class="coins">{{ $coins }}</span>.</p>
        <p class="redirect">
            Anda akan diarahkan otomatis dalam <span id="countdown">5</span> detik.<br>
            <a href="{{ $redirectUrl }}">Klik di sini jika tidak diarahkan otomatis</a>
        </p>
    </div>

    <script>
        let countdown = 5;
        const countdownEl = document.getElementById('countdown');

        const interval = setInterval(() => {
            countdown--;
            countdownEl.textContent = countdown;
            if (countdown <= 0) {
                clearInterval(interval);
                window.location.href = "{{ $redirectUrl }}";
            }
        }, 1000);
    </script>
</body>
</html>
