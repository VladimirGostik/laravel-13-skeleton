<script setup lang="ts" generic="T">
import { Link } from '@inertiajs/vue3';
import type { Paginator } from '@/types';

defineProps<{ paginator: Paginator<T> }>();
</script>

<template>
    <div class="flex flex-wrap gap-1">
        <template v-for="(link, i) in paginator.links" :key="i">
            <Link
                v-if="link.url"
                :href="link.url"
                class="btn btn-sm"
                :class="link.active ? 'btn-primary' : 'btn-ghost'"
                preserve-state
                preserve-scroll
                v-html="link.label"
            />
            <span
                v-else
                class="btn btn-sm btn-disabled"
                v-html="link.label"
            />
        </template>
    </div>
</template>
