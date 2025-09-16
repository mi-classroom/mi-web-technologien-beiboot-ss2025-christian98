<script setup lang="ts">
import AttributeListItem from '@/components/editor/attribute-list/AttributeListItem.vue';
import { Button } from '@/components/ui/button';
import Icon from '@/components/Icon.vue';
import { computed, ref } from 'vue';
import { File, IptcTagDefinition } from '@/types';
import { SquareMousePointer, FileX } from 'lucide-vue-next';
import AddAttributeModal from '@/components/editor/attribute-list/AddAttributeModal.vue';

const props = defineProps<{
    selectedFiles: File[];
    attributes: Map<number, IptcTagDefinition>;
    allFileIds: number[];
}>();

const definitionsForSelectedFiles = computed(() => {
    const definitions = new Map<number, IptcTagDefinition>();
    props.selectedFiles.forEach((file) => {
        file.meta_data?.iptc_items?.forEach((item) => {
            if (!definitions.has(item.tag.id)) {
                definitions.set(item.tag.id, item.tag);
            }
        });
    });
    return definitions;
});

const additionalAttributes = ref<IptcTagDefinition[]>([]);

const allAttributeDefinitions = computed(() => {
    const definitions = new Map<number, IptcTagDefinition>(
        definitionsForSelectedFiles.value,
    );
    additionalAttributes.value.forEach((definition) => {
        if (!definitions.has(definition.id)) {
            definitions.set(definition.id, definition);
        }
    });

    return definitions;
});

const selectedTag = defineModel<IptcTagDefinition | null>('selectedTag');
const selectedFileIds = defineModel<number[]>('selectedFileIds');

const areFilesSelected = computed(() => props.selectedFiles.length > 0);

function definitionAdded(definition: IptcTagDefinition) {
    additionalAttributes.value = [...additionalAttributes.value, definition];
    selectedTag.value = definition;
}
</script>

<template>
    <div
        class="border-mi-warm-dark flex flex-row items-center justify-between border-b px-2 py-3 sm:px-3">
        <div>
            <h3
                class="text-base font-semibold text-gray-900 dark:text-gray-100">
                Attributes
            </h3>
            <p class="text-xs text-gray-600 dark:text-gray-300">
                Choose an attribute to edit
            </p>
        </div>
        <div class="flex gap-1">
            <AddAttributeModal
                :existing-definitions="[...allAttributeDefinitions.values()]"
                :disabled="!areFilesSelected"
                @add="definitionAdded" />
            <Button
                size="icon"
                title="Apply Template to Selected Files"
                disabled>
                <Icon name="FileInput" class="size-4" />
            </Button>
            <Button size="icon" title="Filter Attributes" disabled>
                <Icon name="Filter" class="size-4" />
            </Button>
        </div>
    </div>
    <ul
        v-if="areFilesSelected"
        role="list"
        class="flex flex-col divide-y divide-gray-100 overflow-auto">
        <li
            v-for="[, definition] in allAttributeDefinitions"
            :key="definition.id"
            @click="selectedTag = definition"
            :aria-selected="definition.id === selectedTag?.id"
            class="aria-selected:bg-mi-warm-medium/40 hover:bg-mi-warm-medium/50 relative flex items-center space-x-4 rounded">
            <AttributeListItem
                :tag-definition="definition"
                :selectedFiles="selectedFiles" />
        </li>
    </ul>
    <div v-else class="my-auto text-center">
        <FileX class="mx-auto size-12 stroke-[1.5] text-gray-400" />
        <h3 class="mt-2 text-sm font-semibold text-gray-900">
            No files selected
        </h3>
        <p class="mt-1 text-sm text-gray-500">
            Get started by selecting files on the left.
        </p>
        <div class="mt-6">
            <Button @click="selectedFileIds = allFileIds">
                <SquareMousePointer
                    class="mr-1.5 -ml-0.5 size-5"
                    aria-hidden="true" />
                Select all files
            </Button>
        </div>
    </div>
</template>
