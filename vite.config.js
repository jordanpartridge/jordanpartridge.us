import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    resolve: {
        conditions: ['module', 'browser', process.env.NODE_ENV || 'development'],
    },
    json: {
        stringify: 'auto',
        namedExports: true,
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/app.scss'],
            refresh: true,
        }),
    ],
});
