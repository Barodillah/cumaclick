@extends('layouts.viewer')

@section('content')

    <p class="text-center mt-3">{{ $short->note }}</p>

<main class="container flex-fill text-center">

@if ($type === 'image')
    <img src="{{ route('file.stream', [$short->short_code]) }}?t={{ $token }}" class="img-fluid rounded">

@elseif ($type === 'video')
    <video controls autoplay class="w-100 rounded">
        <source src="{{ route('file.stream', [$short->short_code]) }}?t={{ $token }}">
    </video>

@elseif ($type === 'pdf')
    <div id="pdf-viewer"></div>

@elseif ($type === 'office')
    <iframe
        src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(route('file.stream', $short->short_code)) }}"
        class="w-100"
        style="height:90vh"
        frameborder="0">
    </iframe>

@else
    <iframe src="{{ route('file.stream', [$short->short_code]) }}?t={{ $token }}" class="w-100" style="height:80vh"></iframe>
@endif

</main>

@if(!$short->one_time)
<footer class="my-4 text-center">
    <a href="{{ route('file.download', $short->short_code) }}"
       class="btn btn-primary">
        <i class="fa-solid fa-download me-1"></i> Download File
    </a>
</footer>
@endif

@if ($type === 'pdf')
<script>
const url = "{{ route('file.stream', [$short->short_code]) }}?t={{ $token }}";
pdfjsLib.getDocument(url).promise.then(pdf => {
    pdf.getPage(1).then(page => {
        const viewport = page.getViewport({scale:1.3});
        const canvas = document.createElement('canvas');
        canvas.height = viewport.height;
        canvas.width = viewport.width;
        page.render({canvasContext: canvas.getContext('2d'), viewport});
        document.getElementById('pdf-viewer').appendChild(canvas);
    });
});
</script>
@endif

@if(!$short->one_time && !$short->enable_ads)
    <script src="https://pl28358604.effectivegatecpm.com/63/b5/5a/63b55af1b4e85d7e372a49d18103a77a.js"></script>
@endif
@if(!$short->one_time && !$short->enable_ads)
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
@endsection
