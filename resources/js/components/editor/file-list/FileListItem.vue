<script setup lang="ts">
import { File } from '@/types';

defineProps<{
    file: File;
}>();

const model = defineModel<number[]>();
</script>

<template>
    <input
        type="checkbox"
        :id="`file-${file.id}`"
        :value="file.id"
        v-model="model" />
    <img
        :src="route('api.files.preview', { file })"
        alt="File Icon"
        class="size-12 flex-none bg-gray-50 object-cover" />
    <label :for="`file-${file.id}`" class="min-w-0">{{ file.name }}</label>
</template>
