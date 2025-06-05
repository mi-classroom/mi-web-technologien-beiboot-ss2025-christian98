<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {type BreadcrumbItem, File} from '@/types';
import {Deferred, Head} from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import FileListItem from "@/components/dashboard/FileListItem.vue";

defineProps<{
    recentlyEditedFiles?: { data: File[] };
    recentlyAddedFiles?: { data: File[] };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    }
];
</script>

<template>
    <Head title="Dashboard"/>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid h-1/2 gap-4 md:grid-cols-2">
                <div
                    class="relative pl-2 pt-2 h-full overflow-hidden flex flex-col rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div class="text-xl font-bold border-b pb-1">Recently Edited</div>
                    <Deferred data="recentlyEditedFiles">
                        <template #fallback>
                            Loading...
                            <PlaceholderPattern/>
                        </template>
                        <ul class="flex gap-y-2 overflow-auto flex-col divide-mi-warm-light divide-y">
                            <li v-for="file in recentlyEditedFiles?.data">
                                <FileListItem :file="file" timestamp-label="Last modified" timestamp-field="updated_at"/>
                            </li>
                        </ul>
                    </Deferred>
                </div>
                <div
                    class="relative pl-2 pt-2 h-full overflow-hidden flex flex-col rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div class="text-xl font-bold border-b pb-1">Recently Added</div>
                    <Deferred data="recentlyAddedFiles">
                        <template #fallback>
                            Loading...
                            <PlaceholderPattern/>
                        </template>
                        <ul class="flex gap-y-2 overflow-auto flex-col divide-mi-warm-light divide-y">
                            <li v-for="file in recentlyAddedFiles?.data">
                                <FileListItem :file="file" timestamp-label="Added at" timestamp-field="created_at"/>
                            </li>
                        </ul>
                    </Deferred>
                </div>
            </div>
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                Show all Storage Providers
                <PlaceholderPattern/>
            </div>
        </div>
    </AppLayout>
</template>
