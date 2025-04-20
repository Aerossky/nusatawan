document.addEventListener('DOMContentLoaded', function() {

        // Setup event handler untuk upload gambar
        setupImageUpload();

        // Setup event handler untuk form
        setupFormSubmission();
});

// Variabel untuk menyimpan data
    let selectedFiles = [];
// Fungsi untuk setup upload gambar
    function setupImageUpload() {
        const imageInput = document.getElementById('images');

        imageInput.addEventListener('change', function(event) {
            const files = Array.from(event.target.files);
            const errorElement = document.getElementById('error-images');

            // Cek jumlah file
            if (files.length + selectedFiles.length > 5) {
                errorElement.textContent =
                    `Anda memilih ${files.length} foto, tetapi maksimal hanya boleh 5 foto`;
                errorElement.classList.remove('hidden');
                return;
            } else {
                errorElement.classList.add('hidden');
            }

            // Tambahkan file baru ke array selectedFiles
            const validFiles = files.filter(file => {
                return file.type.startsWith('image/');
            });

            selectedFiles = [...selectedFiles, ...validFiles];

            // Jika lebih dari 5, batasi
            if (selectedFiles.length > 5) {
                selectedFiles = selectedFiles.slice(0, 5);
            }

            updateImagePreview();
            updateImageCounter();
        });
    }

    // Fungsi untuk update counter gambar
    function updateImageCounter() {
        const counter = document.getElementById('images-counter');
        counter.textContent = `${selectedFiles.length}/5 foto dipilih`;

        // Update required status pada input
        const imageInput = document.getElementById('images');
        if (selectedFiles.length > 0) {
            imageInput.removeAttribute('required');
        } else {
            imageInput.setAttribute('required', 'required');
        }
    }

    // Fungsi untuk update preview gambar
    function updateImagePreview() {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.classList.add('image-preview-item');

                const img = document.createElement('img');
                img.src = e.target.result;

                const deleteBtn = document.createElement('button');
                deleteBtn.classList.add('delete-image');
                deleteBtn.setAttribute('type', 'button');
                deleteBtn.innerHTML = '×';
                deleteBtn.setAttribute('data-index', index);
                deleteBtn.addEventListener('click', function() {
                    removeImage(parseInt(this.getAttribute('data-index')));
                });

                div.appendChild(img);
                div.appendChild(deleteBtn);
                preview.appendChild(div);
            }
            reader.readAsDataURL(file);
        });
    }

    // Fungsi untuk hapus gambar
    function removeImage(index) {
        selectedFiles = selectedFiles.filter((_, i) => i !== index);
        updateImagePreview();
        updateImageCounter();

        // Reset input file jika semua gambar dihapus
        if (selectedFiles.length === 0) {
            document.getElementById('images').value = '';
        }
    }

    // Fungsi untuk setup submit form
    function setupFormSubmission() {
        const form = document.getElementById('destinationForm');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            // Hapus file input images yang lama
            formData.delete('images[]');

            // Tambahkan file yang sudah dipilih
            selectedFiles.forEach(file => {
                formData.append('images[]', file);
            });

            // Validasi
            if (selectedFiles.length === 0) {
                const errorElement = document.getElementById('error-images');
                errorElement.textContent = 'Minimal 1 foto harus diupload';
                errorElement.classList.remove('hidden');
                return;
            }

            // Submit form
            this.submit();
        });
    }
