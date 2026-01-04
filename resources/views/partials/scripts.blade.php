<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const modalEl = document.getElementById('resultModal');
    if (!modalEl) return;

    const shortCodeInput = document.getElementById('shortCodeInput');
    const oldCodeInput   = document.getElementById('oldCode');
    const urlInput       = document.getElementById('originalUrlInput');
    const previewLink    = document.getElementById('previewLink');

    @if(session()->has('short_result'))
        const result = @json(session('short_result'));

        shortCodeInput.value = result.code;
        oldCodeInput.value   = result.code;
        urlInput.value       = result.destination;

        previewLink.innerText = result.short_url;
        previewLink.href      = result.short_url;

        new bootstrap.Modal(modalEl).show();
    @endif

    @if(session('open_result_modal') && $errors->has('short_code'))
        // isi ulang dari old()
        shortCodeInput.value = "{{ old('short_code') }}";
        oldCodeInput.value   = "{{ old('old_code') }}";
        urlInput.value       = "{{ old('destination_url') }}";

        // preview tetap pakai old_code (link lama)
        if (oldCodeInput.value) {
            const link = `${window.location.origin}/${oldCodeInput.value}`;
            previewLink.innerText = link;
            previewLink.href      = link;
        }

        new bootstrap.Modal(modalEl).show();
    @endif

});
</script>
    
    <script>
// document.addEventListener("DOMContentLoaded", function () {

//     const dropArea  = document.getElementById('dropArea');
//     const fileInput = document.getElementById('fileInput');
//     const preview   = document.getElementById('preview');

//     if (dropArea && fileInput && preview) {

//         dropArea.addEventListener('click', () => fileInput.click());

//         dropArea.addEventListener('dragover', (e) => {
//             e.preventDefault();
//             dropArea.classList.add('bg-dark');
//         });

//         dropArea.addEventListener('dragleave', () => {
//             dropArea.classList.remove('bg-dark');
//         });

//         dropArea.addEventListener('drop', (e) => {
//             e.preventDefault();
//             dropArea.classList.remove('bg-dark');
//             fileInput.files = e.dataTransfer.files;
//             showPreview(fileInput.files[0]);
//         });

//         fileInput.addEventListener('change', () => {
//             if (fileInput.files.length > 0) {
//                 showPreview(fileInput.files[0]);
//             }
//         });

//         function showPreview(file) {
//             preview.innerHTML = '';

//             if (file.type.startsWith('image/')) {
//                 const img = document.createElement('img');
//                 img.src = URL.createObjectURL(file);
//                 img.className = "img-fluid rounded shadow mt-2";
//                 img.style.maxHeight = "250px";
//                 preview.appendChild(img);
//             } else {
//                 preview.innerHTML =
//                     `<p>File: ${file.name} (${Math.round(file.size / 1024)} KB)</p>`;
//             }
//         }
//     }
    
    const copyBtn = document.getElementById("copyBtn");
    const previewLink = document.getElementById("previewLink");

    if (copyBtn && previewLink) {
        copyBtn.addEventListener("click", function () {
            navigator.clipboard.writeText(previewLink.href).then(() => {
                copyBtn.innerHTML =
                    "<i class='fa-solid fa-clipboard-check me-2'></i>Copied!";
                setTimeout(() => {
                    copyBtn.innerHTML =
                        "<i class='fa-solid fa-copy me-2'></i> Copy link";
                }, 2000);
            });
        });
    }

    const shortCodeInput = document.getElementById("shortCodeInput");
    const saveBtn = document.getElementById("saveBtn");
    const previewUrl = document.getElementById("previewUrl");

    if (shortCodeInput) {
        shortCodeInput.addEventListener("input", function () {
            this.value = this.value.replace(/\s/g, "");
            if (saveBtn) saveBtn.style.display = "inline-block";
            if (previewUrl) previewUrl.style.display = "none";
        });
    }

});
</script>
