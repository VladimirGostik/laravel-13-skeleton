<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import { useTranslate } from '@/Composables/useTranslate';

const t = useTranslate();

defineProps<{ canResetPassword?: boolean; status?: string | null }>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function submit() {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-base-200 p-4">
        <div class="card w-full max-w-md bg-base-100 shadow-md">
            <div class="card-body">
                <h1 class="card-title text-2xl mb-4">{{ t('login') }}</h1>

                <div v-if="status" class="alert alert-info mb-4">{{ status }}</div>

                <form class="space-y-4" @submit.prevent="submit">
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">{{ t('email') }}</legend>
                        <input
                            v-model="form.email"
                            type="email"
                            autocomplete="email"
                            class="input input-bordered w-full"
                            :class="{ 'input-error': form.errors.email }"
                            required
                        />
                        <p v-if="form.errors.email" class="text-error text-sm mt-1">{{ form.errors.email }}</p>
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">{{ t('password') }}</legend>
                        <input
                            v-model="form.password"
                            type="password"
                            autocomplete="current-password"
                            class="input input-bordered w-full"
                            :class="{ 'input-error': form.errors.password }"
                            required
                        />
                        <p v-if="form.errors.password" class="text-error text-sm mt-1">{{ form.errors.password }}</p>
                    </fieldset>

                    <label class="label cursor-pointer justify-start gap-2">
                        <input v-model="form.remember" type="checkbox" class="checkbox checkbox-sm" />
                        <span class="label-text">{{ t('remember_me') }}</span>
                    </label>

                    <button type="submit" class="btn btn-primary w-full" :disabled="form.processing">
                        <span v-if="form.processing" class="loading loading-spinner loading-xs"></span>
                        {{ t('login') }}
                    </button>

                    <div v-if="canResetPassword" class="text-center text-sm">
                        <Link :href="route('password.request')" class="link link-hover">
                            {{ t('forgot_password') }}
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
