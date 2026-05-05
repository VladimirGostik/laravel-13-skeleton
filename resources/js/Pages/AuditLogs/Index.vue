<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Header from '@/Layouts/Header.vue';
import DataTable from '@/Layouts/DataTable.vue';
import { Link } from '@inertiajs/vue3';
import { useTranslate } from '@/Composables/useTranslate';
import { useFilters } from '@/Composables/useFilters';
import type { Paginator, TableColumn } from '@/types';

const t = useTranslate();

interface ActivityRow {
    id: number;
    log_name: string;
    description: string;
    event: string | null;
    subject_type: string | null;
    subject_id: string | null;
    causer_name: string | null;
    causer_email: string | null;
    created_at: string | null;
}

const props = defineProps<{
    logs: Paginator<ActivityRow>;
    filters: Record<string, string | number>;
}>();

const { filters, setSort, setPerPage } = useFilters({
    url: route('audit-logs.index'),
    initialFilters: {
        'filter[search]': (props.filters['filter[search]'] as string) ?? '',
        'filter[date_from]': (props.filters['filter[date_from]'] as string) ?? '',
        'filter[date_to]': (props.filters['filter[date_to]'] as string) ?? '',
        sort: (props.filters.sort as string) ?? '-created_at',
        perPage: Number(props.filters.perPage ?? 25),
    },
    searchKey: 'filter[search]',
    immediateKeys: ['filter[date_from]', 'filter[date_to]'],
});

const columns: TableColumn<ActivityRow>[] = [
    { key: 'created_at', label: t('created_at'), sortable: true },
    { key: 'log_name', label: t('event') },
    { key: 'description', label: t('description'), sortable: true },
    { key: 'subject_type', label: t('subject_type') },
    { key: 'causer_email', label: t('causer') },
    { key: 'id', label: '', align: 'right' },
];
</script>

<template>
    <AppLayout>
        <Header :title="t('audit_logs')" />

        <div class="card bg-base-100 border border-base-300 mb-4">
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input v-model="filters['filter[search]']" :placeholder="t('search')" class="input input-bordered w-full" />
                    <input v-model="filters['filter[date_from]']" type="date" class="input input-bordered w-full" />
                    <input v-model="filters['filter[date_to]']" type="date" class="input input-bordered w-full" />
                </div>
            </div>
        </div>

        <DataTable
            :columns="columns"
            :rows="logs"
            :sort="filters.sort as string"
            :per-page="Number(filters.perPage)"
            @update:sort="(c) => setSort(c, filters.sort as string)"
            @update:per-page="setPerPage"
        >
            <template #cell-id="{ row }">
                <Link :href="route('audit-logs.show', row.id)" class="btn btn-xs btn-ghost">{{ t('view') }}</Link>
            </template>
        </DataTable>
    </AppLayout>
</template>
