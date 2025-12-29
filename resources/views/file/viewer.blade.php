@extends('layouts.viewer')

@section('content')

    <p class="text-center mt-3">{{ $short->note }}</p>

<main class="container flex-fill text-center">

@if ($type === 'image')
    <img src="{{ route('file.stream', $short->short_code) }}" class="img-fluid rounded">

@elseif ($type === 'video')
    <video controls autoplay class="w-100 rounded">
        <source src="{{ route('file.stream', $short->short_code) }}">
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
    <iframe src="{{ route('file.stream', $short->short_code) }}" class="w-100" style="height:80vh"></iframe>
@endif

</main>

<footer class="my-4 text-center">
    <a href="{{ route('file.download', $short->short_code) }}"
       class="btn btn-primary">
        <i class="fa-solid fa-download me-1"></i> Download File
    </a>
</footer>

@if ($type === 'pdf')
<script>
const url = "{{ route('file.stream', $short->short_code) }}";
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
@endsection
