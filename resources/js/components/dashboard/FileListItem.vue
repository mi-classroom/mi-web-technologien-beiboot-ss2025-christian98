<script setup lang="ts">
import {File} from '@/types';
import {Link} from "@inertiajs/vue3";

defineProps<{
    file: File
    timestampField: 'created_at' | 'updated_at'
    timestampLabel: string
}>();
</script>

<template>
    <Link class="flex h-16 hover:bg-mi-primary/20" :href="route('local.files.show', {file: file})">
        <div class="bg-mi-warm-light h-full aspect-square cursor-pointer">
            <img class="size-full object-contain overflow-clip"
                 :src="route('local.files.download', {file: file})" :alt="file.name"/>
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
