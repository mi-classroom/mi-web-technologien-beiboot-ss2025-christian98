<script setup lang="ts">
import { StorageConfig } from '@/types';
import { computed } from 'vue';
import { providerColorMap, providerIconMap } from '@/lib/providers';
import { Cloud } from 'lucide-vue-next';

const props = defineProps<{
    config: StorageConfig;
}>();

const color = computed(
    () =>
        providerColorMap[props.config.provider_type] ?? providerColorMap.local,
);

const icon = computed(
    () => providerIconMap[props.config.provider_type] ?? Cloud,
);
</script>

<template>
    <div
        class="mr-4 flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg text-white"
        :style="{ backgroundColor: color }">
        <component :is="icon" class="h-6 w-6"></component>
    </div>
</template>
