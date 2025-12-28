@forelse($links as $link)
<div class="card mb-3 shadow-sm border-0">
    <div class="card-body">

        {{-- ROW ATAS --}}
        <div class="row align-items-start">

            {{-- LEFT --}}
            <div class="col">
                <div class="fw-semibold mb-1">
                    {{ $link->note ?? 'Untitled' }}
                </div>

                <div class="text-primary small">
                        {{ url($link->short_code) }}
                        <a href="javascript:void(0)">
                        <i class="fa-regular fa-copy copyBtn"
                        role="button"
                        data-link="{{ url($link->short_code) }}"></i>
                    </a>
                </div>

            </div>
            <script>
            document.addEventListener('click', function (e) {
                if (!e.target.classList.contains('copyBtn')) return;

                const icon = e.target;
                const link = icon.dataset.link;

                navigator.clipboard.writeText(link).then(() => {
                    // ubah icon jadi centang
                    icon.classList.remove('fa-regular', 'fa-copy');
                    icon.classList.add('fa-solid', 'fa-clipboard-check');

                    // kembalikan ke icon copy setelah 2 detik
                    setTimeout(() => {
                        icon.classList.remove('fa-solid', 'fa-clipboard-check');
                        icon.classList.add('fa-regular', 'fa-copy');
                    }, 2000);
                }).catch(() => {
                    alert('Gagal menyalin link');
                });
            });
            </script>

            {{-- RIGHT ACTIONS --}}
            <div class="col-auto">
                <div class="d-flex gap-2">
                    <!-- <button class="btn btn-sm btn-light"
                            data-bs-toggle="modal"
                            data-bs-target="#editNoteModal"
                            data-id="{{ $link->id }}"
                            data-note="{{ $link->note }}">
                        <i class="fa-solid fa-pen"></i>
                    </button> -->

                    <a href="{{ route('links.edit', $link->short_code) }}" class="btn btn-sm btn-light">
                        <i class="fa-solid fa-pen"></i>
                    </a>

                    <button class="btn btn-sm btn-light"
                            data-bs-toggle="modal"
                            data-bs-target="#showBarcode"
                            data-barcode="{{ $link->short_code }}">
                        <i class="fa-solid fa-qrcode"></i>
                    </button>

                    <button class="btn btn-sm btn-light"
                            data-bs-toggle="modal"
                            data-bs-target="#showShareLink"
                            data-share="{{ $link->short_code }}">
                        <i class="fa-solid fa-share-nodes"></i>
                    </button>

                    <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-ellipsis"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <li>
                                    <a href="{{ route('links.observation', $link->short_code) }}" class="dropdown-item">
                                        <i class="fa-solid fa-link me-2"></i> Link Observation
                                    </a>
                                </li>
                            @endif
                        @endauth
                        <li>
                            <a href="{{ route('links.edit', $link->short_code) }}" class="dropdown-item">
                                <i class="fa-solid fa-pen me-2"></i> Edit Link
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('links.clicks', $link->short_code) }}" class="dropdown-item">
                                <i class="fa-solid fa-chart-simple me-2"></i> View Click Data
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST"
                                action="{{ route('links.delete', $link->short_code) }}"
                                class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fa-solid fa-trash me-2"></i> Delete Link
                                </button>
                            </form>
                        </li>
                </div>
            </div>

        </div>

        {{-- ROW BAWAH --}}
        <div class="row mt-2">
            <div class="col-12">

                <div class="text-muted small">
                    <a class="text-decoration-none text-muted small" href="{{ $link->destination_type === 'url'
                    ? $link->destination_url
                    : url($link->short_code) }}" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i>
                    {{ Str::limit($link->destination_url, 80) }}</a>
                </div>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <div class="text-muted small">
                            <i class="fa-solid fa-at me-1"></i>
                            {{ $link->user->email ?? '-' }}
                        </div>
                    @endif
                @endauth

                <div class="d-flex flex-wrap gap-3 text-muted small mt-2">
                    <span>
                        <a class="text-decoration-none text-info"
                        href="{{ route('links.clicks', $link->short_code) }}">
                        <i class="fa-solid fa-chart-simple me-1"></i>
                        Click data</a>
                    </span>
                    <span>
                        <i class="fa-regular fa-calendar me-1"></i>
                        {{ $link->created_at->format('M d, Y') }}
                    </span>
                    <span>
                        <i class="fa-solid fa-tag me-1"></i>
                        No tags
                    </span>
                </div>

            </div>
        </div>

    </div>
</div>
@empty
<div class="text-center text-muted py-5">
    Tidak ada data
</div>
@endforelse
<script>
document.getElementById("searchInput").addEventListener("keyup", function () {
    fetch("{{ route('links.search') }}?q=" + encodeURIComponent(this.value))
        .then(res => res.text())
        .then(html => document.getElementById("linkList").innerHTML = html);
});
</script>
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
