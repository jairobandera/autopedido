import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/Administrador/productos/crear-producto.css',
                'resources/js/Administrador/productos/crear-producto.js',
                'resources/css/Administrador/productos/editar-producto.css',
                'resources/js/Administrador/productos/editar-producto.js',
                'resources/css/Administrador/productos/deshabilitar-producto.css',
                'resources/js/Administrador/productos/deshabilitar-producto.js',
            ],
            refresh: true,
        }),
    ],
});