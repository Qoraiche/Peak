import { inject } from 'vue'

export function useRoute() {
    const route = inject('route')

    if (!route) {
        throw new Error('`route` has not been properly injected. Did you forget to use Ziggy or register it?')
    }

    return route
}