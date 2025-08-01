<script setup lang="ts">
import {File} from '@/types';
import {Link} from "@inertiajs/vue3";
import FilePreview from "@/components/file/FilePreview.vue";

defineProps<{
    file: File
    timestampField: 'created_at' | 'updated_at'
    timestampLabel: string
}>();
</script>

<template>
    <Link class="flex h-16 hover:bg-mi-primary/20"
          :href="route('storage.files.show', {file, storageConfig: file.storage_config_id})">
        <div class="bg-mi-warm-light dark:bg-mi-warm-dark/30 h-full aspect-square cursor-pointer">
            <FilePreview :file class="size-full aspect-square object-contain overflow-clip"/>
        </div>
        <div class="flex justify-between w-full items-center" :title="file.name">
            <div class="flex grow flex-col cursor-pointer h-full justify-center pl-4">
                <span class="font-bold">{{ file.name }}</span>
                <span>{{ timestampLabel }}: {{ new Date(file[timestampField]).toLocaleDateString() }}</span>
            </div>
            <div class="pr-4">

            </div>
        </div>
    </Link>
</template>
