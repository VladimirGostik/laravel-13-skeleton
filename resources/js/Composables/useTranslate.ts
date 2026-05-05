import { usePage } from '@inertiajs/vue3';

export function useTranslate() {
    const page = usePage();
    return (key: string, fallback?: string) => {
        const translations = (page.props.translations ?? {}) as Record<string, string>;
        return translations[key] ?? fallback ?? key;
    };
}
