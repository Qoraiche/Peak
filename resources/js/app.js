import "../css/app.css";
import "./bootstrap";
import {createInertiaApp} from "@inertiajs/vue3";
import Editor from "@tinymce/tinymce-vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import VueBottomSheet from "@webzlodimir/vue-bottom-sheet";
import "@webzlodimir/vue-bottom-sheet/dist/style.css";
import ApexCharts from "apexcharts";
import dayjs from "dayjs";
import FloatingVue from "floating-vue";
import "floating-vue/dist/style.css";
import {resolvePageComponent} from "laravel-vite-plugin/inertia-helpers";
import mitt from "mitt"; // Import mitt
import Swal from "sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
import {createApp, h} from "vue";
import vueFilePond from "vue-filepond";
import VueLazyload from "vue-lazyload";
import {LoadingPlugin} from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";
import Multiselect from "vue-multiselect";
import VueTelInput from "vue-tel-input";
import "vue-tel-input/vue-tel-input.css";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import VueApexCharts from "vue3-apexcharts";
import {PerfectScrollbarPlugin} from "vue3-perfect-scrollbar";
import "vue3-perfect-scrollbar/style.css";
import {ZiggyVue} from "../../vendor/tightenco/ziggy";
import "../css/packages/vue-multiselect.min.css";
import {__} from "@peak/Composables/useTranslate";

import {ColorPicker} from "vue3-colorpicker";
import "vue3-colorpicker/style.css";

// Import FilePond styles
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css";
import "filepond/dist/filepond.min.css";

// Import FilePond plugins
import FilePondPluginFileValidateSize from "filepond-plugin-file-validate-size";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";

const FilePond = vueFilePond(
    FilePondPluginImagePreview,
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize
);

const emitter = mitt(); // Initialize mitt
window.swal = Swal.mixin({
    confirmButtonColor: '#3B82F6' // Tailwind's bg-blue-500
});

const appName = import.meta.env.VITE_APP_NAME || "Peak";
const appPages = import.meta.glob("./Pages/**/*.vue");
const peakPages = import.meta.glob("/peak/resources/js/Pages/**/*.vue", {
    eager: true,
});

function resolvePageTitle(title) {
    const titleSeparator = window.peak.title_separator || '|';
    return `${title} ${titleSeparator} ${appName}`;
}

function resolvePageComponentByPath(name) {
    // Resolve admin
    if (name.startsWith("Admin/")) {
        return resolveAdminPageComponent(name);
    }

    return resolveThemePageComponent(name);
}

function resolveAdminPageComponent(name) {
    const customPath = `./Pages/${name}.vue`;
    const peakPath = `/peak/resources/js/Pages/${name}.vue`;

    if (appPages[customPath]) {
        return resolvePageComponent(customPath, appPages);
    }

    if (peakPages[peakPath]) {
        return peakPages[peakPath];
    }

    return null;
}

function resolveThemePageComponent(name) {
    const theme = import.meta.env.VITE_APP_THEME || 'Breeze';
    return resolvePageComponent(
        `./Themes/${theme}/Pages/${name}.vue`,
        import.meta.glob('./Themes/**/Pages/**/*.vue')
    );
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
        .use(VueApexCharts)
        .component("ColorPicker", ColorPicker)
        .component("VueDatePicker", VueDatePicker)
        .component("VueBottomSheet", VueBottomSheet)
        .component("FilePond", FilePond)
        .use(VueLazyload)
        .provide("dayJS", dayjs)
        .component("TinyEditor", Editor)
        .use(ZiggyVue);

    // Global helper
    app.config.globalProperties.__ = __;
    app.config.globalProperties.$apexcharts = ApexCharts;

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
