<script setup lang="ts">
import {Head, router} from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import type {Folder, StorageConfig} from "@/types";
import CreateFolderButton from "@/components/folder/CreateFolderButton.vue";
import {computed} from "vue";
import CreateFileButton from "@/components/folder/CreateFileButton.vue";
import DeleteFolder from "@/components/folder/DeleteFolder.vue";

interface Props {
    folder: App.Services.Storage.Entities.Folder;
    storageConfig: { data: StorageConfig };
    breadcrumbs: App.Data.BreadcrumbData[];
}

const props = defineProps<Props>();

const breadcrumbs = computed(() => props.breadcrumbs.map((folder) => ({
    title: folder.name,
    href: folder.url,
})));

const items = computed(() => {
    return [
        ...(props.folder.folders ?? []),
        ...(props.folder.files ?? []),
    ].toSorted((a, b) => {
        return b.created_at.localeCompare(a.created_at);
    });
});

function navigate(item: App.Services.Storage.Entities.Folder | App.Services.Storage.Entities.File) {
    const href = 'mime_type' in item
        ? route('storage.files.show', {storageConfig: props.storageConfig.data, file: item})
        : route('storage.folders.show', {storageConfig: props.storageConfig.data, folder: item.path.slice(1)})

    router.visit(href);
}
</script>

<template>
    <Head title="Local Files"/>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 sm:px-6 lg:px-8 mt-4">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-base font-semibold">Local Files</h1>
                    <p class="mt-2 mi-text-meta-info">
                        This is a list of all your local files. You can create folders and upload files to them.
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none flex gap-x-1">
                    TODO
                </div>
            </div>
            <div class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <table class="min-w-full">
                            <thead class="">
                            <tr>
                                <th scope="col"
                                    class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold sm:pl-3">
                                    Name
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold">
                                    Path
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold">
                                    Size
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold">
                                    Created At
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold">
                                    Updated At
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="props.folder.parent" @click="navigate(props.folder.parent)"
                                class="border-t border-border cursor-pointer">
                                <td class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap sm:pl-3">
                                    ..
                                </td>
                            </tr>
                            <tr v-for="item in items" @click="navigate(item)"
                                class="border-t border-border cursor-pointer hover:bg-gray-100">
                                <td class="py-4 pr-3 pl-4 text-sm flex items-center space-x-2 font-medium whitespace-nowrap sm:pl-3">
                                    <svg class="h-8 w-8 text-yellow-500" xmlns="http://www.w3.org/2000/svg"
                                         fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/>
                                    </svg>
                                    <span class="overflow-ellipsis overflow-hidden" :title="item.name">
                                        {{ item.name }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 text-sm whitespace-nowrap text-secondary-foreground">
                                    {{ 'mime_type' in item ? `${props.folder.path}/${item.name}` : item.path }}
                                </td>
                                <td class="px-3 py-4 text-sm whitespace-nowrap text-secondary-foreground">
                                    {{ 'mime_type' in item ? item.size : null }}
                                </td>
                                <td class="px-3 py-4 text-sm whitespace-nowrap text-secondary-foreground">
                                    {{ new Date(item.created_at).toLocaleString() }}
                                </td>
                                <td class="px-3 py-4 text-sm whitespace-nowrap text-secondary-foreground">
                                    {{ new Date(item.updated_at).toLocaleString() }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
