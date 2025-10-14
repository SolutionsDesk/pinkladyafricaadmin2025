import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // Add this entire server configuration block
    server: {
        host: '0.0.0.0', // This makes Vite listen on all available network interfaces inside the container
        hmr: {
            host: 'localhost', // This tells the browser to connect to localhost for HMR
        },
        watch: {
            usePolling: true, // This is crucial for detecting file changes inside Docker
        },
    },
});
