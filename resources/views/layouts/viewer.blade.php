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
    <script src="https://pl28358542.effectivegatecpm.com/a0/f0/8f/a0f08f09d88c14cee42b6b4266bad37a.js"></script>
</head>
<body>

@include('partials.navbar')

@yield('content')

@include('partials.modal-result')
@include('partials.scripts')

</body>
</html>
