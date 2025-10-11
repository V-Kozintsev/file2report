import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        {
            name: 'startup-message',
            configureServer() {
              console.log('Vite dev сервер запущен!');
            },
        },
    ],
    server: {
        host: '127.0.0.1',
        port: 5173,
        hmr: {
          host: 'localhost',
          port: 5173,
        }
    }
});
