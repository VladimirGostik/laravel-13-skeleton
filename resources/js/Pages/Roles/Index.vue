<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Header from '@/Layouts/Header.vue';
import DataTable from '@/Layouts/DataTable.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { Link } from '@inertiajs/vue3';
import { useTranslate } from '@/Composables/useTranslate';
import { useDeleteConfirm } from '@/Composables/useDeleteConfirm';
import type { Paginator, TableColumn } from '@/types';

const t = useTranslate();

interface RoleRow {
    id: number;
    name: string;
    users_count: number;
    permissions_count: number;
    is_system: boolean;
    can: { view: boolean; edit: boolean; delete: boolean };
}

defineProps<{ roles: Paginator<RoleRow> }>();

const del = useDeleteConfirm<RoleRow>({
    resolveUrl: (r) => route('roles.destroy', r.id),
});

const columns: TableColumn<RoleRow>[] = [
    { key: 'name', label: t('name'), sortable: true },
    { key: 'users_count', label: t('users_count') },
    { key: 'permissions_count', label: t('permission_count') },
    { key: 'is_system', label: t('system_role') },
    { key: 'id', label: t('actions'), align: 'right' },
];
</script>

<template>
    <AppLayout>
        <Header :title="t('roles')">
            <template #actions>
                <Link :href="route('roles.create')" class="btn btn-primary">{{ t('create_role') }}</Link>
            </template>
        </Header>

        <DataTable :columns="columns" :rows="roles">
            <template #cell-is_system="{ row }">
                <span v-if="row.is_system" class="badge badge-warning">{{ t('yes') }}</span>
                <span v-else class="opacity-50">—</span>
            </template>
            <template #cell-id="{ row }">
                <div class="flex justify-end gap-1">
                    <Link v-if="row.can.edit" :href="route('roles.edit', row.id)" class="btn btn-xs btn-ghost">{{ t('edit') }}</Link>
                    <button v-if="row.can.delete" class="btn btn-xs btn-error" type="button" @click="del.ask(row)">{{ t('delete') }}</button>
                </div>
            </template>
        </DataTable>

        <ConfirmDeleteModal
            :open="del.state.open"
            :description="del.state.target?.name"
            @confirm="del.confirm"
            @cancel="del.cancel"
        />
    </AppLayout>
</template>
