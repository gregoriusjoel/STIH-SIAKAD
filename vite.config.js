import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ mode }) => {
    // Read .env so we don't hard-code dev IP addresses in the repo. Set
    // VITE_APP_ORIGIN (e.g. http://localhost:8000) and VITE_HMR_HOST in your
    // local .env to override.
    const env = loadEnv(mode, process.cwd(), '');
    const appOrigin = env.VITE_APP_ORIGIN || 'http://localhost:8000';
    const hmrHost = env.VITE_HMR_HOST || 'localhost';

    return {
        plugins: [
            laravel({
                input: [
                    'resources/css/app.css',
                    'resources/js/app.js',
                    // Per-page lazy entries (opt-in via @vite([...]) di blade)
                    'resources/js/select2.js',
                    'resources/js/fullcalendar.js',
                    'resources/js/charts.js',
                    'resources/js/tom-select.js',
                    'resources/js/cropper.js',
                    'resources/js/html-to-image.js',
                    'resources/js/pdfjs.js',
                    'resources/js/tinymce.js',
                    'resources/js/ckeditor.js',
                ],
                refresh: true,
            }),
            tailwindcss(),
        ],
        server: {
            host: '0.0.0.0',
            cors: true,
            headers: {
                'Access-Control-Allow-Origin': appOrigin,
            },
            hmr: {
                host: hmrHost,
                protocol: 'ws',
            },
            watch: {
                ignored: ['**/storage/framework/views/**'],
            },
        },
    };
});
