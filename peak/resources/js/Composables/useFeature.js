import {usePage} from '@inertiajs/vue3'

export function useFeature() {
    const disabledFeatures = usePage().props.disabled_features || []

    function featureEnabled(feature) {
        return !disabledFeatures.includes(feature)
    }

    function featureDisabled(feature) {
        return disabledFeatures.includes(feature)
    }

    return {
        featureEnabled,
        featureDisabled
    }
}