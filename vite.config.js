import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/binance.js',
                'resources/js/tradingview.js',
                'resources/js/code-mirror.js',
            ],
            refresh: true,
        }),
    ],
});
