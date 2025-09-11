<script setup lang="ts">
import {IptcTagDefinition} from "@/types";
import {computed} from "vue";

const props = defineProps<{
    definition: IptcTagDefinition;
    class?: string;
}>();

const dataType = props.definition.spec.data_type;

const formatDataType = computed(() => {
    return dataType.charAt(0).toUpperCase() + dataType.slice(1);
});

const getDataTypeBadgeColor = computed(() => {
    const colors = {
        string: 'bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-100',
        enum: 'bg-purple-100 dark:bg-purple-800 text-purple-800 dark:text-purple-100',
        binary: 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100',
        date: 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100',
        time: 'bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100',
        number: 'bg-orange-100 dark:bg-orange-800 text-orange-800 dark:text-orange-100',
    };
    return colors[dataType as keyof typeof colors] || 'bg-gray-100 text-gray-800';
});
</script>

<template>
    <span
        :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium', getDataTypeBadgeColor, props.class]">
        {{ formatDataType }}
    </span>
</template>
