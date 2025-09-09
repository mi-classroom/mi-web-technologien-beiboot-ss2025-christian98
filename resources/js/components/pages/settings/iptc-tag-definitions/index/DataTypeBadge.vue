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
        string: 'bg-blue-100 text-blue-800',
        enum: 'bg-purple-100 text-purple-800',
        binary: 'bg-gray-100 text-gray-800',
        date: 'bg-green-100 text-green-800',
        time: 'bg-yellow-100 text-yellow-800',
        number: 'bg-orange-100 text-orange-800',
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
