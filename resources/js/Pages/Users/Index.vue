<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Header from '@/Layouts/Header.vue';
import DataTable from '@/Layouts/DataTable.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { Link } from '@inertiajs/vue3';
import { useTranslate } from '@/Composables/useTranslate';
import { useFilters } from '@/Composables/useFilters';
import { useDeleteConfirm } from '@/Composables/useDeleteConfirm';
import type { Paginator, TableColumn } from '@/types';

const t = useTranslate();

interface UserRow {
    id: number;
    name: string;
    email: string;
    role: string | null;
    is_active: boolean;
    created_at: string | null;
    can: { view: boolean; edit: boolean; delete: boolean };
}

const props = defineProps<{
    users: Paginator<UserRow>;
    filters: Record<string, string | number>;
    roles: string[];
}>();

const { filters, setSort, setPerPage } = useFilters({
    url: route('users.index'),
    initialFilters: {
        'filter[search]': (props.filters['filter[search]'] as string) ?? '',
        'filter[role]': (props.filters['filter[role]'] as string) ?? '',
        'filter[is_active]': (props.filters['filter[is_active]'] as string) ?? '',
        sort: (props.filters.sort as string) ?? '',
        perPage: Number(props.filters.perPage ?? 25),
    },
    searchKey: 'filter[search]',
    immediateKeys: ['filter[role]', 'filter[is_active]'],
});

const del = useDeleteConfirm<UserRow>({
    resolveUrl: (u) => route('users.destroy', u.id),
    getDescription: (u) => `${u.name} (${u.email})`,
});

const columns: TableColumn<UserRow>[] = [
    { key: 'name', label: t('name'), sortable: true },
    { key: 'email', label: t('email'), sortable: true },
    { key: 'role', label: t('role') },
    { key: 'is_active', label: t('is_active') },
    { key: 'created_at', label: t('created_at'), sortable: true },
    { key: 'id', label: t('actions'), align: 'right' },
];
</script>

<template>
    <AppLayout>
        <Header :title="t('users')">
            <template #actions>
                <Link :href="route('users.create')" class="btn btn-primary">{{ t('create_user') }}</Link>
            </template>
        </Header>

        <div class="card bg-base-100 border border-base-300 mb-4">
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input v-model="filters['filter[search]']" :placeholder="t('search')" class="input input-bordered w-full" />
                    <select v-model="filters['filter[role]']" class="select select-bordered w-full">
                        <option value="">{{ t('all_roles') }}</option>
                        <option v-for="r in roles" :key="r" :value="r">{{ r }}</option>
                    </select>
                    <select v-model="filters['filter[is_active]']" class="select select-bordered w-full">
                        <option value="">—</option>
                        <option value="1">{{ t('yes') }}</option>
                        <option value="0">{{ t('no') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <DataTable
            :columns="columns"
            :rows="users"
            :sort="filters.sort as string"
            :per-page="Number(filters.perPage)"
            @update:sort="(c) => setSort(c, filters.sort as string)"
            @update:per-page="setPerPage"
        >
            <template #cell-is_active="{ row }">
                <span class="badge" :class="row.is_active ? 'badge-success' : 'badge-ghost'">
                    {{ row.is_active ? t('yes') : t('no') }}
                </span>
            </template>
            <template #cell-id="{ row }">
                <div class="flex justify-end gap-1">
                    <Link v-if="row.can.view" :href="route('users.show', row.id)" class="btn btn-xs btn-ghost">{{ t('view') }}</Link>
                    <Link v-if="row.can.edit" :href="route('users.edit', row.id)" class="btn btn-xs btn-ghost">{{ t('edit') }}</Link>
                    <button v-if="row.can.delete" class="btn btn-xs btn-error" type="button" @click="del.ask(row)">{{ t('delete') }}</button>
                </div>
            </template>
        </DataTable>

        <ConfirmDeleteModal
            :open="del.state.open"
            :description="del.state.target ? `${del.state.target.name} (${del.state.target.email})` : ''"
            @confirm="del.confirm"
            @cancel="del.cancel"
        />
    </AppLayout>
</template>
