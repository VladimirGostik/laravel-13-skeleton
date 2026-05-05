<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { useTranslate } from '@/Composables/useTranslate';

const t = useTranslate();

const props = defineProps<{ token: string; email?: string | null }>();

const form = useForm({
    token: props.token,
    email: props.email ?? '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-base-200 p-4">
        <div class="card w-full max-w-md bg-base-100 shadow-md">
            <div class="card-body">
                <h1 class="card-title text-2xl mb-4">{{ t('reset_password') }}</h1>

                <form class="space-y-4" @submit.prevent="submit">
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">{{ t('email') }}</legend>
                        <input v-model="form.email" type="email" class="input input-bordered w-full" :class="{ 'input-error': form.errors.email }" required />
                        <p v-if="form.errors.email" class="text-error text-sm mt-1">{{ form.errors.email }}</p>
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">{{ t('new_password') }}</legend>
                        <input v-model="form.password" type="password" class="input input-bordered w-full" :class="{ 'input-error': form.errors.password }" required />
                        <p v-if="form.errors.password" class="text-error text-sm mt-1">{{ form.errors.password }}</p>
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">{{ t('password_confirmation') }}</legend>
                        <input v-model="form.password_confirmation" type="password" class="input input-bordered w-full" required />
                    </fieldset>

                    <button type="submit" class="btn btn-primary w-full" :disabled="form.processing">
                        <span v-if="form.processing" class="loading loading-spinner loading-xs"></span>
                        {{ t('reset_password') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
