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
            colors: {
                'psico-pink': '#F3D1D7',      // Rosa do fundo do menu
                'psico-pink-dark': '#E2B1B9', // Rosa do botão ativo (Pacientes)
                'psico-text': '#726366',      // Cor do texto
            },
        },
    },

    plugins: [forms],
};
