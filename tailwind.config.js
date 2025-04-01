import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        "./node_modules/flowbite/**/*.js"
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors:{
                primary: "#2D9CDB", // Biru utama
                secondary: "#0C43BA", // Biru sekunder
                background: "#F2F2F2", // Background utama
            }
        },
    },
    plugins: [
        require('flowbite/plugin')
    ],
    darkMode: 'false',
};
