<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Icon from '@/components/Icon.vue';
import { File, IptcItem, IptcTagDefinition, Resource } from '@/types';
import FileEntry from '@/components/editor/edit-view/FileEntry.vue';
import { useMutation, useQueryClient } from '@tanstack/vue-query';
import { fetchApi } from '@/lib/fetchApi';
import { useTemplateRef } from 'vue';

const props = defineProps<{
    attributes: Map<number, IptcItem[]>;
    selectedFiles: File[];
    fileIds: number[];
}>();

const selectedTag = defineModel<IptcTagDefinition | null>('selectedTag');

const fileRefs = useTemplateRef<InstanceType<typeof FileEntry>[]>('fileRefs');

const queryClient = useQueryClient();

const createMutation = useMutation({
    mutationKey: ['createFileAttribute'],
    mutationFn: async (data: { file: File; newValue: string[] }) => {
        return await fetchApi<Resource<IptcItem>>(
            route('api.files.iptc.store', { file: data.file }),
            {
                method: 'POST',
                body: JSON.stringify({
                    tag: selectedTag.value?.id,
                    value: data.newValue,
                }),
            },
        );
    },
    onSuccess: () => {
        // Handle success, e.g., show a notification
        queryClient.invalidateQueries({ queryKey: ['files', props.fileIds] });
    },
    onError: () => {
        // Handle error, e.g., show an error message
    },
});

const updateMutation = useMutation({
    mutationKey: ['saveFileAttribute'],
    mutationFn: async (data: { item: IptcItem; newValue: string[] }) => {
        return await fetchApi<Resource<IptcItem>>(
            route('api.iptc.update', { iptc: data.item }),
            {
                method: 'PUT',
                body: JSON.stringify({
                    value: data.newValue,
                }),
            },
        );
    },
    onSuccess: () => {
        // Handle success, e.g., show a notification
        queryClient.invalidateQueries({ queryKey: ['files', props.fileIds] });
    },
    onError: () => {
        // Handle error, e.g., show an error message
    },
});

const destroyMutation = useMutation({
    mutationKey: ['destroyFileAttribute'],
    mutationFn: async (item: IptcItem) => {
        return await fetchApi(route('api.iptc.destroy', { iptc: item }), {
            method: 'DELETE',
        });
    },
    onSuccess: () => {
        // Handle success, e.g., show a notification
        queryClient.invalidateQueries({ queryKey: ['files', props.fileIds] });
    },
    onError: () => {
        // Handle error, e.g., show an error message
    },
});

function handleSave(item: IptcItem | File, newValue: string[]) {
    console.log(item, newValue);
    if ('tag' in item) {
        updateMutation.mutate({ item, newValue });
    } else if (newValue.length > 0) {
        // If it's a File and newValue is not empty, create a new Iptc
        createMutation.mutate({ file: item, newValue });
    }
}

function handleRemove(item: IptcItem) {
    destroyMutation.mutate(item);
}

function handleSaveAll() {
    fileRefs.value?.forEach((fileEntry) => {
        fileEntry.save();
    });
}
</script>

<template>
    <div
        class="border-mi-warm-dark flex flex-row items-center justify-between border-b px-2 py-3 sm:px-3">
        <div>
            <h3
                class="text-base font-semibold text-gray-900 dark:text-gray-100">
                Edit
            </h3>
            <p class="text-xs text-gray-600 dark:text-gray-300">
                Edit attributes of selected files
            </p>
        </div>
        <div class="flex gap-1">
            <Button title="Suggestions" disabled>
                <Icon name="Lightbulb" class="size-4" />
                <span>AI Suggestions</span>
            </Button>
            <Button title="Save Changes" @click="handleSaveAll">
                <Icon name="Save" class="size-4" />
                <span>Save all</span>
            </Button>

            <Button
                variant="ghost"
                title="Discard Changes"
                @click="selectedTag = null">
                <Icon name="X" class="size-4" />
                <span class="sr-only">Close</span>
            </Button>
        </div>
    </div>

    <div class="mx-2 mt-2">
        <span class="text-sm font-bold"> Apply to all selected files </span>
        <div class="mt-1 grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-4">
            <Button class="basis-1/4" disabled>
                <Icon name="SquareArrowUp" class="size-5" />
                Prepend all
            </Button>
            <Button class="basis-1/4" disabled>
                <Icon name="SquareArrowDown" class="size-5" />
                Append all
            </Button>
            <Button class="basis-1/4" disabled>
                <Icon name="SquarePen" class="size-5" />
                Overwrite all
            </Button>
            <Button class="basis-1/4" disabled>
                <Icon name="SquareX" class="size-5" />
                Remove all
            </Button>
        </div>
    </div>

    <hr class="border-mi-dark mt-6 mb-2 h-1" />

    <div>
        <span class="mx-2 text-xl font-bold">Files</span>
        <ul
            v-if="selectedTag"
            class="divide divide-mi-dark flex flex-col gap-y-1 divide-y overflow-auto px-1">
            <FileEntry
                v-for="file in selectedFiles"
                :key="file.id"
                ref="fileRefs"
                :file
                :selectedTag="selectedTag"
                @save="handleSave"
                @remove="handleRemove" />
        </ul>
    </div>
</template>
