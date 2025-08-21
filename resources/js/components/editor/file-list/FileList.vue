<script setup lang="ts">
import {Button} from "@/components/ui/button";
import Icon from "@/components/Icon.vue";
import FileListItem from "@/components/editor/file-list/FileListItem.vue";
import {File} from "@/types";
import {Dialog, DialogTrigger} from "@/components/ui/dialog";
import AddFilesDialogContent from "@/components/editor/file-list/AddFilesDialogContent.vue";

defineProps<{
    files?: File[];
}>();

const selectedFileIds = defineModel<number[]>('selectedFileIds');

defineEmits<{
    (e: 'selected', files: File[]): void;
}>();
</script>

<template>
    <div class="border-b flex flex-row justify-between items-center border-mi-warm-dark px-2 py-3 sm:px-3">
        <div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Files</h3>
            <p class="text-xs text-gray-600 dark:text-gray-300">Select files you want to edit</p>
        </div>

        <div class="flex gap-1">
            <Dialog>
                <DialogTrigger as-child>
                    <Button size="icon" title="Add Files">
                        <Icon name="Plus" class="size-4"/>
                    </Button>
                </DialogTrigger>
                <AddFilesDialogContent @selected="f => $emit('selected', f)"/>
            </Dialog>

            <Button disabled size="icon" title="Filter Files">
                <Icon name="Filter" class="size-4"/>
            </Button>
        </div>
    </div>
    <ul role="list" class="flex flex-col divide-y divide-gray-100 overflow-auto">
        <li v-for="file in files" :key="file.id" class="flex gap-x-4 px-2 py-2 items-center">
            <FileListItem v-model="selectedFileIds" :file/>
        </li>
    </ul>
</template>
