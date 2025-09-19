<script setup lang="ts">
import { ref } from 'vue';
import { FileInput } from '@/components/folder/CreateFileButton.vue';

const props = defineProps<{
    file: FileInput;
}>();

const src = ref<string>('');

const reader = new FileReader();
reader.onload = (event) => {
    src.value = event.target?.result as string;
};
reader.readAsDataURL(props.file.file);
</script>

<template>
    <div
        class="group aspect-square overflow-hidden rounded-lg bg-gray-100 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 focus-within:ring-offset-gray-100">
        <img
            :src="src"
            alt=""
            class="pointer-events-none object-cover group-hover:opacity-75" />
        <button type="button" class="absolute inset-0 focus:outline-hidden">
            <span class="sr-only">View details for {{ file.name }}</span>
        </button>
    </div>
    <p
        class="pointer-events-none mt-2 block truncate text-sm font-medium text-gray-900">
        {{ file.name }}
    </p>
    <p class="pointer-events-none block text-sm font-medium text-gray-500">
        {{ file.file.size }}
    </p>
</template>
