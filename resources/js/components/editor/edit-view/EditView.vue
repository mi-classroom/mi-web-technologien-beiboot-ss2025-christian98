<script setup lang="ts">
import {Button} from "@/components/ui/button";
import Icon from "@/components/Icon.vue";
import {IptcItem} from "@/types";
import FileEntry from "@/components/editor/edit-view/FileEntry.vue";
import {useMutation} from "@tanstack/vue-query";
import {fetchApi} from "@/lib/fetchApi";

defineProps<{
    attributes: Map<string, IptcItem[]>;
}>();

const selectedTag = defineModel<string | null>('selectedTag');

const updateMutation = useMutation({
    mutationKey: ['saveFileAttribute'],
    mutationFn: async (data: { item: IptcItem, newValue: string[] }) => {
        return await fetchApi(route('api.iptc.update', {iptc: data.item}), {
            method: 'PUT',
            body: JSON.stringify({
                value: data.newValue,
            }),
        });
    },
    onSuccess: () => {
        // Handle success, e.g., show a notification
    },
    onError: (error) => {
        // Handle error, e.g., show an error message
    }
});

const destroyMutation = useMutation({
    mutationKey: ['destroyFileAttribute'],
    mutationFn: async (item: IptcItem) => {
        return await fetchApi(route('api.iptc.destroy', {iptc: item}), {
            method: 'DELETE',
        });
    },
    onSuccess: () => {
        // Handle success, e.g., show a notification
    },
    onError: (error) => {
        // Handle error, e.g., show an error message
    }
});

function handleSave(item: IptcItem, newValue: string[]) {
    updateMutation.mutate({item, newValue});
}

function handleRemove(item: IptcItem) {
    destroyMutation.mutate(item);
}

</script>

<template>
    <div class="border-b flex flex-row justify-between items-center border-mi-warm-dark px-2 py-3 sm:px-3">
        <div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Edit</h3>
            <p class="text-xs text-gray-600 dark:text-gray-300">Edit attributes of selected files</p>
        </div>
        <div class="flex gap-1">
            <Button title="Suggestions">
                <Icon name="Lightbulb" class="size-4"/>
                <span>Suggestions</span>
            </Button>
            <Button title="Save Changes">
                <Icon name="Save" class="size-4"/>
                <span>Save all</span>
            </Button>

            <Button variant="ghost" title="Discard Changes" @click="selectedTag = null">
                <Icon name="X" class="size-4"/>
                <span class="sr-only">Close</span>
            </Button>
        </div>
    </div>

    <div class="mx-2 mt-2">
        <span class="font-bold text-sm">
            Apply to all selected files
        </span>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2 mt-1">
            <Button class="basis-1/4">
                <Icon name="SquareArrowUp" class="size-5"/>
                Prepend all
            </Button>
            <Button class="basis-1/4">
                <Icon name="SquareArrowDown" class="size-5"/>
                Append all
            </Button>
            <Button class="basis-1/4">
                <Icon name="SquarePen" class="size-5"/>
                Overwrite all
            </Button>
            <Button class="basis-1/4">
                <Icon name="SquareX" class="size-5"/>
                Remove all
            </Button>
        </div>
    </div>

    <hr class="h-1 border-mi-dark mt-6 mb-2"/>

    <span class="text-xl mx-2 font-bold">Files</span>
    <ul class="flex flex-col gap-y-1 px-1 divide divide-y divide-mi-dark overflow-auto">
        <li v-if="selectedTag" v-for="item in attributes.get(selectedTag)" :key="item.id" class="py-4 ml-12">
            <FileEntry :item @save="handleSave" @remove="handleRemove"/>
        </li>
    </ul>

    <div class="mt-8 px-1">
        <Button class="w-full">Add file</Button>
    </div>
</template>
