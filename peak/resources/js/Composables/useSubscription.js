import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";

export function useSubscription() {
    const page = usePage();

    const subscriptionObject = computed(() => page.props.subscription || {});

    const subscription = subscriptionObject.value?.subscription;
    const plan = subscriptionObject.value?.plan;
    const provider = subscriptionObject.value?.provider;

    return {subscription, plan, provider};
}
