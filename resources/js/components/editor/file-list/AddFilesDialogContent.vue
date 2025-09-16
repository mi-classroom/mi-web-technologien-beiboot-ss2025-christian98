<script setup lang="ts">
import {
    DialogClose,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { useQuery } from '@tanstack/vue-query';
import { fetchApi } from '@/lib/fetchApi';
import type { File, Folder, StorageConfig } from '@/types';
import { computed, ref, watch } from 'vue';
import FilePreview from '@/components/file/FilePreview.vue';

const emits = defineEmits<{
    selected: [files: File[]];
}>();

const storageConfigsQuery = useQuery({
    queryKey: ['storage-configs'],
    queryFn: () =>
        fetchApi<{ data: StorageConfig[] }>(route('api.storage-config.index')),
});
const storageConfigs = computed(() => storageConfigsQuery.data.value?.data);
const selectedStorageConfigId = ref<number | undefined>();
const selectedStorageConfig = computed(() => {
    return storageConfigs.value?.find(
        (config) => config.id === selectedStorageConfigId.value,
    );
});

const folderId = ref<number | undefined>();
watch(selectedStorageConfig, (value) => {
    console.log(value);
    folderId.value = value?.root_folder_id;
});

watch(folderId, () => {
    console.log(route('api.folders.show', { folder: folderId.value }));
});

const folderQuery = useQuery({
    queryKey: ['folder', folderId],
    queryFn: () =>
        fetchApi<{ data: Folder }>(
            route('api.folders.show', { folder: folderId.value }),
        ),
});
const folder = computed(() => folderQuery.data.value?.data);

const items = computed(() => {
    return [
        ...(folder.value?.folders ?? []),
        ...(folder.value?.files ?? []),
    ].toSorted((a, b) => {
        return b.created_at.localeCompare(a.created_at);
    });
});

function navigate(item: Folder | File) {
    if ('type' in item) {
        emits('selected', [item]);
    } else {
        folderId.value = item.id;
    }
}
</script>

<template>
    <DialogContent class="max-h-[80vh] w-full max-w-[90vw] sm:max-w-[800px]">
        <div class="flex max-h-[80vh] min-h-0 flex-1 flex-col space-y-6">
            <!-- Header -->
            <DialogHeader class="flex-none">
                <DialogTitle>Add files</DialogTitle>
            </DialogHeader>

            <!-- Form oder Config Auswahl -->
            <div
                v-if="!selectedStorageConfigId"
                class="flex flex-none flex-col space-y-4">
                <Label for="storageConfig">Storage Config</Label>
                <select
                    id="storageConfig"
                    v-model="selectedStorageConfigId"
                    class="border-border bg-background focus:border-primary focus:ring-primary w-full rounded-md border px-3 py-2 text-sm shadow-sm">
                    <option value="" disabled>Select a storage config</option>
                    <option
                        v-for="config in storageConfigs"
                        :key="config.id"
                        :value="config.id">
                        {{ config.name }}
                    </option>
                </select>
            </div>

            <!-- Datei-Liste -->
            <div
                v-if="selectedStorageConfigId"
                class="min-h-0 flex-1 overflow-auto px-6">
                <div class="overflow-x-auto">
                    <table class="w-full table-fixed">
                        <thead>
                            <tr>
                                <th
                                    class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold sm:pl-3">
                                    Name
                                </th>
                                <th
                                    class="px-3 py-3.5 text-left text-sm font-semibold">
                                    Path
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-if="folder?.parent"
                                @click="navigate(folder.parent)"
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
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer -->
            <DialogFooter class="mt-2 flex flex-none justify-end gap-2">
                <DialogClose as-child>
                    <Button variant="default">Finish</Button>
                </DialogClose>
            </DialogFooter>
        </div>
    </DialogContent>
</template>

<style scoped></style>
