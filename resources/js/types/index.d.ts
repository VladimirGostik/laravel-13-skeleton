export interface Paginator<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
    links: Array<{ url: string | null; label: string; active: boolean }>;
    path: string;
}

export interface SelectOption {
    value: string | number;
    label: string;
}

export interface Breadcrumb {
    label: string;
    href?: string;
}

export interface TableColumn<TRow = unknown> {
    key: keyof TRow & string;
    label: string;
    sortable?: boolean;
    align?: 'left' | 'center' | 'right';
    class?: string;
}

export interface AuthUser {
    id: number;
    name: string;
    email: string;
}

export interface LanguageOption {
    code: string;
    name: string;
    flag: string;
}

export interface FlashBag {
    success?: string | null;
    error?: string | null;
    info?: string | null;
    status?: string | null;
}

export interface CanMap {
    viewUsers?: boolean;
    viewRoles?: boolean;
    viewAuditLogs?: boolean;
    editGlobalSettings?: boolean;
}

declare module '@inertiajs/core' {
    interface PageProps {
        translations: Record<string, string>;
        canResetPassword: boolean;
        flash: FlashBag;
        auth: { user: AuthUser | null };
        can: CanMap;
        locale: string;
        languages: LanguageOption[];
    }
}

declare module 'vue' {
    interface ComponentCustomProperties {
        route: (
            name: string,
            params?: string | number | Record<string, unknown> | Array<unknown>,
        ) => string;
    }
}

declare global {
    const route: (
        name: string,
        params?: string | number | Record<string, unknown> | Array<unknown>,
    ) => string;
}

export {};
