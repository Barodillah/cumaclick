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

                <div class="@if($link->is_active) text-primary small @else text-muted small @endif">
                        {{ url($link->short_code) }}
                        <a href="javascript:void(0)">
                        <i class="@if($link->is_active) fa-regular  fa-copy copyBtn @else  @endif"
                        role="button"
                        data-link="{{ url($link->short_code) }}"></i>
                    </a> @if(!$link->is_active) <span class="badge bg-secondary">Inactive</span> @endif
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
                    @if($link->is_active)
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
                    @endif
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
                        {{ $link->click_count }} Click</a>
                    </span>
                    <span>
                        <i class="fa-regular fa-calendar me-1"></i>
                        {{ $link->created_at->format('M d, Y') }}
                    </span>
                    <span>
                        <a href=javascript:void(0)" class="text-decoration-none text-info"
                        data-bs-toggle="modal" data-bs-target="#addTagsModal"
                        data-shortcode="{{ $link->short_code }}">
                        <i class="fa-solid fa-tag me-1"></i>
                        @if($link->tags->count() > 0)
                            {{ $link->tags->count() }} tags
                        @else
                        No tags
                        @endif
                        </a>
                    </span>
                    @if($link->abuse_score > 0)
                    <span>
                        <i class="fa-solid fa-exclamation-triangle me-1 text-danger"></i>
                        {{ $link->abuse_score }} Abuse
                    </span>
                    @endif

                    @if($link->pin_code != null or $link->require_otp != 0)
                    <span>
                        <i class="fa-solid fa-lock me-1"></i>
                        @if($link->pin_code != null and $link->require_otp != 0)
                            PIN & OTP
                        @elseif($link->pin_code != null)
                            PIN
                        @elseif($link->require_otp != 0)
                            OTP
                        @endif
                    </span>
                    @endif

                    @if($link->max_click != null or $link->one_time != 0)
                    <span>
                        <i class="fa-solid fa-arrow-pointer me-1"></i>
                        @if($link->max_click != null)
                            Max {{ $link->max_click }}
                        @elseif($link->one_time != 0)
                            One-time
                        @endif
                    </span>
                    @endif

                    @if($link->expired_at != null
                    or $link->active_from != null
                    or $link->active_until != null)
                    <span>
                        <i class="fa-solid fa-clock me-1"></i>
                        @if($link->active_from != null)
                            From {{ $link->active_from->format('H:i M d, Y') }}
                        @endif
                        @if($link->active_until != null)
                            @if($link->active_from != null) - @endif
                            Until {{ $link->active_until->format('H:i M d, Y') }}
                        @endif
                        @if($link->expired_at != null)
                            @if($link->active_from != null or $link->active_until != null) - @endif
                            Expired {{ $link->expired_at->format('H:i M d, Y') }}
                        @endif
                    </span>
                    @endif
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
function fetchLinks() {
    const q = document.getElementById('searchInput').value;
    const startDate = document.getElementById('startDate')?.value || '';
    const endDate = document.getElementById('endDate')?.value || '';
    const type = document.getElementById('typeFilter')?.value || '';
    const status = document.getElementById('statusFilter')?.value || '';
    const tag = document.getElementById('hasTagsFilter')?.value || '';

    const params = new URLSearchParams({
        q, startDate, endDate, type, status, tag
    });

    fetch("{{ route('links.search') }}?" + params.toString())
        .then(res => res.text())
        .then(html => document.getElementById("linkList").innerHTML = html);
}

// Pencarian teks
document.getElementById("searchInput").addEventListener("keyup", fetchLinks);

// Filter tanggal (modal custom range)
document.querySelectorAll('.quick-range').forEach(button => {
    button.addEventListener('click', function() {
        const days = parseInt(this.getAttribute('data-days'));
        const end = new Date();
        let start;

        if(days === 1 && this.textContent.includes('hour')) {
            start = new Date(end.getTime() - 60 * 60 * 1000);
        } else if(days === 0) {
            start = new Date();
        } else {
            start = new Date();
            start.setDate(end.getDate() - days);
        }

        const formatDate = d => d.toISOString().split('T')[0];
        document.getElementById('startDate').value = formatDate(start);
        document.getElementById('endDate').value = formatDate(end);

        fetchLinks(); // langsung reload data
    });
});

// Filter tambahan (type/status)
document.getElementById('typeFilter')?.addEventListener('change', fetchLinks);
document.getElementById('statusFilter')?.addEventListener('change', fetchLinks);
document.getElementById('hasTagsFilter')
    ?.addEventListener('change', fetchLinks);

// Filter tanggal manual
document.getElementById('startDate')?.addEventListener('change', fetchLinks);
document.getElementById('endDate')?.addEventListener('change', fetchLinks);

// Clear filters
// Gunakan class untuk banyak tombol, misal class="clearFilters"
document.querySelectorAll('.clearFilters').forEach(btn => {
    btn.addEventListener('click', () => {
        // Reset semua input filter
        const searchInput = document.getElementById('searchInput');
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');
        const typeFilter = document.getElementById('typeFilter');
        const statusFilter = document.getElementById('statusFilter');
        const tagFilter = document.getElementById('hasTagsFilter');

        if(searchInput) searchInput.value = '';
        if(startDate) startDate.value = '';
        if(endDate) endDate.value = '';
        if(typeFilter) typeFilter.value = '';
        if(statusFilter) statusFilter.value = '';
        if(tagFilter) tagFilter.value = '';

        // Reload data tanpa filter
        fetchLinks();
    });
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
