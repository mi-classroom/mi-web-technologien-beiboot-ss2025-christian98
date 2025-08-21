<script setup lang="ts">
import {IptcTagDefinition} from "@/types";

const props = defineProps<{
    modelValue?: string | number
    definition: IptcTagDefinition;
    index: number;
    readonly?: boolean;
    title?: string;
    suffix: string;
}>();

const model = defineModel<string|undefined>();
</script>

<template>
    <select :id="`iptc-${definition.tag}-${index}-${suffix}`" :name="`iptc-${definition.tag}-${index}-${suffix}`"
           :title
           v-model="model"
           :disabled="!definition.is_value_editable || readonly"
           class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 disabled:outline-gray-200 sm:text-sm/6">
        <option v-for="(option, i) in definition.spec.enum_values ?? []" :key="i" :value="option">{{ option }}</option>
    </select>
</template>
