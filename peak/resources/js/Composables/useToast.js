export async function useToastification() {
    if (typeof window !== 'undefined') {
        const ToastPkg = await import('vue-toastification');
        return ToastPkg.useToast?.() ?? ToastPkg.default?.useToast?.();
    }
    return null;
}