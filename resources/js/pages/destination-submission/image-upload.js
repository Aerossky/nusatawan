// document.addEventListener('DOMContentLoaded', function() {

//         // Setup event handler untuk upload gambar
//         setupImageUpload();

//         // Setup event handler untuk form
//         setupFormSubmission();
// });

// // Variabel untuk menyimpan data
//     let selectedFiles = [];
// // Fungsi untuk setup upload gambar
//     function setupImageUpload() {
//         const imageInput = document.getElementById('images');

//         imageInput.addEventListener('change', function(event) {
//             const files = Array.from(event.target.files);
//             const errorElement = document.getElementById('error-images');

//             // Cek jumlah file
//             if (files.length + selectedFiles.length > 5) {
//                 errorElement.textContent =
//                     `Anda memilih ${files.length} foto, tetapi maksimal hanya boleh 5 foto`;
//                 errorElement.classList.remove('hidden');
//                 return;
//             } else {
//                 errorElement.classList.add('hidden');
//             }

//             // Tambahkan file baru ke array selectedFiles
//             const validFiles = files.filter(file => {
//                 return file.type.startsWith('image/');
//             });

//             selectedFiles = [...selectedFiles, ...validFiles];

//             // Jika lebih dari 5, batasi
//             if (selectedFiles.length > 5) {
//                 selectedFiles = selectedFiles.slice(0, 5);
//             }

//             updateImagePreview();
//             updateImageCounter();
//         });
//     }

//     // Fungsi untuk update counter gambar
//     function updateImageCounter() {
//         const counter = document.getElementById('images-counter');
//         counter.textContent = `${selectedFiles.length}/5 foto dipilih`;

//         // Update required status pada input
//         const imageInput = document.getElementById('images');
//         if (selectedFiles.length > 0) {
//             imageInput.removeAttribute('required');
//         } else {
//             imageInput.setAttribute('required', 'required');
//         }
//     }

//     // Fungsi untuk update preview gambar
//     function updateImagePreview() {
//         const preview = document.getElementById('image-preview');
//         preview.innerHTML = '';

//         selectedFiles.forEach((file, index) => {
//             const reader = new FileReader();
//             reader.onload = function(e) {
//                 const div = document.createElement('div');
//                 div.classList.add('image-preview-item');

//                 const img = document.createElement('img');
//                 img.src = e.target.result;

//                 const deleteBtn = document.createElement('button');
//                 deleteBtn.classList.add('delete-image');
//                 deleteBtn.setAttribute('type', 'button');
//                 deleteBtn.innerHTML = '×';
//                 deleteBtn.setAttribute('data-index', index);
//                 deleteBtn.addEventListener('click', function() {
//                     removeImage(parseInt(this.getAttribute('data-index')));
//                 });

//                 div.appendChild(img);
//                 div.appendChild(deleteBtn);
//                 preview.appendChild(div);
//             }
//             reader.readAsDataURL(file);
//         });
//     }

//     // Fungsi untuk hapus gambar
//     function removeImage(index) {
//         selectedFiles = selectedFiles.filter((_, i) => i !== index);
//         updateImagePreview();
//         updateImageCounter();

//         // Reset input file jika semua gambar dihapus
//         if (selectedFiles.length === 0) {
//             document.getElementById('images').value = '';
//         }
//     }

//     // Fungsi untuk setup submit form
//     function setupFormSubmission() {
//         const form = document.getElementById('destinationForm');

//         form.addEventListener('submit', function(e) {
//             e.preventDefault();

//             const formData = new FormData(this);

//             // Hapus file input images yang lama
//             formData.delete('images[]');

//             // Tambahkan file yang sudah dipilih
//             selectedFiles.forEach(file => {
//                 formData.append('images[]', file);
//             });

//             // Validasi
//             if (selectedFiles.length === 0) {
//                 const errorElement = document.getElementById('error-images');
//                 errorElement.textContent = 'Minimal 1 foto harus diupload';
//                 errorElement.classList.remove('hidden');
//                 return;
//             }

//             // Submit form
//             this.submit();
//         });
//     }

export default function initImageUpload() {
    // Image upload and preview functionality
    const imageInput = document.getElementById('images');
    const imagePreview = document.getElementById('image-preview');
    const imagesCounter = document.getElementById('images-counter');
    const errorImages = document.getElementById('error-images');
    const uploadArea = document.getElementById('upload-area');
    const clearImagesBtn = document.getElementById('clear-images');
    const MAX_FILES = 5;

    if (!imageInput || !imagePreview || !uploadArea) {
        return; // Exit if elements don't exist
    }

    // Drag and drop functionality
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => {
            uploadArea.classList.add('drag-over');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => {
            uploadArea.classList.remove('drag-over');
        });
    });

    uploadArea.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        imageInput.files = files;
        handleFiles(files);
    });

    // Handle selected files
    imageInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        if (files.length > MAX_FILES) {
            errorImages.classList.remove('hidden');
            imageInput.value = '';
            return;
        } else {
            errorImages.classList.add('hidden');
        }

        // Update counter
        imagesCounter.textContent = `${files.length}/${MAX_FILES} foto dipilih`;

        // Clear preview
        imagePreview.innerHTML = '';

        // Show clear button if files selected
        if (files.length > 0) {
            clearImagesBtn.classList.remove('hidden');
        } else {
            clearImagesBtn.classList.add('hidden');
        }

        // Create previews
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function(e) {
                const previewItem = document.createElement('div');
                previewItem.className =
                    'image-preview-item aspect-w-16 aspect-h-9 bg-gray-100 rounded-lg overflow-hidden';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-full object-cover';
                img.alt = `Preview ${i + 1}`;

                const deleteBtn = document.createElement('div');
                deleteBtn.className = 'delete-btn';
                deleteBtn.innerHTML = '<i class="fas fa-times"></i>';
                deleteBtn.setAttribute('data-index', i);

                previewItem.appendChild(img);
                previewItem.appendChild(deleteBtn);
                imagePreview.appendChild(previewItem);

                // Delete button functionality
                deleteBtn.addEventListener('click', function() {
                    // We can't directly modify the FileList, so we create a new input
                    const newFileInput = document.createElement('input');
                    newFileInput.type = 'file';
                    newFileInput.multiple = true;

                    // Create a new DataTransfer object
                    const dt = new DataTransfer();

                    // Add all files except the one to delete
                    for (let j = 0; j < files.length; j++) {
                        if (j !== parseInt(this.getAttribute('data-index'))) {
                            dt.items.add(files[j]);
                        }
                    }

                    // Set the new files to the input and trigger change event
                    imageInput.files = dt.files;
                    imageInput.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                });
            };

            reader.readAsDataURL(file);
        }
    }

    // Clear all images button
    if (clearImagesBtn) {
        clearImagesBtn.addEventListener('click', function() {
            imageInput.value = '';
            imagePreview.innerHTML = '';
            imagesCounter.textContent = `0/${MAX_FILES} foto dipilih`;
            this.classList.add('hidden');
        });
    }
}
