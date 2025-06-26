<script setup lang="ts">
import AttributeListItem from "@/components/editor/attribute-list/AttributeListItem.vue";
import {Button} from "@/components/ui/button";
import Icon from "@/components/Icon.vue";
import {computed} from "vue";
import {IptcItem, File} from "@/types";
import {SquareMousePointer, FileX} from "lucide-vue-next";

const props = defineProps<{
    selectedFiles: File[];
    attributes: Map<string, IptcItem[]>;
    allFileIds: number[];
}>();

const selectedTag = defineModel<string | null>('selectedTag');
const selectedFileIds = defineModel<number[]>('selectedFileIds');

const areFilesSelected = computed(() => props.selectedFiles.length > 0);
</script>

<template>
    <div class="border-b flex flex-row justify-between items-center border-mi-warm-dark px-2 py-3 sm:px-3">
        <div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Attributes</h3>
            <p class="text-xs text-gray-600 dark:text-gray-300">Choose an attribute to edit</p>
        </div>
        <div class="flex gap-1">
            <Button size="icon" title="Add Additional Attributes" :disabled="!areFilesSelected">
                <Icon name="Plus" class="size-4"/>
            </Button>
            <Button size="icon" title="Apply Template to Selected Files" :disabled="!areFilesSelected">
                <Icon name="FileInput" class="size-4"/>
            </Button>
            <Button size="icon" title="Filter Attributes" :disabled="!areFilesSelected">
                <Icon name="Filter" class="size-4"/>
            </Button>
        </div>
    </div>
    <ul v-if="areFilesSelected" role="list" class="flex flex-col divide-y divide-gray-100 overflow-auto">
        <li v-for="[tag, items] in attributes" :key="tag" @click="selectedTag = tag"
            :aria-selected="tag === selectedTag"
            class="relative flex items-center space-x-4 rounded aria-selected:bg-mi-warm-medium/40 hover:bg-mi-warm-medium/50">
            <AttributeListItem :tag="tag" :items="items" :totalFiles="selectedFiles.length"/>
        </li>
    </ul>
    <div v-else class="text-center my-auto">
        <FileX class="mx-auto size-12 text-gray-400 stroke-[1.5]"/>
        <h3 class="mt-2 text-sm font-semibold text-gray-900">No files selected</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by selecting files on the left.</p>
        <div class="mt-6">
            <Button @click="selectedFileIds = allFileIds">
                <SquareMousePointer class="mr-1.5 -ml-0.5 size-5" aria-hidden="true"/>
                Select all files
            </Button>
        </div>
    </div>
</template>
