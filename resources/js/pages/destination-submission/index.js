// Import semua komponen
import './map';
import initTabNavigation from './tab-navigation';
import initFormValidation from './form-validation';
import initImageUpload from './image-upload';

document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi semua komponen
    initTabNavigation();
    initFormValidation();
    initImageUpload();
});
