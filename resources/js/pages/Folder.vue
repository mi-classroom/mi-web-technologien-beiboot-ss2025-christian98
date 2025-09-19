<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Folder, StorageConfig } from '@/types';
import CreateFolderButton from '@/components/folder/CreateFolderButton.vue';
import { computed } from 'vue';
import CreateFileButton from '@/components/folder/CreateFileButton.vue';
import DeleteFolder from '@/components/folder/DeleteFolder.vue';
import FilePreview from '@/components/file/FilePreview.vue';

interface Props {
    storageConfig: { data: StorageConfig };
    folder: { data: Folder };
    breadcrumbs: { data: Folder[] };
}

const props = defineProps<Props>();

const breadcrumbs = computed(() =>
    props.breadcrumbs.data.map((folder) => ({
        title: folder.name != '' ? folder.name : '/',
        href: route('storage.folders.show', {
            folder: folder,
            storageConfig: props.storageConfig.data,
        }),
    })),
);

const items = computed(() => {
    return [
        ...(props.folder.data.folders ?? []),
        ...(props.folder.data.files ?? []),
    ].toSorted((a, b) => {
        return b.created_at.localeCompare(a.created_at);
    });
});

function navigate(item: Folder | File) {
    const href =
        'type' in item
            ? route('storage.files.show', {
                  file: item,
                  storageConfig: props.storageConfig.data,
              })
            : route('storage.folders.show', {
                  folder: item,
                  storageConfig: props.storageConfig.data,
              });

    router.visit(href);
}
</script>

<template>
    <Head :title="props.storageConfig.data.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mt-4 px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-base font-semibold">
                        {{ props.storageConfig.data.name }}
                    </h1>
                    <p class="mi-text-meta-info mt-2">
                        This is a list of all your local files. You can create
                        folders and upload files to them.
                    </p>
                </div>
                <div class="mt-4 flex gap-x-1 sm:mt-0 sm:ml-16 sm:flex-none">
                    <CreateFileButton
                        :folder="props.folder.data"
                        :storageConfig="props.storageConfig.data" />
                    <CreateFolderButton
                        :folder="props.folder.data"
                        :storageConfig="props.storageConfig.data" />
                    <DeleteFolder
                        v-if="props.folder.data.parent_id"
                        :folder="props.folder.data" />
                </div>
            </div>
            <div class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div
                        class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <table class="min-w-full">
                            <thead class="">
                                <tr>
                                    <th
                                        scope="col"
                                        class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold sm:pl-3">
                                        Name
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold">
                                        Path
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold">
                                        Size
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold">
                                        Created At
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold">
                                        Updated At
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-if="props.folder.data.parent"
                                    @click="navigate(props.folder.data.parent)"
                                    :data-href="
                                        route('storage.folders.show', {
                                            folder: props.folder.data.parent_id,
                                            storageConfig:
                                                props.storageConfig.data,
                                        })
                                    "
                                    class="border-border cursor-pointer border-t">
                                    <td
                                        class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap sm:pl-3">
                                        ..
                                    </td>
                                </tr>
                                <tr
                                    v-for="item in items"
                                    :key="item.id"
                                    @click="navigate(item)"
                                    class="border-border cursor-pointer border-t hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td
                                        class="flex items-center space-x-2 py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap sm:pl-3">
                                        <FilePreview
                                            v-if="'type' in item"
                                            :file="item"
                                            class="size-8 overflow-clip object-contain" />
                                        <svg
                                            v-else
                                            class="h-8 w-8 text-yellow-500"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z" />
                                        </svg>
                                        <span
                                            class="overflow-hidden overflow-ellipsis"
                                            :title="item.name">
                                            {{ item.name }}
                                        </span>
                                    </td>
                                    <td
                                        class="text-secondary-foreground px-3 py-4 text-sm whitespace-nowrap">
                                        {{ item.full_path }}
                                    </td>
                                    <td
                                        class="text-secondary-foreground px-3 py-4 text-sm whitespace-nowrap">
                                        {{
                                            'type' in item
                                                ? item.size_for_humans
                                                : null
                                        }}
                                    </td>
                                    <td
                                        class="text-secondary-foreground px-3 py-4 text-sm whitespace-nowrap">
                                        {{
                                            new Date(
                                                item.created_at,
                                            ).toLocaleString()
                                        }}
                                    </td>
                                    <td
                                        class="text-secondary-foreground px-3 py-4 text-sm whitespace-nowrap">
                                        {{
                                            new Date(
                                                item.updated_at,
                                            ).toLocaleString()
                                        }}
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
