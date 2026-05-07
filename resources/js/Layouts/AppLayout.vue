<script setup lang="ts">
import { computed, onMounted, onBeforeUnmount, watch, reactive } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { useTranslate } from '@/Composables/useTranslate';
import type { ToastType } from '@/Composables/useToast';
import type { AuthUser, CanMap, FlashBag, LanguageOption } from '@/types';

interface SharedPageProps {
    auth: { user: AuthUser | null };
    languages: LanguageOption[];
    locale: string;
    can: CanMap;
    flash: FlashBag;
    [key: string]: unknown;
}

const t = useTranslate();
const page = usePage<SharedPageProps>();

interface NavItem {
    label: string;
    href: string;
    visible: boolean;
}

const navItems = computed<NavItem[]>(() => {
    const can = page.props.can ?? {};
    return [
        { label: t('dashboard'), href: route('dashboard'), visible: true },
        { label: t('users'), href: route('users.index'), visible: !!can.viewUsers },
        { label: t('roles'), href: route('roles.index'), visible: !!can.viewRoles },
        { label: t('audit_logs'), href: route('audit-logs.index'), visible: !!can.viewAuditLogs },
    ];
});

const user = computed(() => page.props.auth?.user ?? null);
const languages = computed(() => page.props.languages ?? []);
const currentLocale = computed(() => page.props.locale ?? 'sk');

interface Toast {
    id: number;
    message: string;
    type: ToastType;
}

const toasts = reactive<Toast[]>([]);
let toastSeq = 0;

function pushToast(message: string, type: ToastType) {
    const id = ++toastSeq;
    toasts.push({ id, message, type });
    setTimeout(() => {
        const idx = toasts.findIndex((t) => t.id === id);
        if (idx >= 0) toasts.splice(idx, 1);
    }, 4000);
}

function onAppToast(e: Event) {
    const detail = (e as CustomEvent).detail as { message: string; type: ToastType };
    pushToast(detail.message, detail.type);
}

watch(
    () => page.props.flash,
    (flash) => {
        if (!flash) return;
        if (flash.success) pushToast(flash.success, 'success');
        if (flash.error) pushToast(flash.error, 'error');
        if (flash.info) pushToast(flash.info, 'info');
        if (flash.status) pushToast(flash.status, 'info');
    },
    { deep: true, immediate: true },
);

onMounted(() => {
    window.addEventListener('app-toast', onAppToast as EventListener);
});

onBeforeUnmount(() => {
    window.removeEventListener('app-toast', onAppToast as EventListener);
});

function logout() {
    router.post(route('logout'));
}

function alertClass(type: ToastType) {
    return {
        success: 'alert-success',
        error: 'alert-error',
        info: 'alert-info',
        warning: 'alert-warning',
    }[type];
}
</script>

<template>
    <div class="drawer lg:drawer-open">
        <input id="app-drawer" type="checkbox" class="drawer-toggle" />

        <div class="drawer-content flex flex-col min-h-screen">
            <div class="navbar bg-base-100 border-b border-base-300 lg:hidden">
                <label for="app-drawer" class="btn btn-ghost drawer-button">☰</label>
                <span class="ml-2 font-semibold">{{ t('app_name') }}</span>
            </div>
            <main class="flex-1 p-4 lg:p-8">
                <slot />
            </main>
        </div>

        <div class="drawer-side z-30">
            <label for="app-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
            <aside class="bg-base-100 border-r border-base-300 w-72 min-h-screen flex flex-col">
                <div class="px-6 py-5 border-b border-base-300">
                    <Link :href="route('dashboard')" class="text-lg font-bold text-primary">
                        {{ t('app_name') }}
                    </Link>
                </div>

                <div v-if="user" class="px-6 py-4 border-b border-base-300">
                    <div class="text-sm font-semibold">{{ user.name }}</div>
                    <div class="text-xs opacity-70">{{ user.email }}</div>
                    <button class="btn btn-sm btn-ghost mt-3" type="button" @click="logout">
                        {{ t('logout') }}
                    </button>
                </div>

                <ul class="menu px-4 py-4 flex-1 gap-1">
                    <template v-for="item in navItems" :key="item.href">
                        <li v-if="item.visible">
                            <Link :href="item.href">{{ item.label }}</Link>
                        </li>
                    </template>

                    <li>
                        <details>
                            <summary>{{ t('profile_settings') }}</summary>
                            <ul>
                                <li><Link :href="route('profile.show')">{{ t('profile') }}</Link></li>
                            </ul>
                        </details>
                    </li>
                </ul>

                <div class="dropdown dropdown-top px-4 pb-4">
                    <label tabindex="0" class="btn btn-sm btn-ghost w-full justify-start">
                        🌐 {{ t('language') }}: {{ currentLocale.toUpperCase() }}
                    </label>
                    <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box shadow-md w-52">
                        <li v-for="lang in languages" :key="lang.code">
                            <Link :href="route('language.switch', { locale: lang.code })" method="get" as="button">
                                {{ lang.flag }} {{ lang.name }}
                            </Link>
                        </li>
                    </ul>
                </div>
            </aside>
        </div>

        <div class="toast toast-bottom toast-end z-50">
            <div v-for="toast in toasts" :key="toast.id" class="alert" :class="alertClass(toast.type)">
                <span>{{ toast.message }}</span>
            </div>
        </div>
    </div>
</template>
