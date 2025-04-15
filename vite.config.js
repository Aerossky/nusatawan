import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/pages/create-destination/editor.js','resources/js/pages/create-destination/image-preview.js', 'resources/js/pages/destination-submission/map.js', 'resources/js/pages/destination-submission/image-upload.js'],
            refresh: true,
        }),
    ],
});
