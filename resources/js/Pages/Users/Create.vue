<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Header from '@/Layouts/Header.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { useTranslate } from '@/Composables/useTranslate';

const t = useTranslate();

defineProps<{ roles: string[] }>();

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'user',
    is_active: true,
});

function submit() {
    form.post(route('users.store'));
}
</script>

<template>
    <AppLayout>
        <Header :title="t('create_user')" :breadcrumbs="[{ label: t('users'), href: route('users.index') }, { label: t('create_user') }]" />

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
                        <legend class="fieldset-legend">{{ t('password') }}</legend>
                        <input v-model="form.password" type="password" class="input input-bordered w-full" :class="{ 'input-error': form.errors.password }" />
                        <p v-if="form.errors.password" class="text-error text-sm">{{ form.errors.password }}</p>
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">{{ t('password_confirmation') }}</legend>
                        <input v-model="form.password_confirmation" type="password" class="input input-bordered w-full" />
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
                            {{ t('create') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
