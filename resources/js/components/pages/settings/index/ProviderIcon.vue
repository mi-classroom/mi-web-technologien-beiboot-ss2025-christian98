<script setup lang="ts">
import {StorageConfig} from "@/types";
import {computed} from "vue";
import {providerColorMap, providerIconMap} from "@/lib/providers";
import {Cloud} from "lucide-vue-next";

const props = defineProps<{
    config: StorageConfig
}>();

const color = computed(() => providerColorMap[props.config.provider_type] ?? providerColorMap.local);

const icon = computed(() => providerIconMap[props.config.provider_type] ?? Cloud);
</script>

<template>
    <div
        class="w-12 h-12 rounded-lg flex items-center justify-center mr-4 flex-shrink-0 text-white"
        :style="{ backgroundColor: color }"
    >
        <component :is="icon" class="w-6 h-6"></component>
    </div>
</template>
