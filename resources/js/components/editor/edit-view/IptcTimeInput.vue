<script setup lang="ts">
import IptcInput from '@/components/editor/edit-view/IptcInput.vue';
import { IptcTagDefinition } from '@/types';

function toIptcFormat(date?: string): string | undefined {
    if (!date) return undefined;
    return `${date.split(':').join('')}`; // Convert to HHmmss format
}

function fromIptcFormat(date?: string): string | undefined {
    if (!date) return undefined;
    return `${date.slice(0, 2)}:${date.slice(2, 4)}:${date.slice(4, 6)}`; // Convert to HH:mm:ss format
}

const [model, modifiers] = defineModel<string | undefined>({
    get(value) {
        return fromIptcFormat(value);
    },
    set(value) {
        return toIptcFormat(value);
    },
});

defineProps<{
    definition: IptcTagDefinition;
    index: number;
    readonly?: boolean;
    title?: string;
    suffix: string;
}>();
</script>

<template>
    <IptcInput
        v-model="model"
        type="time"
        :readonly
        :title
        :definition
        :index
        :suffix />
</template>
