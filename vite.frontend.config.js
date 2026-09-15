import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
    build: {
        outDir:       'public/assets',
        emptyOutDir:  false,
        cssCodeSplit: false,
        rollupOptions: {
            input: {
                main: resolve(process.cwd(), 'resources/js/frontend.js'),
            },
            output: {
                entryFileNames: 'main.js',
                chunkFileNames: 'chunks/[name].js',
                assetFileNames: (info) => info.name?.endsWith('.css') ? 'main.css' : '[name][extname]',
            },
        },
    },
    css: {
        postcss: './postcss.config.js',
    },
});
