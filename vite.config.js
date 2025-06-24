import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss', // atau 'resources/css/app.css' jika tidak pakai Sass
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                quietDeps: true, // Mengurangi deprecation warnings dari dependencies
            }
        }
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    'admin-lte': ['admin-lte']
                }
            }
        }
    }
});
