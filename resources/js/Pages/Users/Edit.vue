<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Header from '@/Components/Header.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { useTranslate } from '@/Composables/useTranslate';

defineOptions({ layout: AppLayout });

const t = useTranslate();

const props = defineProps<{
    user: { id: string; name: string; email: string; role: string | null; is_active: boolean };
    roles: string[];
}>();

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    role: props.user.role ?? 'user',
    is_active: props.user.is_active,
});

function submit() {
    form.put(route('users.update', props.user.id));
}
</script>

<template>
    <Header :title="t('edit_user')" :breadcrumbs="[{ label: t('users'), href: route('users.index') }, { label: user.name }]" />

    <div class="card bg-base-100 border border-base-300 max-w-2xl">
        <div class="card-body">
            <form class="space-y-4" @submit.prevent="submit">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">{{ t('name') }}</legend>
                    <input v-model="form.name" class="input input-bordered w-full" :class="{ 'input-error': form.errors.name }" />
                    <p v-if="form.errors.name" class="text-error text-sm">{{ form.errors.name }}</p>
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">{{ t('email') }}</legend>
                    <input v-model="form.email" type="email" class="input input-bordered w-full" :class="{ 'input-error': form.errors.email }" />
                    <p v-if="form.errors.email" class="text-error text-sm">{{ form.errors.email }}</p>
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">{{ t('role') }}</legend>
                    <select v-model="form.role" class="select select-bordered w-full">
                        <option v-for="r in roles" :key="r" :value="r">{{ r }}</option>
                    </select>
                </fieldset>
                <label class="label cursor-pointer justify-start gap-2">
                    <input v-model="form.is_active" type="checkbox" class="checkbox checkbox-sm" />
                    <span class="label-text">{{ t('is_active') }}</span>
                </label>
                <div class="flex justify-end gap-2">
                    <Link :href="route('users.index')" class="btn btn-ghost">{{ t('cancel') }}</Link>
                    <button type="submit" class="btn btn-primary" :disabled="form.processing">
                        <span v-if="form.processing" class="loading loading-spinner loading-xs"></span>
                        {{ t('save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
