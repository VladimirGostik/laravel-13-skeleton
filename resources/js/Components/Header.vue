<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { Breadcrumb } from '@/types';

defineProps<{
    title: string;
    breadcrumbs?: Breadcrumb[];
}>();
</script>

<template>
    <header class="mb-6">
        <nav v-if="breadcrumbs?.length" class="text-sm breadcrumbs mb-2">
            <ul>
                <li v-for="(crumb, i) in breadcrumbs" :key="i">
                    <Link v-if="crumb.href" :href="crumb.href">{{ crumb.label }}</Link>
                    <span v-else>{{ crumb.label }}</span>
                </li>
            </ul>
        </nav>
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-2xl font-bold">{{ title }}</h1>
            <div class="flex gap-2">
                <slot name="actions" />
            </div>
        </div>
    </header>
</template>
