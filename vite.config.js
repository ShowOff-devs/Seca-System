import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '10.0.254.8', // your LAN IP
        port: 5174,
        cors: true,            // <--- Enable CORS
        strictPort: true,
        // Optional: allow all origins (for dev only)
        headers: {
            'Access-Control-Allow-Origin': '*',
        },
    },
});