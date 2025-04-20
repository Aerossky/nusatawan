import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                'resources/css/custom/preview.css',
                'resources/css/custom/map.css',
                'resources/js/app.js',
                // Destination Submission Admin
                'resources/js/pages/create-destination/editor.js',
                'resources/js/pages/create-destination/image-preview.js',
                // Destination Submission User
                'resources/js/pages/destination-submission/index.js',
                'resources/js/pages/destination-submission/map.js',


],
            refresh: true,
        }),
    ],
});
