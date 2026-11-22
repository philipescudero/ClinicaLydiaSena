import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            // Adicionamos a configuração do diretório de build
            publicDirectory: '../clinicalydiasena.com.br', 
            buildDirectory: 'build',
        }),
    ],
});