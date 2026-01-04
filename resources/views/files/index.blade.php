@extends('layouts.master')

@section('content')
<div class="container py-5">

    {{-- Header Section --}}
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-1">My Files</h2>
            <p class="text-muted mb-0">Manage your shared links and documents.</p>
        </div>
        <div class="col-md-6 mt-3 mt-md-0 text-md-end">
            <form method="GET" action="{{ route('files.index') }}" class="d-inline-block w-100 w-md-auto">
                <div class="input-group input-group-lg shadow-sm overflow-hidden border-0">
                    <span class="input-group-text bg-white border-0 ps-4 text-muted">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" 
                        name="q" 
                        class="form-control border-0 bg-white ps-2" 
                        placeholder="Search files..." 
                        value="{{ request('q') }}">
                </div>
            </form>
        </div>
    </div>

    {{-- Grid Content --}}
    <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4">
        @forelse ($files as $file)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm rounded-4 card-hover position-relative overflow-hidden">
                    
                    {{-- Status Badge (Overlay) --}}
                    <div class="position-absolute top-0 end-0 p-3 z-2">
                        @if(!$file->is_active)
                             <span class="badge bg-danger bg-opacity-75 backdrop-blur rounded-pill fw-normal">Inactive</span>
                        @elseif($file->one_time == 1)
                             <span class="badge bg-warning text-dark bg-opacity-75 backdrop-blur rounded-pill fw-normal">One-Time</span>
                        @else
                            <span class="badge bg-success bg-opacity-75 backdrop-blur rounded-pill fw-normal">Active</span>
                        @endif
                    </div>

                    {{-- Thumbnail --}}
                    <div class="ratio ratio-4x3 bg-light overflow-hidden">
                        @if($file->one_time != 1)
                            <img src="{{ route('file.stream', $file->short_code) }}" 
                                class="card-img-top object-fit-cover transition-transform" 
                                alt="{{ $file->title }}">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 bg-secondary-subtle text-secondary">
                                <i class="fa-solid fa-file-circle-question fa-3x opacity-50"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body p-3 d-flex flex-column">
                        <h6 class="card-title fw-bold text-dark text-truncate mb-1" title="{{ $file->title }}">
                            {{ $file->title ?? $file->short_code }}
                        </h6>
                        
                        <div class="d-flex align-items-center justify-content-between mb-3 bg-light rounded-3 p-2 border border-light-subtle">
                            <small class="text-primary text-truncate font-monospace me-2" id="link-{{ $file->id }}">
                                {{ url($file->short_code) }}
                            </small>
                            <button class="btn btn-link btn-sm p-0 text-muted btn-copy" 
                                    data-clipboard-text="{{ url($file->short_code) }}"
                                    data-bs-toggle="tooltip" 
                                    title="Copy Link">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>

                        <div class="mt-auto d-flex align-items-center justify-content-between text-muted small">
                            <span><i class="fa-regular fa-calendar me-1"></i> {{ $file->created_at->format('d M Y') }}</span>
                        </div>
                    </div>

                    {{-- Card Footer / Actions --}}
                    <div class="card-footer bg-white border-0 pt-0 pb-3 px-3">
                        <hr class="text-muted opacity-10 mt-0 mb-3">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('links.edit', $file->short_code) }}" 
                            class="btn btn-sm"
                            data-bs-toggle="tooltip" title="Edit File">
                                <i class="fa-solid fa-pen"></i>
                            </a>

                            @if($file->is_active)
                                <button class="btn btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#showBarcode" 
                                    data-barcode="{{ $file->short_code }}"
                                    title="QR Code">
                                    <i class="fa-solid fa-qrcode"></i>
                                </button>

                                <button class="btn btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#showShareLink" 
                                    data-share="{{ $file->short_code }}"
                                    title="Share">
                                    <i class="fa-solid fa-share-nodes"></i>
                                </button>
                            @endif

                            <form method="POST" action="{{ route('links.delete', $file->short_code) }}" class="delete-form m-0 p-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                    class="btn btn-sm"
                                    title="Delete">
                                    <i class="fa-solid fa-trash text-danger"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-4 shadow-sm border border-light-subtle">
                    <div class="mb-3 text-muted opacity-25">
                        <i class="fa-solid fa-folder-open fa-4x"></i>
                    </div>
                    <h5 class="fw-bold text-muted">No files found</h5>
                    <p class="text-muted mb-0">Start by uploading or creating a new link.</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-5">
        {{ $files->links() }}
    </div>
</div>

@include('links.partials.modals')

{{-- Toast Notification for Copy --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="copyToast" class="toast align-items-center text-white bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fa-solid fa-check-circle me-2"></i> Link copied to clipboard!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Copy to Clipboard Logic
    const copyButtons = document.querySelectorAll('.btn-copy');
    const toastEl = document.getElementById('copyToast');
    const toast = new bootstrap.Toast(toastEl);

    copyButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const text = this.getAttribute('data-clipboard-text');
            navigator.clipboard.writeText(text).then(() => {
                toast.show();
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        });
    });

    // SweetAlert Delete
    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (!form.classList.contains('delete-form')) return;
        e.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "This file will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            focusCancel: true,
            customClass: {
                popup: 'rounded-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endsection

@push('styles')
<style>

    /* Card Hover Effect */
    .card-hover {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
    }

    /* Image Zoom on Hover */
    .card-hover:hover .card-img-top {
        transform: scale(1.05);
    }
    
    .transition-transform {
        transition: transform 0.3s ease;
    }

    /* Blur Backdrop for Badges */
    .backdrop-blur {
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }

    /* Utility */
    .object-fit-cover {
        object-fit: cover;
        height: 100%;
        width: 100%;
    }
    
    /* Input Search Focus */
    .input-group:focus-within {
        box-shadow: 0 0 0 0.25rem rgba(180, 90, 113, 0.15) !important;
        transition: box-shadow 0.2s;
    }
    
    .form-control:focus {
        box-shadow: none;
    }
</style>
@endpush