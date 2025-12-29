<div class="modal fade" id="showShareLink" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa-solid fa-share-nodes me-1"></i> Share Link
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="small mb-3 text-center">
                    Bagikan link berikut:<br>
                    <a id="shareLink" href="#" target="_blank" class="fw-bold"></a>
                </p>

                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    <a id="shareWhatsApp" target="_blank" class="btn btn-success">
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>
                    <a id="shareFacebook" target="_blank" class="btn btn-info text-white">
                        <i class="fa-brands fa-facebook"></i>
                    </a>
                    <a id="shareTwitter" target="_blank" class="btn btn-secondary">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                    <a id="shareInstagram" target="_blank" class="btn btn-primary">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
document.getElementById('showShareLink')
    .addEventListener('show.bs.modal', function (event) {

        const button = event.relatedTarget;
        const shortCode = button.getAttribute('data-share');
        const fullUrl = `${window.location.origin}/${shortCode}`;

        // tampilkan link utama
        const shareLink = document.getElementById('shareLink');
        shareLink.href = fullUrl;
        shareLink.textContent = fullUrl;

        // encode untuk share
        const encodedUrl = encodeURIComponent(fullUrl);
        const text = encodeURIComponent('Klik link yang saya share dari Cuma.Click');

        document.getElementById('shareWhatsApp').href =
            `https://wa.me/?text=${text}%0A${encodedUrl}`;

        document.getElementById('shareFacebook').href =
            `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;

        document.getElementById('shareTwitter').href =
            `https://twitter.com/intent/tweet?text=${text}&url=${encodedUrl}`;

        document.getElementById('shareInstagram').href =
            `https://www.instagram.com/share?url=${encodedUrl}`;
});
</script>

{{-- Modal QR --}}
<div class="modal fade" id="showBarcode" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-3">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa-solid fa-qrcode me-1"></i> QR Code
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="small mb-2">
                    Link:
                    <a id="barcodeLink" href="#" target="_blank"></a>
                </p>

                <canvas id="qr" class="mx-auto d-block"></canvas>

                <div class="mt-3">
                    <a id="downloadQR" class="btn btn-sm btn-primary" download>
                        <i class="fa-solid fa-download me-1"></i> Download QR
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>

<script>
let qr;

const qrModal = document.getElementById('showBarcode');

qrModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const code = button.getAttribute('data-barcode');

    const url = "{{ url('/') }}/" + code;

    // Update link text
    const linkEl = document.getElementById('barcodeLink');
    linkEl.href = url;
    linkEl.textContent = url;

    // Generate / Update QR
    if (!qr) {
        qr = new QRious({
            element: document.getElementById('qr'),
            size: 260,
            value: url
        });
    } else {
        qr.set({ value: url });
    }

    // Setup download button
    const downloadBtn = document.getElementById('downloadQR');
    const canvas = document.getElementById('qr');

    // tunggu canvas update
    setTimeout(() => {
        downloadBtn.href = canvas.toDataURL('image/png');
        downloadBtn.download = `qr-${code}.png`;
    }, 100);
});
</script>

<!-- Modal Add Tags -->
<!-- Modal Add Tags -->
<div class="modal fade" id="addTagsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">

            <!-- HEADER -->
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1">
                        <i class="fa-solid fa-tags me-1"></i> Add Tags
                    </h5>
                    <small class="text-muted">
                        <span id="modalBaseUrl">{{ url('/') }}/</span><strong id="modalShortCode"></strong>
                    </small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- FORM -->
            <form id="addTagsForm" method="POST">
                @csrf

                <div class="modal-body">

                    <label class="form-label">Tags</label>

                    <!-- Badge container -->
                    <div id="tagsContainer"
                        class="border rounded p-2 d-flex flex-wrap gap-2 mb-2 d-none"
                        style="min-height:42px"></div>


                    <!-- Input -->
                    <input type="text"
                        class="form-control"
                        id="tagsInput"
                        list="tagsSuggestionList"
                        autocomplete="off"
                        placeholder="Type tag and press Enter or , to add">

                    <datalist id="tagsSuggestionList"></datalist>


                    <!-- Hidden submit -->
                    <input type="hidden" name="tags" id="tagsHidden">

                    <div class="form-text">
                        Press <b>Enter</b> or <b>,</b> to add tag
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Save Tags
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
<script>
let tags = [];

const modal = document.getElementById('addTagsModal');
const form = document.getElementById('addTagsForm');
const tagsInput = document.getElementById('tagsInput');
const tagsContainer = document.getElementById('tagsContainer');
const tagsHidden = document.getElementById('tagsHidden');
const modalShortCode = document.getElementById('modalShortCode');

/* Render badge tags */
function renderTags() {
    tagsContainer.innerHTML = '';

    if (tags.length === 0) {
        tagsContainer.classList.add('d-none');
        tagsHidden.value = '';
        return;
    }

    tagsContainer.classList.remove('d-none');

    tags.forEach((tag, index) => {
        const badge = document.createElement('span');
        badge.className = 'badge bg-info text-light d-flex align-items-center';
        badge.innerHTML = `
            ${tag}
            <button type="button"
                    class="btn-close btn-close-white ms-2"
                    style="font-size:.6rem"></button>
        `;

        badge.querySelector('button').onclick = () => {
            tags.splice(index, 1);
            renderTags();
        };

        tagsContainer.appendChild(badge);
    });

    tagsHidden.value = tags.join(',');
}


/* Add tag via Enter / comma */
tagsInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' || e.key === ',') {
        e.preventDefault();

        let value = tagsInput.value
            .trim()
            .replace(',', '')
            .toLowerCase();

        if (value && !tags.includes(value)) {
            tags.push(value);
            renderTags();
        }

        tagsInput.value = '';
    }
});

/* Modal show */
modal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const shortCode = button.getAttribute('data-shortcode');

    modalShortCode.textContent = shortCode;
    form.action = `/links/${shortCode}/tags`;

    // Load existing tags
    fetch(`/links/${shortCode}/tags`)
        .then(res => res.json())
        .then(data => {
            tags = data;       // array of tag names
            renderTags();
        });
});

/* Reset ketika modal ditutup */
modal.addEventListener('hidden.bs.modal', function () {
    tags = [];
    renderTags();
    tagsInput.value = '';
});
</script>
<script>
const datalist = document.getElementById('tagsSuggestionList');
let suggestionTimeout = null;

tagsInput.addEventListener('input', function () {
    const q = this.value.trim();

    if (q.length < 1) {
        datalist.innerHTML = '';
        return;
    }

    clearTimeout(suggestionTimeout);

    suggestionTimeout = setTimeout(() => {
        fetch(`/tags/suggestions?q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(data => {
                datalist.innerHTML = '';
                data.forEach(tag => {
                    const option = document.createElement('option');
                    option.value = tag;
                    datalist.appendChild(option);
                });
            });
    }, 250); // debounce ringan
});
</script>


