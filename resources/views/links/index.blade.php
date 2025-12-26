@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Daftar Links</h3>
        <a href="/" class="btn btn-primary">
            <i class="fa-solid fa-link me-1"></i> New Link
        </a>
    </div>

    @if(session('msg'))
        <div class="alert alert-{{ session('msg_type') ?? 'info' }} alert-dismissible fade show" role="alert">
            {{ session('msg') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Cari link...">
    </div>

    @if($links->isEmpty())
        <p class="text-muted text-center">Tidak ada link untuk ditampilkan.</p>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle" id="linkTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Short Code</th>
                        <th>Original URL</th>
                        <th>Catatan</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($links as $link)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $link->short_code }}</td>
                        <td>
                            <a href="{{ $link->destination_url }}" target="_blank" class="text-decoration-none">
                                {{ Str::limit($link->destination_url, 50) }}
                            </a>
                        </td>
                        <td>{{ Str::limit($link->note, 40) }}</td>
                        <td>
                            @if($link->expired_at && now()->greaterThan($link->expired_at))
                                <span class="badge bg-danger">Expired</span>
                            @else
                                <span class="badge bg-success">Aktif</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary copyBtn" data-link="{{ url($link->short_code) }}">
                                <i class="fa-solid fa-clipboard"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editNoteModal"
                                data-id="{{ $link->id }}" data-note="{{ $link->note }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#showBarcode"
                                data-id="{{ $link->id }}" data-barcode="{{ $link->short_code }}">
                                <i class="fa-solid fa-qrcode"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Modal Edit Note -->
<div class="modal fade" id="editNoteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="/links.updateNote" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Edit Catatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="noteId">
        <textarea name="note" id="noteText" rows="4" class="form-control"></textarea>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal QR -->
<div class="modal fade" id="showBarcode" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-3">
        <div class="modal-header">
            <h5 class="modal-title">QR Code</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <p>Link: <a id="barcodeLink" href="#" target="_blank"></a></p>
            <div id="qrcode" class="mx-auto"></div>
        </div>
        <div class="modal-footer">
            <a id="downloadBtn" href="#" class="btn btn-primary" download>
                <i class="fa-solid fa-download me-1"></i> Download
            </a>
        </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // Inisialisasi DataTable
    $('#linkTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[5,10,20,50,-1],[5,10,20,50,"Semua"]],
        order: [[0,'asc']],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_–_END_ dari _TOTAL_ data",
            zeroRecords: "Tidak ada hasil yang cocok",
            paginate: { first: "Awal", last: "Akhir", next: "›", previous: "‹" }
        }
    });

    // Copy link
    $('.copyBtn').click(function(){
        const link = $(this).data('link');
        navigator.clipboard.writeText(link).then(() => {
            $(this).html('<i class="fa-solid fa-clipboard-check text-success"></i>');
            setTimeout(() => { $(this).html('<i class="fa-solid fa-clipboard"></i>'); }, 1200);
        });
    });

    // Modal edit note
    $('#editNoteModal').on('show.bs.modal', function(event){
        const button = $(event.relatedTarget);
        $('#noteId').val(button.data('id'));
        $('#noteText').val(button.data('note'));
    });

    // Modal QR code
    $('#showBarcode').on('show.bs.modal', function(event){
        const button = $(event.relatedTarget);
        const code = button.data('barcode');
        const link = "{{ url('/') }}/" + code;
        $('#barcodeLink').attr('href', link).text(link);
        $('#qrcode').empty();
        new QRCode(document.getElementById("qrcode"), {
            text: link,
            width: 200,
            height: 200,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    });
});
</script>
@endpush
