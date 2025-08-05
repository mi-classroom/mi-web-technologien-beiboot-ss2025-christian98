<script setup lang="ts">
import Icon from "@/components/Icon.vue";
import {StorageConfig} from "@/types";
import {computed} from "vue";

const props = defineProps<{
    config: StorageConfig
}>();

const color = computed(() => {
    const colorMap = {
        'local': '#4a5568',
        'webdav': '#0077c2',
    }

    return colorMap[props.config.provider_type] ?? '#4a5568';
});

const icon = computed(() => {
    const iconMap = { // TODO: duplicated in AppHeader.vue
        'local': 'HardDrive',
        'webdav': 'Cloud',
    };

    return iconMap[props.config.provider_type] ?? 'Cloud';
});
</script>

<template>
    <div
        class="w-12 h-12 rounded-lg flex items-center justify-center mr-4 flex-shrink-0 text-white"
        :style="{ backgroundColor: color }"
    >
        <Icon :name="icon" class="w-6 h-6"></Icon>
    </div>
</template>
