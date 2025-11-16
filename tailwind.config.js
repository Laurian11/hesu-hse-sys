import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    text: '#000000',
                    bg: '#ffffff',
                },
                secondary: {
                    text: '#6b6b6b',
                },
                border: {
                    DEFAULT: '#e0e0e0',
                },
            },
        },
    },

    plugins: [forms],
};
