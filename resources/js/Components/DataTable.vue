<script setup lang="ts" generic="TRow extends object">
import type { Paginator, TableColumn } from '@/types';
import Pagination from '@/Components/Pagination.vue';
import { useTranslate } from '@/Composables/useTranslate';

const t = useTranslate();

defineProps<{
    columns: TableColumn<TRow>[];
    rows: Paginator<TRow>;
    sort?: string | null;
    perPage?: number;
}>();

const emit = defineEmits<{
    (e: 'update:sort', column: string): void;
    (e: 'update:perPage', value: number): void;
}>();

function clickSort(column: TableColumn<TRow>) {
    if (!column.sortable) return;
    emit('update:sort', column.key);
}

function sortIndicator(column: TableColumn<TRow>, sort?: string | null) {
    if (!column.sortable) return '';
    if (sort === column.key) return ' ▲';
    if (sort === `-${column.key}`) return ' ▼';
    return '';
}
</script>

<template>
    <div class="overflow-x-auto bg-base-100 rounded-box border border-base-300">
        <table class="table">
            <thead>
                <tr>
                    <th
                        v-for="col in columns"
                        :key="col.key"
                        :class="[col.class, col.sortable ? 'cursor-pointer select-none' : '']"
                        @click="clickSort(col)"
                    >
                        {{ col.label }}{{ sortIndicator(col, sort) }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="rows.data.length === 0">
                    <td :colspan="columns.length" class="text-center py-8 opacity-60">
                        {{ t('no_results') }}
                    </td>
                </tr>
                <tr v-for="(row, idx) in rows.data" :key="idx">
                    <td v-for="col in columns" :key="col.key" :class="col.class">
                        <slot :name="`cell-${col.key}`" :row="row">
                            {{ (row as Record<string, unknown>)[col.key as string] }}
                        </slot>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between mt-4 flex-wrap gap-4">
        <label class="flex items-center gap-2 text-sm">
            {{ t('per_page') }}:
            <select
                class="select select-bordered select-sm"
                :value="perPage ?? rows.per_page"
                @change="emit('update:perPage', Number(($event.target as HTMLSelectElement).value))"
            >
                <option :value="10">10</option>
                <option :value="25">25</option>
                <option :value="50">50</option>
                <option :value="100">100</option>
            </select>
        </label>
        <Pagination :paginator="rows" />
    </div>
</template>
