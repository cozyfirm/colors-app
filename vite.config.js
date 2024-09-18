import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/admin/admin.scss', 'resources/css/public/public.scss', 'resources/js/app.js', 'resources/js/admin/admin.js', 'resources/js/public/public.js'],
            refresh: true,
        }),
    ],
    resolve : {
        alias: {
            '$':'jQuery',
            '~font' : path.resolve('resources/assets/fonts')
        }
    },
});
