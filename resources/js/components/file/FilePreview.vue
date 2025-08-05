<script setup lang="ts">
import {File} from '@/types';
import {computed} from "vue";
import Icon from "@/components/Icon.vue";

const props = defineProps<{
    file: File;
}>();

const isText = computed(() => {
    return props.file.type.startsWith('text/') || props.file.type === 'application/pdf';
});

const isArchive = computed(() => {
    const validTypes = ['application/zip', 'application/x-rar-compressed'];

    return validTypes.includes(props.file.type);
});

const iconName = computed(() => {
    if (props.file.type.startsWith('image/')) return 'FileImage';
    if (props.file.type.startsWith('video/')) return 'FileVideo';
    if (props.file.type.startsWith('audio/')) return 'FileMusic';
    if (isText.value) return 'FileText';
    if (isArchive.value) return 'FileArchive';
    return 'File';
});
</script>

<template>
    <img
        v-if="file.type.startsWith('image/')"
        v-bind="$attrs"
        :src="route('storage.files.download', {file, storageConfig: file.storage_config_id})"
        :alt="file.name"
    />
    <Icon v-else
          :name="iconName"
          v-bind="$attrs"
          class="w-full h-full object-contain"
          :title="file.name"/>
</template>
