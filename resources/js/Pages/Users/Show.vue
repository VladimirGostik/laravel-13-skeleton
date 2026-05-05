<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Header from '@/Layouts/Header.vue';
import { Link } from '@inertiajs/vue3';
import { useTranslate } from '@/Composables/useTranslate';

const t = useTranslate();

defineProps<{
    user: {
        id: number;
        name: string;
        email: string;
        role: string | null;
        is_active: boolean;
        locale: string;
        email_verified_at: string | null;
        created_at: string | null;
    };
}>();
</script>

<template>
    <AppLayout>
        <Header :title="user.name" :breadcrumbs="[{ label: t('users'), href: route('users.index') }, { label: user.name }]">
            <template #actions>
                <Link :href="route('users.edit', user.id)" class="btn btn-primary">{{ t('edit') }}</Link>
            </template>
        </Header>

        <div class="card bg-base-100 border border-base-300 max-w-2xl">
            <div class="card-body">
                <dl class="grid grid-cols-2 gap-y-3">
                    <dt class="opacity-60">{{ t('email') }}</dt><dd>{{ user.email }}</dd>
                    <dt class="opacity-60">{{ t('role') }}</dt><dd>{{ user.role ?? '—' }}</dd>
                    <dt class="opacity-60">{{ t('is_active') }}</dt>
                    <dd>
                        <span class="badge" :class="user.is_active ? 'badge-success' : 'badge-ghost'">
                            {{ user.is_active ? t('yes') : t('no') }}
                        </span>
                    </dd>
                    <dt class="opacity-60">{{ t('language') }}</dt><dd>{{ user.locale }}</dd>
                    <dt class="opacity-60">{{ t('created_at') }}</dt><dd>{{ user.created_at }}</dd>
                </dl>
            </div>
        </div>
    </AppLayout>
</template>
