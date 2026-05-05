<script setup lang="ts">
import { useTranslate } from '@/Composables/useTranslate';

const t = useTranslate();

defineProps<{
    open: boolean;
    title?: string;
    description?: string;
}>();

const emit = defineEmits<{
    (e: 'confirm'): void;
    (e: 'cancel'): void;
}>();
</script>

<template>
    <dialog class="modal" :class="{ 'modal-open': open }">
        <div class="modal-box">
            <h3 class="font-bold text-lg">{{ title ?? t('confirm_delete') }}</h3>
            <p v-if="description" class="py-4">{{ description }}</p>
            <div class="modal-action">
                <button class="btn btn-ghost" type="button" @click="emit('cancel')">{{ t('cancel') }}</button>
                <button class="btn btn-error" type="button" @click="emit('confirm')">{{ t('delete') }}</button>
            </div>
        </div>
    </dialog>
</template>
