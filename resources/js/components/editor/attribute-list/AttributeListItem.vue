<script setup lang="ts">
import Icon from "@/components/Icon.vue";
import {File, IptcTagDefinition} from "@/types";
import {computed} from "vue";

const props = defineProps<{
    tagDefinition: IptcTagDefinition;
    selectedFiles: File[];
}>();

const totalFilesCount = computed(() => props.selectedFiles.length);
const selectedFilesWithTag = computed(() => {
    return props.selectedFiles.filter(file =>
        file.meta_data?.iptc_items?.some(item => item.tag.id === props.tagDefinition.id)
    );
});
const selectedFilesWithTagCount = computed(() => {
    return selectedFilesWithTag.value.length;
});

console.log(props.tagDefinition.iptcItems);

function statuses(length: number) {
    if (length === totalFilesCount.value) {
        return 'text-green-400 bg-green-400/10';
    } else if (length / totalFilesCount.value > 0.5) {
        return 'text-yellow-500 bg-yellow-100/10';
    } else {
        return 'text-rose-400 bg-rose-400/10';
    }
}
</script>

<template>
    <div class="min-w-0 flex-auto cursor-pointer px-1 py-4">
        <div class="flex items-center gap-x-3">
            <div :class="[statuses(selectedFilesWithTagCount), 'flex-none rounded-full p-1']">
                <div class="size-2 rounded-full bg-current"/>
            </div>
            <h2 class="min-w-0 text-sm/6 font-semibold">
                <div class="flex gap-x-2">
                    <span class="truncate">{{ tagDefinition.name }}</span>
                    <span class="text-gray-500">/</span>
                    <span class="whitespace-nowrap">{{ tagDefinition.tag }}</span>
                    <span class="absolute inset-0"/>
                </div>
            </h2>
        </div>
        <div class="mt-3 flex items-center gap-x-2.5 text-xs/5 text-gray-600">
            <p class="truncate">{{ selectedFilesWithTag.map(i => i.name).join(', ') }}</p>
            <svg viewBox="0 0 2 2" class="size-0.5 flex-none fill-gray-500">
                <circle cx="1" cy="1" r="1"/>
            </svg>
            <p>{{ selectedFilesWithTagCount }}</p>
        </div>
    </div>
    <Icon name="ChevronRight" class="size-5 flex-none text-gray-600" aria-hidden="true"/>
</template>

<style scoped>

</style>
