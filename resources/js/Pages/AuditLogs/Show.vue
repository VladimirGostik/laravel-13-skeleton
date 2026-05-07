<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Header from '@/Components/Header.vue';
import { useTranslate } from '@/Composables/useTranslate';

defineOptions({ layout: AppLayout });

const t = useTranslate();

defineProps<{
    log: {
        id: number;
        log_name: string;
        description: string;
        event: string | null;
        subject_type: string | null;
        subject_id: string | null;
        causer_name: string | null;
        causer_email: string | null;
        properties: Record<string, unknown>;
        created_at: string | null;
    };
}>();
</script>

<template>
    <Header :title="log.description" :breadcrumbs="[{ label: t('audit_logs'), href: route('audit-logs.index') }, { label: '#' + log.id }]" />

    <div class="card bg-base-100 border border-base-300 max-w-3xl">
        <div class="card-body space-y-3">
            <dl class="grid grid-cols-2 gap-y-2 text-sm">
                <dt class="opacity-60">{{ t('event') }}</dt><dd>{{ log.log_name }} / {{ log.event }}</dd>
                <dt class="opacity-60">{{ t('subject_type') }}</dt><dd>{{ log.subject_type ?? '—' }} #{{ log.subject_id ?? '—' }}</dd>
                <dt class="opacity-60">{{ t('causer') }}</dt><dd>{{ log.causer_email ?? '—' }}</dd>
                <dt class="opacity-60">{{ t('created_at') }}</dt><dd>{{ log.created_at }}</dd>
            </dl>
            <div>
                <h3 class="font-semibold mt-4 mb-2">Properties</h3>
                <pre class="bg-base-200 rounded-box p-4 overflow-x-auto text-sm">{{ JSON.stringify(log.properties, null, 2) }}</pre>
            </div>
        </div>
    </div>
</template>
