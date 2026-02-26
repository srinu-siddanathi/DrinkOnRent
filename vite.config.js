import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'node_modules/jquery-ui-dist/jquery-ui.min.css', 'node_modules/jquery-ui-dist/jquery-ui.min.js'],
            refresh: true,
        }),
    ],
});
