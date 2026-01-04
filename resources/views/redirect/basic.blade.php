<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Cuma Click - Lebih dari Sekedar Link' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:description" content="{{ $description ?? 'Platform pemendek link yang kuat dengan analitik, proteksi PIN, hosting file, dan banyak lagi.' }}">
    <meta property="og:image" content="{{ $favicon ?? asset('favicon.png') }}">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link href="{{ asset('/style.css') }}" rel="stylesheet">
    <style>
        :root {
            --main-color: #B45A71;
        }

        body {
            background: #ffffff;
            height: 100vh;
            overflow: hidden;
            color: var(--main-color);
        }

        .center-box {
            position: relative;
            text-align: center;
        }

        .brand-text {
            font-size: 2rem;
            font-weight: 600;
            line-height: 1.3;
        }

        .brand-text span {
            display: block;
            font-weight: 700;
            position: relative;
        }

        /* Cursor */
        .cursor {
            position: absolute;
            font-size: 2.6rem;
            right: -45px;
            opacity: 0;
            animation: cursorMove 2.2s ease-in-out forwards;
        }

        /* Cursor animation (3 frame) */
        @keyframes cursorMove {
            /* FRAME 1 */
            0% {
                top: 0px;
                opacity: 0;
                transform: translateX(0);
                color: #000;
            }
            15% {
                opacity: 1;
            }

            /* FRAME 2 */
            35% {
                top: 42px;
                transform: translateX(0);
            }

            /* FRAME 3 */
            60% {
                top: 84px;
                transform: translateX(-12px);
            }

            /* CLICK EFFECT */
            75% {
                transform: translateX(-12px) scale(0.9);
                color: var(--main-color);
            }

            100% {
                transform: translateX(-12px) scale(1);
                color: var(--main-color);
            }
        }

        /* Click ripple */
        .click-effect {
            position: absolute;
            width: 18px;
            height: 18px;
            border: 2px solid var(--main-color);
            border-radius: 50%;
            opacity: 0;
            animation: clickAnim 0.6s ease-out 1.6s forwards;
            right: -30px;
            top: 98px;
        }

        @keyframes clickAnim {
            0% {
                transform: scale(0.6);
                opacity: 1;
            }
            100% {
                transform: scale(2);
                opacity: 0;
            }
        }

        /* Fade out */
        .fade-out {
            animation: fadeOut 0.6s ease forwards;
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
            }
        }

        @media (max-width: 576px) {
            .brand-text {
                font-size: 1.5rem;
            }
            .cursor {
                font-size: 2.2rem;
                right: -38px;
            }
        }
    </style>
</head>
<body>

<div class="d-flex justify-content-center align-items-center h-100">
    <div class="center-box">

        <!-- Cursor -->
        <div class="cursor">
            <i class="fa-solid fa-arrow-pointer"></i>
        </div>

        <!-- Click effect -->
        <div class="click-effect"></div>

        <!-- Text -->
        <div class="brand-text">
            <span>Buka Link</span>
            <span>Melalui</span>
            <span class="fw-bold">Cuma.Click</span>
        </div>

    </div>
</div>

<script>
    const target = @json($target);

    // Fade out sebelum redirect
    setTimeout(() => {
        document.body.classList.add('fade-out');
    }, 2400);

    // Redirect
    setTimeout(() => {
        window.location.href = target;
    }, 3000);
</script>

</body>
</html>
