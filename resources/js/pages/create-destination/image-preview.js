document.addEventListener('DOMContentLoaded', function () {
    const ImagePreviewManager = {
        files: [],
        primaryIndex: null,
        maxFiles: 5,
        maxTotalSizeMB: 5,
        fileInput: document.getElementById('image'),
        previewContainer: document.getElementById('image-preview'),
        primaryImageInput: document.getElementById('primaryImage'),

        init() {
            this.fileInput?.addEventListener('change', this.handleFileSelection.bind(this));
        },

        async handleFileSelection() {
            const newFiles = Array.from(this.fileInput.files);

            const combinedFiles = this.files.concat(newFiles);

            if (combinedFiles.length > this.maxFiles) {
                this.showError(`Maksimal ${this.maxFiles} gambar diperbolehkan.`);
                this.resetInput();
                return;
            }

            const compressedFiles = await Promise.all(
                combinedFiles.map(file => this.compressImage(file, 2))
            );

            const totalSizeMB = compressedFiles.reduce((sum, file) => sum + file.size, 0) / (1024 * 1024);
            if (totalSizeMB > this.maxTotalSizeMB) {
                this.showError(`Total ukuran gambar terlalu besar. Maksimal ${this.maxTotalSizeMB} MB, sekarang ${totalSizeMB.toFixed(2)} MB.`);
                this.resetInput();
                return;
            }

            this.files = compressedFiles;
            if (this.primaryIndex === null && this.files.length > 0) {
                this.primaryIndex = 0;
            }

            // Set ulang input file (biar disubmit juga ke Laravel)
            const dt = new DataTransfer();
            this.files.forEach(file => dt.items.add(file));
            this.fileInput.files = dt.files;

            this.updatePrimaryImageInput();
            this.renderPreviews();
        },

        async compressImage(file, maxSizeMB = 2, initialQuality = 0.8) {
            const readFile = (file) => new Promise((resolve) => {
                const reader = new FileReader();
                reader.onload = (e) => resolve(e.target.result);
                reader.readAsDataURL(file);
            });

            const dataUrl = await readFile(file);
            const img = await new Promise((resolve) => {
                const image = new Image();
                image.onload = () => resolve(image);
                image.src = dataUrl;
            });

            const canvas = document.createElement('canvas');
            const MAX_WIDTH = 1920;
            const scale = Math.min(1, MAX_WIDTH / img.width);
            canvas.width = img.width * scale;
            canvas.height = img.height * scale;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

            let quality = initialQuality;
            let blob;

            while (quality > 0.3) {
                const dataUrlCompressed = canvas.toDataURL('image/jpeg', quality);
                const byteString = atob(dataUrlCompressed.split(',')[1]);
                const mimeString = dataUrlCompressed.split(',')[0].split(':')[1].split(';')[0];
                const ab = new ArrayBuffer(byteString.length);
                const ia = new Uint8Array(ab);
                for (let i = 0; i < byteString.length; i++) {
                    ia[i] = byteString.charCodeAt(i);
                }
                blob = new Blob([ab], { type: mimeString });

                if (blob.size / (1024 * 1024) < maxSizeMB) break;
                quality -= 0.05;
            }

            return new File([blob], file.name, { type: blob.type });
        },

        renderPreviews() {
            this.previewContainer.innerHTML = '';

            if (this.files.length === 0) {
                this.previewContainer.innerHTML = '<p class="text-gray-500 italic">Tidak ada gambar dipilih</p>';
                return;
            }

            this.files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => this.createPreviewCard(e.target.result, index);
                reader.readAsDataURL(file);
            });
        },

        createPreviewCard(imageSrc, index) {
            const card = document.createElement('div');
            card.className = 'relative border rounded overflow-hidden shadow-sm';
            if (this.primaryIndex === index) {
                card.classList.add('primary-image');
            }

            card.innerHTML = `
                <div class="relative">
                    <img src="${imageSrc}" class="w-full h-32 object-cover">
                    ${this.primaryIndex === index
                        ? '<div class="absolute top-0 right-0 bg-indigo-600 text-white px-2 py-1 text-xs">Primary</div>'
                        : ''}
                </div>
                <div class="p-2 bg-white">
                    <div class="flex justify-between gap-2">
                        ${this.primaryIndex !== index
                            ? `<button type="button" onclick="ImagePreviewManager.setPrimary(${index})" class="text-xs bg-indigo-500 text-white px-2 py-1 rounded flex-1 hover:bg-indigo-600">Set Primary</button>`
                            : `<button type="button" disabled class="text-xs bg-indigo-300 text-white px-2 py-1 rounded flex-1 cursor-not-allowed">Primary</button>`}
                        <button type="button" onclick="ImagePreviewManager.removeImage(${index})" class="text-xs bg-red-500 text-white px-2 py-1 rounded flex-1 hover:bg-red-600">Delete</button>
                    </div>
                </div>
            `;
            this.previewContainer.appendChild(card);
        },

        setPrimary(index) {
            this.primaryIndex = index;
            this.updatePrimaryImageInput();
            this.renderPreviews();
        },

        removeImage(index) {
            this.files.splice(index, 1);

            const dt = new DataTransfer();
            this.files.forEach(file => dt.items.add(file));
            this.fileInput.files = dt.files;

            if (this.primaryIndex === index) {
                this.primaryIndex = this.files.length > 0 ? 0 : null;
            } else if (this.primaryIndex > index) {
                this.primaryIndex--;
            }

            this.updatePrimaryImageInput();
            this.renderPreviews();
        },

        updatePrimaryImageInput() {
            this.primaryImageInput.value = this.primaryIndex !== null ? this.primaryIndex : '';
        },

        showError(message) {
            this.previewContainer.innerHTML = `<p class="text-red-500 italic">${message}</p>`;
        },

        resetInput() {
            this.fileInput.value = '';
            this.files = [];
            this.primaryIndex = null;
            this.updatePrimaryImageInput();
        }
    };

    ImagePreviewManager.init();
    window.ImagePreviewManager = ImagePreviewManager;
});
