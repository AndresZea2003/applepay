import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import fs from 'fs';
import https from 'https';

export default defineConfig({
    server: {
        https: {
            key: fs.readFileSync('./applepay-privateKey.key'),
            cert: fs.readFileSync('./applepay.crt'),
        },
    },
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
