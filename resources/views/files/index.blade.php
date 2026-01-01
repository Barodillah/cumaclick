@extends('layouts.master')

@section('content')
<div class="container p-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">My Files</h4>

        {{-- Search --}}
        <form method="GET"
            action="{{ route('files.index') }}"
            class="d-flex"
            style="max-width:300px;">
            
            <div class="input-group">
                <input type="text"
                    name="q"
                    class="form-control"
                    placeholder="Search files..."
                    value="{{ request('q') }}">

                <button class="btn btn-primary" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </form>

    </div>

    {{-- Grid --}}
    <div class="row g-4">
        @forelse ($files as $file)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm h-100 file-card">

                    {{-- Thumbnail --}}
                    <div class="ratio ratio-1x1 bg-light file-thumb">
                        @if($file->one_time != 1)
                            <img src="{{ route('file.stream', $file->short_code) }}"
                                alt="{{ $file->title }}">
                        @else
                            <div class="file-icon-wrapper">
                                <i class="fa-solid fa-file-circle-question"></i>
                            </div>
                        @endif
                    </div>


                    {{-- Info --}}
                    <div class="card-body p-2">
                        <div class="fw-semibold text-truncate">
                            {{ $file->title ?? $file->short_code }}
                        </div>
                        <p class="text-primary mb-0">{{ url($file->short_code) }}</p>
                        <small class="text-muted">
                            {{ $file->created_at->format('d M Y') }}
                        </small>
                    </div>

                    {{-- Action --}}
                    <div class="card-footer bg-white border-0 d-flex justify-content-between px-2 pb-2">
                        
                        <a href="{{ route('links.edit', $file->short_code) }}"
                           class="btn btn-sm btn-light">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        @if($file->is_active)
                        <button class="btn btn-sm btn-light"
                                data-bs-toggle="modal"
                                data-bs-target="#showBarcode"
                                data-barcode="{{ $file->short_code }}">
                            <i class="fa-solid fa-qrcode"></i>
                        </button>

                        <button class="btn btn-sm btn-light"
                                data-bs-toggle="modal"
                                data-bs-target="#showShareLink"
                                data-share="{{ $file->short_code }}">
                            <i class="fa-solid fa-share-nodes"></i>
                        </button>
                        @endif
                        <form method="POST"
                            action="{{ route('links.delete', $file->short_code) }}"
                            class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light">
                                <i class="fa-solid fa-trash text-danger"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">
                No files found
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $files->links() }}
    </div>
</div>
@include('links.partials.modals')

<script>
document.addEventListener('submit', function (e) {
    const form = e.target;

    if (!form.classList.contains('delete-form')) return;

    e.preventDefault();

    Swal.fire({
        title: 'Hapus link?',
        text: 'Link yang dihapus tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
</script>
@endsection

@push('styles')
<style>
.file-card {
    transition: transform .15s ease, box-shadow .15s ease;
}
.file-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,.08);
}

.file-thumb {
    overflow: hidden;
}

/* Image */
.file-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Wrapper icon (WAJIB kena .ratio > *) */
.file-icon-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

/* Icon */
.file-icon-wrapper i {
    font-size: 3rem;
    color: #9ca3af;
}

</style>
@endpush
