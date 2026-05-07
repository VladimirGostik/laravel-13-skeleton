<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Header from '@/Components/Header.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { useTranslate } from '@/Composables/useTranslate';
import { computed } from 'vue';

defineOptions({ layout: AppLayout });

const t = useTranslate();
const page = usePage();
const languages = computed(
    () =>
        (page.props as unknown as { languages: Array<{ code: string; name: string; flag: string }> })
            .languages ?? [],
);

const props = defineProps<{
    user: { id: string; name: string; email: string; locale: string };
}>();

const profile = useForm({
    name: props.user.name,
    email: props.user.email,
    locale: props.user.locale,
});

const password = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

function saveProfile() {
    profile.put(route('profile.update'));
}

function savePassword() {
    password.put(route('profile.password'), {
        onSuccess: () => password.reset(),
    });
}
</script>

<template>
    <Header :title="t('profile')" />

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <h2 class="card-title">{{ t('profile_settings') }}</h2>
                    <form class="space-y-4" @submit.prevent="saveProfile">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">{{ t('name') }}</legend>
                            <input v-model="profile.name" class="input input-bordered w-full" :class="{ 'input-error': profile.errors.name }" />
                            <p v-if="profile.errors.name" class="text-error text-sm">{{ profile.errors.name }}</p>
                        </fieldset>
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">{{ t('email') }}</legend>
                            <input v-model="profile.email" type="email" class="input input-bordered w-full" :class="{ 'input-error': profile.errors.email }" />
                            <p v-if="profile.errors.email" class="text-error text-sm">{{ profile.errors.email }}</p>
                        </fieldset>
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">{{ t('language') }}</legend>
                            <select v-model="profile.locale" class="select select-bordered w-full">
                                <option v-for="l in languages" :key="l.code" :value="l.code">{{ l.flag }} {{ l.name }}</option>
                            </select>
                        </fieldset>
                        <button type="submit" class="btn btn-primary" :disabled="profile.processing">
                            <span v-if="profile.processing" class="loading loading-spinner loading-xs"></span>
                            {{ t('save') }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <h2 class="card-title">{{ t('change_password') }}</h2>
                    <form class="space-y-4" @submit.prevent="savePassword">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">{{ t('current_password') }}</legend>
                            <input v-model="password.current_password" type="password" class="input input-bordered w-full" :class="{ 'input-error': password.errors.current_password }" />
                            <p v-if="password.errors.current_password" class="text-error text-sm">{{ password.errors.current_password }}</p>
                        </fieldset>
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">{{ t('new_password') }}</legend>
                            <input v-model="password.password" type="password" class="input input-bordered w-full" :class="{ 'input-error': password.errors.password }" />
                            <p v-if="password.errors.password" class="text-error text-sm">{{ password.errors.password }}</p>
                        </fieldset>
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">{{ t('password_confirmation') }}</legend>
                            <input v-model="password.password_confirmation" type="password" class="input input-bordered w-full" />
                        </fieldset>
                        <button type="submit" class="btn btn-primary" :disabled="password.processing">
                            <span v-if="password.processing" class="loading loading-spinner loading-xs"></span>
                            {{ t('save') }}
                        </button>
                    </form>
                </div>
            </div>
    </div>
</template>
