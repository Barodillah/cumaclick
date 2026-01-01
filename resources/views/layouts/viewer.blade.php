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
    @if(!$short->one_time && !$short->enable_ads)
    <script src="https://pl28358542.effectivegatecpm.com/a0/f0/8f/a0f08f09d88c14cee42b6b4266bad37a.js"></script>
    @endif
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
    @stack('styles')
</head>
<body>

@include('partials.navbar')

@yield('content')

@include('partials.modal-result')
@include('partials.scripts')

</body>
</html>
