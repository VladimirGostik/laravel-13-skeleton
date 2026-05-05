import { reactive, watch } from 'vue';
import { router } from '@inertiajs/vue3';

interface UseFiltersOptions<T extends Record<string, unknown>> {
    url: string;
    initialFilters: T;
    debounceMs?: number;
    searchKey?: keyof T & string;
    immediateKeys?: Array<keyof T & string>;
}

export function useFilters<T extends Record<string, unknown>>(opts: UseFiltersOptions<T>) {
    const filters = reactive({ ...opts.initialFilters }) as T;
    const debounceMs = opts.debounceMs ?? 350;
    let searchTimer: ReturnType<typeof setTimeout> | null = null;

    function go() {
        const payload: Record<string, unknown> = {};
        Object.entries(filters).forEach(([k, v]) => {
            if (v !== '' && v !== null && v !== undefined) {
                payload[k] = v;
            }
        });
        router.get(opts.url, payload as Record<string, string>, { preserveState: true, replace: true });
    }

    if (opts.searchKey) {
        watch(
            () => filters[opts.searchKey!],
            () => {
                if (searchTimer) clearTimeout(searchTimer);
                searchTimer = setTimeout(go, debounceMs);
            },
        );
    }

    if (opts.immediateKeys?.length) {
        for (const key of opts.immediateKeys) {
            watch(() => filters[key], () => go());
        }
    }

    function clearFilters() {
        Object.keys(filters).forEach((k) => {
            (filters as Record<string, unknown>)[k] = '';
        });
        go();
    }

    function setSort(column: string, current: string | null) {
        let next = column;
        if (current === column) next = `-${column}`;
        else if (current === `-${column}`) next = column;
        (filters as unknown as Record<string, unknown>)['sort'] = next;
        go();
    }

    function setPerPage(perPage: number) {
        (filters as unknown as Record<string, unknown>)['perPage'] = perPage;
        go();
    }

    return { filters, go, clearFilters, setSort, setPerPage };
}
