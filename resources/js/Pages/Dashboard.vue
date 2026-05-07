<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Header from '@/Components/Header.vue';
import { useTranslate } from '@/Composables/useTranslate';

defineOptions({ layout: AppLayout });

const t = useTranslate();

defineProps<{
    stats: { users: number; activeUsers: number; auditLogs: number };
}>();
</script>

<template>
    <Header :title="t('dashboard')" />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="stat bg-base-100 rounded-box border border-base-300">
            <div class="stat-title">{{ t('users') }}</div>
            <div class="stat-value">{{ stats.users }}</div>
        </div>
        <div class="stat bg-base-100 rounded-box border border-base-300">
            <div class="stat-title">{{ t('is_active') }}</div>
            <div class="stat-value text-success">{{ stats.activeUsers }}</div>
        </div>
        <div class="stat bg-base-100 rounded-box border border-base-300">
            <div class="stat-title">{{ t('audit_logs') }}</div>
            <div class="stat-value text-info">{{ stats.auditLogs }}</div>
        </div>
    </div>
</template>
