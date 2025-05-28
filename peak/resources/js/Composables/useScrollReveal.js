import { onMounted } from 'vue'

export function useScrollReveal(options = {}) {
    onMounted(async () => {
        const ScrollReveal = (await import('scrollreveal')).default

        ScrollReveal().reveal('.sr-fade-up', {
            distance: '30px',
            origin: 'bottom',
            duration: 600,
            easing: 'ease',
            interval: 100,
            ...options,
        })
    })
}
