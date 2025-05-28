import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import path from "path";
import {defineConfig} from "vite";

export default defineConfig({
    plugins: [
        laravel({

            input: [
                'peak/resources/js/admin.js',
                'resources/js/Themes/Breeze/theme.js',
                'resources/js/Themes/PeekyPok/theme.js',
            ],

            ssr: "resources/js/ssr.js",
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

    ssr: {
        noExternal: ['@inertiajs/server'],
    },

    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/js"),
            "@peak": path.resolve(__dirname, "peak/resources/js"),
            "@Breeze": path.resolve(__dirname, "resources/js/Themes/Breeze"), // theme alias
            "@PeekyPok": path.resolve(__dirname, "resources/js/Themes/PeekyPok"), // theme alias
            "ziggy": path.resolve(__dirname, "vendor/tightenco/ziggy"),
        },
    },
});
