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
