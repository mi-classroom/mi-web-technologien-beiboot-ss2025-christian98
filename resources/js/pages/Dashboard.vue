<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, File } from '@/types';
import { Deferred, Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import FileListItem from '@/components/dashboard/FileListItem.vue';

defineProps<{
    recentlyEditedFiles?: { data: File[] };
    recentlyAddedFiles?: { data: File[] };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid h-1/2 gap-4 md:grid-cols-2">
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border relative flex h-full flex-col overflow-hidden rounded-xl border pt-2 pl-2">
                    <div class="border-b pb-1 text-xl font-bold">
                        Recently Edited
                    </div>
                    <Deferred data="recentlyEditedFiles">
                        <template #fallback>
                            Loading...
                            <PlaceholderPattern />
                        </template>
                        <ul
                            class="divide-mi-warm-light flex flex-col gap-y-2 divide-y overflow-auto">
                            <li v-for="file in recentlyEditedFiles?.data" :key="file.id">
                                <FileListItem
                                    :file="file"
                                    timestamp-label="Last modified"
                                    timestamp-field="updated_at" />
                            </li>
                        </ul>
                    </Deferred>
                </div>
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border relative flex h-full flex-col overflow-hidden rounded-xl border pt-2 pl-2">
                    <div class="border-b pb-1 text-xl font-bold">
                        Recently Added
                    </div>
                    <Deferred data="recentlyAddedFiles">
                        <template #fallback>
                            Loading...
                            <PlaceholderPattern />
                        </template>
                        <ul
                            class="divide-mi-warm-light flex flex-col gap-y-2 divide-y overflow-auto">
                            <li v-for="file in recentlyAddedFiles?.data" :key="file.id">
                                <FileListItem
                                    :file="file"
                                    timestamp-label="Added at"
                                    timestamp-field="created_at" />
                            </li>
                        </ul>
                    </Deferred>
                </div>
            </div>
            <div
                class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                Show all Storage Providers
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
