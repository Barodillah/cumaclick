<!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
        const saveBtn = document.getElementById("saveBtn");
        const shortCodeInput = document.getElementById("shortCodeInput");
        const originalUrlInput = document.getElementById("originalUrlInput");
        const previewUrl = document.getElementById('previewUrl');
    
        // Awal: tombol disembunyikan
        saveBtn.style.display = "none";
    
        // Tampilkan tombol jika ada perubahan pada field
        [shortCodeInput, originalUrlInput].forEach(input => {
            input.addEventListener("input", function () {
                saveBtn.style.display = "inline-block";
                previewUrl.style.display = "none";
            });
        });
    
        // Sembunyikan lagi setiap kali modal dibuka ulang
        const modal = document.getElementById('resultModal');
        modal.addEventListener('shown.bs.modal', function () {
            saveBtn.style.display = "none";
        });
    });
    </script>
    
    <script>
      // Drag Drop Preview
      const dropArea = document.getElementById('dropArea');
      const fileInput = document.getElementById('fileInput');
      const preview = document.getElementById('preview');
    
      // Klik pada dropArea => buka file explorer
      dropArea.addEventListener('click', () => fileInput.click());
    
      // Drag over effect
      dropArea.addEventListener('dragover', (e) => { 
        e.preventDefault(); 
        dropArea.classList.add('bg-dark'); 
      });
    
      dropArea.addEventListener('dragleave', () => dropArea.classList.remove('bg-dark'));
    
      // Drop file
      dropArea.addEventListener('drop', (e) => {
        e.preventDefault(); 
        dropArea.classList.remove('bg-dark');
        fileInput.files = e.dataTransfer.files; 
        showPreview(fileInput.files[0]);
      });
    
      // Change (jika pilih manual via explorer)
      fileInput.addEventListener('change', () => { 
        if (fileInput.files.length > 0) showPreview(fileInput.files[0]); 
      });
    
      // Preview function
      function showPreview(file) {
        preview.innerHTML = '';
        if (file.type.startsWith('image/')) {
          const img = document.createElement('img');
          img.src = URL.createObjectURL(file);
          img.className = "img-fluid rounded shadow mt-2";
          img.style.maxHeight = "250px";
          preview.appendChild(img);
        } else {
          preview.innerHTML = `<p>File: ${file.name} (${Math.round(file.size / 1024)} KB)</p>`;
        }
      }
    </script>

  <script>
    // Drag Drop Preview
    // const dropArea = document.getElementById('dropArea');
    // const fileInput = document.getElementById('fileInput');
    // const preview = document.getElementById('preview');

    // dropArea.addEventListener('dragover', (e) => { e.preventDefault(); dropArea.classList.add('bg-dark'); });
    // dropArea.addEventListener('dragleave', () => dropArea.classList.remove('bg-dark'));
    // dropArea.addEventListener('drop', (e) => {
    //   e.preventDefault(); dropArea.classList.remove('bg-dark');
    //   fileInput.files = e.dataTransfer.files; showPreview(fileInput.files[0]);
    // });
    // fileInput.addEventListener('change', () => { if (fileInput.files.length > 0) showPreview(fileInput.files[0]); });
    // function showPreview(file) {
    //   preview.innerHTML = '';
    //   if (file.type.startsWith('image/')) {
    //     const img = document.createElement('img');
    //     img.src = URL.createObjectURL(file);
    //     img.className = "img-fluid rounded shadow mt-2";
    //     img.style.maxHeight = "250px";
    //     preview.appendChild(img);
    //   } else {
    //     preview.innerHTML = `<p>File: ${file.name} (${Math.round(file.size / 1024)} KB)</p>`;
    //   }
    // }

    // Copy to Clipboard
    document.getElementById("copyBtn").addEventListener("click", function() {
      const link = document.getElementById("previewLink").href;
      navigator.clipboard.writeText(link).then(() => {
        this.innerHTML = "<i class='fa-solid fa-clipboard-check me-2'></i>Copied!";
        setTimeout(() => { this.innerHTML = "<i class='fa-solid fa-copy me-2'></i> Copy link"; }, 2000);
      });
    });

    // Hapus spasi input
    document.getElementById("shortCodeInput").addEventListener("input", function () {
      this.value = this.value.replace(/\s/g, "");
    });

    // Show modal jika ada result dari process.php
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('short') && urlParams.has('url')) {
      const short = urlParams.get('short');
      const url = urlParams.get('url');
      document.getElementById('shortCodeInput').value = short;
      document.getElementById('oldCode').value = short;
      document.getElementById('originalUrlInput').value = url;
      document.getElementById('previewLink').innerText = window.location.origin + "/" + short;
      document.getElementById('previewLink').href = window.location.origin + "/" + short;
      new bootstrap.Modal(document.getElementById('resultModal')).show();
    }
  </script>