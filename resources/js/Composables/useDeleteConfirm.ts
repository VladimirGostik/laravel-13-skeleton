import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';

interface DeleteState<T> {
    open: boolean;
    target: T | null;
}

interface UseDeleteConfirmOpts<T> {
    resolveUrl: (target: T) => string;
    getTitle?: (target: T) => string;
    getDescription?: (target: T) => string;
    method?: 'delete' | 'post';
}

export function useDeleteConfirm<T>(opts: UseDeleteConfirmOpts<T>) {
    const state = reactive<DeleteState<T>>({ open: false, target: null }) as DeleteState<T>;

    function ask(target: T) {
        state.target = target;
        state.open = true;
    }

    function cancel() {
        state.open = false;
        state.target = null;
    }

    function confirm() {
        if (!state.target) return;
        const method = opts.method ?? 'delete';
        const url = opts.resolveUrl(state.target);
        router[method](url, undefined, {
            preserveScroll: true,
            onFinish: () => cancel(),
        });
    }

    return { state, ask, cancel, confirm };
}
