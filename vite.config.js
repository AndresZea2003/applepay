import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import basicSsl from '@vitejs/plugin-basic-ssl'
import fs from 'fs';

const host = 'applepay.test';

export default defineConfig({
    server: {
        host,
        hmr: { host },
        https: {
            key: fs.readFileSync(`./ssl/key.pem`),
            cert: fs.readFileSync(`./ssl/cert.pem`),
        },
    },
    plugins: [
        basicSsl(),
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            valetTls: 'applepay.test',
        }),
    ],
});

