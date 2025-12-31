<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Adsense --}}
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7708303851834026" crossorigin="anonymous"></script>
    <meta name="google-adsense-account" content="ca-pub-7708303851834026">

    <title>@yield('title', 'Cuma Click - Lebih dari Sekedar Link')</title>

    <meta name="description" content="Cuma Click adalah platform pemendek link yang kuat dengan analitik, proteksi PIN, hosting file, dan banyak lagi.">
    <meta name="keywords" content="pemendek link, shortlink, hosting file, proteksi PIN, analitik, cuma click">
    <meta name="author" content="Cuma Click">
    <meta name="robots" content="index, follow">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://cuma.click/">
    <meta property="og:title" content="Cuma Click - Lebih dari Sekedar Link">
    <meta property="og:description" content="Platform pemendek link yang kuat dengan analitik, proteksi PIN, hosting file, dan banyak lagi.">
    <meta property="og:image" content="https://cuma.click/favicon.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('/favicon.png') }}" type="image/png">

    {{-- CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Source+Code+Pro:wght@400;600;700&display=swap" rel="stylesheet"> -->

    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .countdown-number {
            font-size: 1.4rem;
            font-weight: 700;
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: .8; }
            50% { transform: scale(1.15); opacity: 1; }
            100% { transform: scale(1); opacity: .8; }
        }

        .progress {
            height: 10px;
            border-radius: 50px;
            overflow: hidden;
            background: #e9ecef;
        }

        .progress-bar {
            background: linear-gradient(
                90deg,
                #B45A71,
                #F7E9EC
            );
            box-shadow: 0 0 12px rgba(180, 90, 113, 0.6);
            transition: width .1s linear;
        }

        .redirect-card {
            animation: fadeInUp .6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    <style>
        .ads-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .ads-modal.hidden {
            display: none;
        }

        .ads-content {
            background: rgba(255, 255, 255, 0.95);
            padding: 15px;
            border-radius: 12px;
            position: relative;
            max-width: 340px;
        }

        .ads-close {
            position: absolute;
            top: -10px;
            right: -10px;

            background: #B45A71;
            color: #fff;
            border: none;

            width: 32px;
            aspect-ratio: 1 / 1; /* KUNCI BULAT */
            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 20px;
            line-height: 1; /* PENTING */
            padding: 0;

            cursor: pointer;
            box-sizing: border-box;
        }

        @media (max-width: 480px) {
            .ads-close {
                width: 36px;
                font-size: 22px;
            }
        }

    </style>
</head>
<body>

@include('partials.navbar')
@include('partials.navlink')
@yield('content')

@auth
@if(auth()->user()->enabled_ads === false)

<!-- MODAL ADS -->
<div id="adsModal" class="ads-modal hidden">
    <div class="ads-content justify-content-center text-center">
        <button class="ads-close" id="closeAds"><i class="fas fa-times"></i></button>

        <!-- IKLAN -->
        <script>
            atOptions = {
                'key' : '4e384a8c295d8194f5d4a36f1411df38',
                'format' : 'iframe',
                'height' : 250,
                'width' : 300,
                'params' : {}
            };
        </script>
        <script src="https://www.highperformanceformat.com/4e384a8c295d8194f5d4a36f1411df38/invoke.js"></script>
        
        <a class="btn btn-sm btn-warning mt-2" href="{{ route('links.premium') }}">
            <i class="fa-solid fa-coins me-1"></i> Tukar coins untuk menghilangkan iklan
        </a>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('adsModal');
    const closeBtn = document.getElementById('closeAds');

    let reopenTimeout;

    function openAds() {
        if (document.getElementById('welcomeBonusModal')) {
            return; // ❌ tahan ads kalau bonus ada
        }
        modal.classList.remove('hidden');
    }

    function closeAds() {
        modal.classList.add('hidden');

        // buka lagi setelah 30 detik
        reopenTimeout = setTimeout(openAds, 30000);
    }

    // buka pertama kali setelah 10 detik
    setTimeout(openAds, 10000);

    closeBtn.addEventListener('click', closeAds);
});
</script>
@endif
@endauth

@include('partials.modal-result')
@include('partials.scripts')

</body>
</html>
