<script setup lang="ts">
import { File } from '@/types';
import { Link } from '@inertiajs/vue3';
import FilePreview from '@/components/file/FilePreview.vue';

defineProps<{
    file: File;
    timestampField: 'created_at' | 'updated_at';
    timestampLabel: string;
}>();
</script>

<template>
    <Link
        class="hover:bg-mi-primary/20 flex h-16"
        :href="
            route('storage.files.show', {
                file,
                storageConfig: file.storage_config_id,
            })
        ">
        <div
            class="bg-mi-warm-light dark:bg-mi-warm-dark/30 aspect-square h-full cursor-pointer">
            <FilePreview
                :file
                class="aspect-square size-full overflow-clip object-contain" />
        </div>
        <div
            class="flex w-full items-center justify-between"
            :title="file.name">
            <div
                class="flex h-full grow cursor-pointer flex-col justify-center pl-4">
                <span class="font-bold">{{ file.name }}</span>
                <span>
                    {{ timestampLabel }}:
                    {{ new Date(file[timestampField]).toLocaleDateString() }}
                </span>
            </div>
            <div class="pr-4"></div>
        </div>
    </Link>
</template>
