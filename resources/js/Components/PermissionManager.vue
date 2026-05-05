<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    modelValue: string[];
    permissions: string[];
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: string[]): void;
}>();

const groups = computed(() => {
    const out: Record<string, string[]> = {};
    for (const p of props.permissions) {
        const parts = p.split(' ');
        const key = parts[parts.length - 1] ?? 'general';
        out[key] = out[key] ?? [];
        out[key].push(p);
    }
    return out;
});

function toggle(perm: string, checked: boolean) {
    const set = new Set(props.modelValue);
    if (checked) set.add(perm);
    else set.delete(perm);
    emit('update:modelValue', Array.from(set));
}
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <fieldset
            v-for="(perms, group) in groups"
            :key="group"
            class="border border-base-300 rounded-box p-4"
        >
            <legend class="font-semibold capitalize px-2">{{ group }}</legend>
            <label v-for="perm in perms" :key="perm" class="label cursor-pointer justify-start gap-2">
                <input
                    type="checkbox"
                    class="checkbox checkbox-sm"
                    :checked="modelValue.includes(perm)"
                    @change="toggle(perm, ($event.target as HTMLInputElement).checked)"
                />
                <span class="label-text">{{ perm }}</span>
            </label>
        </fieldset>
    </div>
</template>
