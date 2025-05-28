import {usePage} from "@inertiajs/vue3";
import {computed} from "vue";

export function useLocale() {
    const page = usePage();

    const language = computed(() => page.props?.currentLanguageCollection || {});

    return {language};
}
