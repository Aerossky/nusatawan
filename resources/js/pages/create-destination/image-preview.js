document.addEventListener('alpine:init', () => {
    Alpine.data('imageUploader', () => ({
        images: [],         // Array untuk menyimpan image dan preview
        error: '',          // Untuk menyimpan pesan error
        primaryIndex: null, // Index gambar utama
        dragOver: false,    // Efek drag and drop

        // Handle file upload via input
        handleFiles(event) {
            const files = Array.from(event.target.files);
            this.processFiles(files);
        },

        // Handle file upload via drag & drop
        handleDrop(event) {
            this.dragOver = false;
            const files = Array.from(event.dataTransfer.files);
            this.processFiles(files);
        },

        // Proses file yang diupload
        processFiles(files) {
            this.error = '';

            // Validasi
            if (!files || files.length === 0) return;

            // Validasi tipe dan ukuran file
            for (const file of files) {
                if (!file.type.startsWith('image/')) {
                    this.error = 'Semua file harus berupa gambar.';
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    this.error = 'Ukuran gambar maksimal 2MB.';
                    return;
                }
            }

            // Validasi jumlah maksimum file
            if ((this.images.length + files.length) > 5) {
                this.error = 'Maksimal 5 gambar yang diperbolehkan.';
                return;
            }

            // Tambahkan file ke preview
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.images.push({
                        file: file,
                        url: e.target.result
                    });

                    // Set gambar pertama sebagai utama jika belum ada
                    if (this.primaryIndex === null) {
                        this.primaryIndex = 0;
                    }
                };
                reader.readAsDataURL(file);
            });
        },

        // Hapus gambar
        removeImage(index) {
            this.images.splice(index, 1);

            // Update primary index
            if (this.primaryIndex === index) {
                this.primaryIndex = this.images.length > 0 ? 0 : null;
            } else if (this.primaryIndex > index) {
                this.primaryIndex--;
            }
        },

        // Set gambar utama
        setPrimary(index) {
            this.primaryIndex = index;
        }
    }));
});
