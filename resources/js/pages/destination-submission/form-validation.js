export default function initFormValidation() {
    // Character counter for description
    const descriptionField = document.getElementById('description');
    const charCount = document.getElementById('char-count');

    if (descriptionField && charCount) {
        descriptionField.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;

            if (count < 100) {
                charCount.classList.add('text-red-500');
                charCount.classList.remove('text-gray-500');
            } else {
                charCount.classList.remove('text-red-500');
                charCount.classList.add('text-gray-500');
            }
        });

        // Trigger initial count if there's content
        if (descriptionField.value) {
            descriptionField.dispatchEvent(new Event('input'));
        }
    }

    // Basic info validation
    function validateBasicInfo() {
        const placeName = document.getElementById('place_name').value;
        const category = document.getElementById('category_id').value;
        const duration = document.getElementById('time_minutes').value;
        const visitTime = document.getElementById('best_visit_time').value;
        const description = document.getElementById('description').value;

        let valid = true;
        let errorMessages = [];

        if (!placeName) {
            valid = false;
            errorMessages.push('Nama destinasi harus diisi');
            document.getElementById('place_name').classList.add('border-red-500');
        } else {
            document.getElementById('place_name').classList.remove('border-red-500');
        }

        if (!category) {
            valid = false;
            errorMessages.push('Kategori harus dipilih');
            document.getElementById('category_id').classList.add('border-red-500');
        } else {
            document.getElementById('category_id').classList.remove('border-red-500');
        }

        if (!duration) {
            valid = false;
            errorMessages.push('Durasi kunjungan harus diisi');
            document.getElementById('time_minutes').classList.add('border-red-500');
        } else {
            document.getElementById('time_minutes').classList.remove('border-red-500');
        }

        if (!visitTime) {
            valid = false;
            errorMessages.push('Waktu terbaik untuk berkunjung harus diisi');
            document.getElementById('best_visit_time').classList.add('border-red-500');
        } else {
            document.getElementById('best_visit_time').classList.remove('border-red-500');
        }

        if (!description) {
            valid = false;
            errorMessages.push('Deskripsi harus diisi');
            document.getElementById('description').classList.add('border-red-500');
        } else if (description.length < 100) {
            valid = false;
            errorMessages.push('Deskripsi minimal 100 karakter');
            document.getElementById('description').classList.add('border-red-500');
        } else {
            document.getElementById('description').classList.remove('border-red-500');
        }

        // Display errors if any
        if (!valid) {
            let errorHTML =
                '<div class="bg-red-50 text-red-500 p-3 rounded-lg mb-5 border-l-4 border-red-500 text-sm">' +
                '<p class="font-medium mb-2">Mohon perbaiki kesalahan berikut:</p>' +
                '<ul class="list-disc pl-5 space-y-1">';

            errorMessages.forEach(msg => {
                errorHTML += `<li>${msg}</li>`;
            });

            errorHTML += '</ul></div>';

            // Insert error message before the form content
            const tabContent = document.getElementById('tab-1');
            const existingError = tabContent.querySelector('.bg-red-50');
            if (existingError) {
                existingError.remove();
            }

            tabContent.insertAdjacentHTML('afterbegin', errorHTML);

            // Scroll to error message
            tabContent.querySelector('.bg-red-50').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        } else {
            // Remove error message if it exists
            const tabContent = document.getElementById('tab-1');
            const existingError = tabContent.querySelector('.bg-red-50');
            if (existingError) {
                existingError.remove();
            }
        }

        return valid;
    }

    // Location validation
    function validateLocation() {
        const city = document.getElementById('administrative_area').value;
        const province = document.getElementById('province').value;

        let valid = true;
        let errorMessages = [];

        if (!city) {
            valid = false;
            errorMessages.push('Kota/Kabupaten harus diisi');
            document.getElementById('administrative_area').classList.add('border-red-500');
        } else {
            document.getElementById('administrative_area').classList.remove('border-red-500');
        }

        if (!province) {
            valid = false;
            errorMessages.push('Provinsi harus diisi');
            document.getElementById('province').classList.add('border-red-500');
        } else {
            document.getElementById('province').classList.remove('border-red-500');
        }

        // Display errors if any
        if (!valid) {
            let errorHTML =
                '<div class="bg-red-50 text-red-500 p-3 rounded-lg mb-5 border-l-4 border-red-500 text-sm">' +
                '<p class="font-medium mb-2">Mohon perbaiki kesalahan berikut:</p>' +
                '<ul class="list-disc pl-5 space-y-1">';

            errorMessages.forEach(msg => {
                errorHTML += `<li>${msg}</li>`;
            });

            errorHTML += '</ul></div>';

            // Insert error message before the form content
            const tabContent = document.getElementById('tab-2');
            const existingError = tabContent.querySelector('.bg-red-50');
            if (existingError) {
                existingError.remove();
            }

            tabContent.insertAdjacentHTML('afterbegin', errorHTML);

            // Scroll to error message
            tabContent.querySelector('.bg-red-50').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        } else {
            // Remove error message if it exists
            const tabContent = document.getElementById('tab-2');
            const existingError = tabContent.querySelector('.bg-red-50');
            if (existingError) {
                existingError.remove();
            }
        }

        return valid;
    }

    // Form submit validation
    const destinationForm = document.getElementById('destinationForm');
    if (destinationForm) {
        destinationForm.addEventListener('submit', function(e) {
            const isValid = validateBasicInfo() && validateLocation();

            // Check if at least one image is selected
            const images = document.getElementById('images').files;
            if (images.length === 0) {
                e.preventDefault();

                // Switch to photos tab
                window.switchTab(3, 3);

                // Show error message
                const errorHTML =
                    '<div class="bg-red-50 text-red-500 p-3 rounded-lg mb-5 border-l-4 border-red-500 text-sm">' +
                    '<p class="font-medium mb-2">Mohon perbaiki kesalahan berikut:</p>' +
                    '<ul class="list-disc pl-5 space-y-1">' +
                    '<li>Minimal 1 foto destinasi harus diunggah</li>' +
                    '</ul></div>';

                const tabContent = document.getElementById('tab-3');
                const existingError = tabContent.querySelector('.bg-red-50');
                if (existingError) {
                    existingError.remove();
                }

                tabContent.insertAdjacentHTML('afterbegin', errorHTML);
                tabContent.querySelector('.bg-red-50').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });

                return false;
            }

            if (!isValid) {
                e.preventDefault();
                return false;
            }

            // Show loading state on submit button
            document.getElementById('submit-form').innerHTML =
                '<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim...';
            document.getElementById('submit-form').disabled = true;
        });
    }

    // Expose validation functions globally
    window.validateBasicInfo = validateBasicInfo;
    window.validateLocation = validateLocation;
}
