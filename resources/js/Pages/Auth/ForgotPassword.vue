<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import { useTranslate } from '@/Composables/useTranslate';

const t = useTranslate();

defineProps<{ status?: string | null }>();

const form = useForm({ email: '' });

function submit() {
    form.post(route('password.email'));
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-base-200 p-4">
        <div class="card w-full max-w-md bg-base-100 shadow-md">
            <div class="card-body">
                <h1 class="card-title text-2xl mb-4">{{ t('forgot_password') }}</h1>

                <div v-if="status" class="alert alert-success mb-4">{{ status }}</div>

                <form class="space-y-4" @submit.prevent="submit">
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">{{ t('email') }}</legend>
                        <input
                            v-model="form.email"
                            type="email"
                            class="input input-bordered w-full"
                            :class="{ 'input-error': form.errors.email }"
                            required
                        />
                        <p v-if="form.errors.email" class="text-error text-sm mt-1">{{ form.errors.email }}</p>
                    </fieldset>

                    <button type="submit" class="btn btn-primary w-full" :disabled="form.processing">
                        <span v-if="form.processing" class="loading loading-spinner loading-xs"></span>
                        {{ t('send_reset_link') }}
                    </button>

                    <div class="text-center text-sm">
                        <Link :href="route('login')" class="link link-hover">{{ t('login') }}</Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
