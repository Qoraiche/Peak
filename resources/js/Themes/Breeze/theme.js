import "./css/theme.css";
import "./bootstrap";
import {createInertiaApp} from "@inertiajs/vue3";
import Editor from "@tinymce/tinymce-vue";
import "@vuepic/vue-datepicker/dist/main.css";
import VueBottomSheet from "@webzlodimir/vue-bottom-sheet";
import "@webzlodimir/vue-bottom-sheet/dist/style.css";
import dayjs from "dayjs";
import FloatingVue from "floating-vue";
import "floating-vue/dist/style.css";
import {resolvePageComponent} from "laravel-vite-plugin/inertia-helpers";
import mitt from "mitt"; // Import mitt
import Swal from "sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
import {createApp, h} from "vue";
import VueLazyload from "vue-lazyload";
import {LoadingPlugin} from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";
import Multiselect from "vue-multiselect";
import VueTelInput from "vue-tel-input";
import "vue-tel-input/vue-tel-input.css";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import {PerfectScrollbarPlugin} from "vue3-perfect-scrollbar";
import "vue3-perfect-scrollbar/style.css";
import {ZiggyVue} from "../../../../vendor/tightenco/ziggy";
import {__} from "@peak/Composables/useTranslate";

import {ColorPicker} from "vue3-colorpicker";
import "vue3-colorpicker/style.css";

const emitter = mitt(); // Initialize mitt

window.swal = Swal.mixin({
    confirmButtonColor: '#3B82F6' // Tailwind's bg-blue-500
});

const appName = import.meta.env.VITE_APP_NAME || "Peak";

function resolvePageTitle(title) {
    const titleSeparator = window.peak.title_separator || '|';
    return `${title} ${titleSeparator} ${appName}`;
}

function resolvePageComponentByPath(name) {
    return resolveThemePageComponent(name);
}

const pages = import.meta.glob('./Pages/**/*.vue');

function resolveThemePageComponent(name) {
    // The key used in pages must match exactly, so:
    const pagePath = `./Pages/${name}.vue`;

    if (pages[pagePath]) {
        return resolvePageComponent(pagePath, pages);
    }

    // fallback if page not found
    throw new Error(`Page not found: ${pagePath}`);
}

function setupApp({el, App, props, plugin}) {
    const app = createApp({render: () => h(App, props)});

    app.use(plugin)
        .use(PerfectScrollbarPlugin)
        .provide("emitter", emitter)
        .component("Multiselect", Multiselect)
        .use(VueTelInput)
        .use(FloatingVue)
        .use(Toast, {position: "bottom-center"})
        .use(LoadingPlugin)
        .component("ColorPicker", ColorPicker)
        .component("VueBottomSheet", VueBottomSheet)
        .use(VueLazyload)
        .provide("dayJS", dayjs)
        .component("TinyEditor", Editor)
        .use(ZiggyVue);

    // Global helper
    app.config.globalProperties.__ = __;

    app.mount(el);
}

createInertiaApp({
    title: resolvePageTitle,
    resolve: resolvePageComponentByPath,
    setup: setupApp,
    progress: {
        color: "#1a79ff",
        includeCSS: true,
        showSpinner: true,
    },
});
