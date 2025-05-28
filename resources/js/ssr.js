// ssr.js (Server-side rendering setup)
import {createSSRApp, h} from 'vue';
import {renderToString} from '@vue/server-renderer';
import {createInertiaApp} from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import {ZiggyVue} from '../../vendor/tightenco/ziggy';
import {__} from '@peak/Composables/useTranslate';
import dayjs from 'dayjs';
import mitt from 'mitt'; // Import mitt
import Toast from 'vue-toastification'; // Import Toast
import 'vue-toastification/dist/index.css'; // Import CSS

const theme = import.meta.env.VITE_APP_THEME || 'Breeze';
const appName = import.meta.env.VITE_APP_NAME || 'Peak';

const emitter = mitt(); // Initialize mitt

// Import all theme pages using Vite's glob
// Glob import to include all pages, but we'll filter out the Dashboard folder later
const pages = import.meta.glob('./Themes/**/Pages/**/*.vue');

// Filter out pages inside the 'Dashboard' folder
const filteredPages = Object.keys(pages).reduce((acc, path) => {
    // Exclude any page in the 'Dashboard' folder
    if (!path.includes('/Dashboard/')) {
        acc[path] = pages[path];
    }
    return acc;
}, {});

// Function to resolve page components dynamically based on the theme
function resolvePage(name) {
    const path = `./Themes/${theme}/Pages/${name}.vue`;
    const page = filteredPages[path];

    if (!page) {
        throw new Error(`[Inertia SSR] Page not found: ${path}`);
    }

    return page();
}

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - ${appName}`,
        resolve: resolvePage,
        setup({App, props, plugin}) {
            const app = createSSRApp({render: () => h(App, props)});

            app.use(plugin)
                .use(ZiggyVue, {
                    ...page.props.ziggy,
                    location: new URL(page.props.ziggy.location),
                })
                .provide('emitter', emitter)
                .provide('dayJS', dayjs);

            app.config.globalProperties.__ = __;

            // Toast is only available on the client-side
            if (typeof window !== 'undefined') {
                app.use(Toast, {
                    position: 'bottom-center',
                    timeout: 3000,
                });
            }

            return app;
        },
    })
);
