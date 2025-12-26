<div class="modal fade" id="resultModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content p-3">
            <div class="modal-header">
                <h5 class="modal-title">Shortlink Dibuat</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('shorten.update') }}">
                    @csrf
                    <input type="hidden" name="old_code" id="oldCode">
                    <input type="hidden" name="destination_url" id="originalUrlInput">

                    <div class="mb-3">
                        <label class="form-label">Short Code</label>
                        <input type="text" id="shortCodeInput" name="short_code" class="form-control">
                        <small class="text-secondary"><em>*u can custom your short link</em></small>
                    </div>

                    <button class="btn btn-primary" id="saveBtn">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Save
                    </button>
                </form>

                <div id="previewUrl" class="mt-3">
                    <hr>
                    <p>
                        Preview:
                        <a href="#" id="previewLink" target="_blank"></a>
                    </p>
                    <button class="btn btn-outline-secondary btn-sm" id="copyBtn">
                        <i class="fa-solid fa-copy me-2"></i> Copy link
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
