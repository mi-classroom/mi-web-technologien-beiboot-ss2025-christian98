<script setup lang="ts">
import { IptcTagDefinition } from '@/types';

defineProps<{
    modelValue?: string | number;
    definition: IptcTagDefinition;
    index: number;
    type: 'text' | 'date' | 'datetime-local' | 'time' | 'number' | 'checkbox';
    readonly?: boolean;
    title?: string;
    suffix: string;
}>();

const model = defineModel<string | undefined>();
</script>

<template>
    <input
        :id="`iptc-${definition.tag}-${index}-${suffix}`"
        :name="`iptc-${definition.tag}-${index}-${suffix}`"
        :type
        :title
        :minLength="definition.spec.min_length"
        :maxLength="definition.spec.max_length"
        v-model="model"
        :disabled="!definition.is_value_editable || readonly"
        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 disabled:outline-gray-200 sm:text-sm/6" />
</template>
