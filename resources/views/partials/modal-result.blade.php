<div class="modal fade" id="resultModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <div class="modal-header">
                <h5 class="modal-title">Shortlink Dibuat</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('shorten.update') }}">
                    @csrf
                    <input type="hidden" name="old_code" id="oldCode" value="{{ old('old_code') }}">
                    <input type="hidden" name="destination_url" id="originalUrlInput" value="{{ old('destination_url') }}">

                    <div class="mb-3">
                        <label class="form-label">Short Code</label>

                        <input
                            type="text"
                            id="shortCodeInput"
                            name="short_code"
                            class="form-control @error('short_code') is-invalid @enderror"
                            value="{{ old('short_code') }}"
                            placeholder="contoh: my-link"
                        >

                        @error('short_code')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <small class="text-secondary">
                            <em>*u can custom your short link</em>
                        </small>
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
                    @auth
                    @php
                        $editCode = (session('open_result_modal') && $errors->has('short_code'))
                            ? old('old_code')
                            : (session('short_result')['code'] ?? null);
                    @endphp

                    @if($editCode)
                    <a href="links/{{ $editCode }}/edit" class="btn btn-outline-secondary btn-sm ms-1">
                        <i class="fa-solid fa-gear me-2"></i> Other setting
                    </a>
                    @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
