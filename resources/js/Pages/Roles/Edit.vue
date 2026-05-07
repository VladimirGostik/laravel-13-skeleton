<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Header from '@/Components/Header.vue';
import PermissionManager from '@/Components/PermissionManager.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { useTranslate } from '@/Composables/useTranslate';

defineOptions({ layout: AppLayout });

const t = useTranslate();

const props = defineProps<{
    role: { id: string; name: string; is_system: boolean; permissions: string[] };
    permissions: string[];
}>();

const form = useForm<{ name: string; permissions: string[] }>({
    name: props.role.name,
    permissions: [...props.role.permissions],
});

function submit() {
    form.put(route('roles.update', props.role.id));
}
</script>

<template>
    <Header :title="t('edit_role')" :breadcrumbs="[{ label: t('roles'), href: route('roles.index') }, { label: role.name }]" />

    <div class="card bg-base-100 border border-base-300">
        <div class="card-body">
            <form class="space-y-4" @submit.prevent="submit">
                <fieldset class="fieldset max-w-md">
                    <legend class="fieldset-legend">{{ t('name') }}</legend>
                    <input v-model="form.name" :disabled="role.is_system" class="input input-bordered w-full" :class="{ 'input-error': form.errors.name }" />
                    <p v-if="form.errors.name" class="text-error text-sm">{{ form.errors.name }}</p>
                </fieldset>

                <h3 class="font-semibold mt-4">{{ t('permissions') }}</h3>
                <PermissionManager v-model="form.permissions" :permissions="permissions" />

                <div class="flex justify-end gap-2 pt-4">
                    <Link :href="route('roles.index')" class="btn btn-ghost">{{ t('cancel') }}</Link>
                    <button type="submit" class="btn btn-primary" :disabled="form.processing">
                        <span v-if="form.processing" class="loading loading-spinner loading-xs"></span>
                        {{ t('save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
