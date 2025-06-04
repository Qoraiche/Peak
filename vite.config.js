import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import path from "path";
import {defineConfig} from "vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/Themes/Breeze/theme.js',
                'peak/resources/js/admin.js',
            ],
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],

    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/js"),
            "@peak": path.resolve(__dirname, "peak/resources/js"),
            "@Breeze": path.resolve(__dirname, "resources/js/Themes/Breeze"),
            "ziggy": path.resolve(__dirname, "vendor/tightenco/ziggy"),
        },
    },

    build: {
        cssCodeSplit: true, // keep CSS code splitting (optional)
        target: 'es2018', // support modern browsers only; improve performance
        sourcemap: false, // disable for smaller build size (unless debugging)
        minify: 'esbuild', // faster than terser
        brotliSize: false, // disables brotli size calc for faster builds
        rollupOptions: {
            output: {
                // Let Vite handle chunking except vendor separation
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        if (id.includes('vue')) {
                            return 'vendor-vue';
                        }
                        if (id.includes('inertia')) {
                            return 'vendor-inertia';
                        }
                        if (id.includes('lodash')) {
                            return 'vendor-lodash';
                        }
                        if (id.includes('axios')) {
                            return 'vendor-axios';
                        }
                        return 'vendor-others'; // fallback for all other node_modules
                    }
                },
                chunkFileNames: 'assets/[name]-[hash].js',
                entryFileNames: 'assets/[name]-[hash].js',
                assetFileNames: 'assets/[name]-[hash].[ext]',
            },
        },
    },
});
