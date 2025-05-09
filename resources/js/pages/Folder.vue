<script setup lang="ts">
import {Head, router} from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import type {Folder} from "@/types";
import CreateFolderButton from "@/components/folder/CreateFolderButton.vue";
import {computed} from "vue";
import CreateFileButton from "@/components/folder/CreateFileButton.vue";
import DeleteFolder from "@/components/folder/DeleteFolder.vue";

interface Props {
    folder: { data: Folder };
    breadcrumbs: Folder[];
}

const props = defineProps<Props>();

const breadcrumbs = computed(() => props.breadcrumbs.map((folder) => ({
    title: folder.name != '' ? folder.name : '/',
    href: route('folders.show', {folder: folder.id}),
})));

function navigate(event: Event) {
    const href = (event.currentTarget as HTMLTableRowElement).dataset['href'];

    if (!href) {
        return;
    }

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
                    <CreateFileButton :folder="props.folder.data"/>
                    <CreateFolderButton :folder="props.folder.data"/>
                    <DeleteFolder v-if="props.folder.data.parent_id" :folder="props.folder.data"/>
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
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold">Path
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="border-t border-border">
                                <th colspan="5" scope="colgroup"
                                    class="py-2 pr-3 pl-4 text-left text-sm font-semibold sm:pl-3">
                                    Folders
                                </th>
                            </tr>
                            <tr v-if="props.folder.data.parent_id"
                                @click="navigate"
                                :data-href="route('folders.show', {folder: props.folder.data.parent_id})"
                                class="border-t border-border cursor-pointer">
                                <td class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap sm:pl-3">
                                    ..
                                </td>
                            </tr>
                            <tr v-for="f in props.folder.data.folders" @click="navigate"
                                :data-href="route('folders.show', {folder:f.id})"
                                class="border-t border-border cursor-pointer hover:bg-gray-100">
                                <td class="py-4 pr-3 pl-4 text-sm flex items-center space-x-2 font-medium whitespace-nowrap sm:pl-3">
                                    <svg class="h-8 w-8 text-yellow-500" xmlns="http://www.w3.org/2000/svg"
                                         fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/>
                                    </svg>
                                    <span>{{ f.name }}</span>
                                </td>
                                <td class="px-3 py-4 text-sm whitespace-nowrap text-secondary-foreground">{{
                                        f.path
                                    }}
                                </td>
                            </tr>

                            <tr class="border-t border-border">
                                <th colspan="5" scope="colgroup"
                                    class="py-2 pr-3 pl-4 text-left text-sm font-semibold sm:pl-3">
                                    Files
                                </th>
                            </tr>
                            <tr v-for="file in props.folder.data.files" class="border-t border-border cursor-pointer hover:bg-gray-100"
                                @click="navigate" :data-href="route('files.show', {file: file.id})">
                                <td class="py-4 flex items-center space-x-2 pr-3 pl-4 text-sm font-medium whitespace-nowrap  sm:pl-3">
                                    <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ file.name }}</span>
                                </td>
                                <td class="px-3 py-4 text-sm whitespace-nowrap text-secondary-foreground">
                                    {{ file.size_for_humans }}
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
