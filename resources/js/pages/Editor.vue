<script setup lang="ts">
import {computed, ref, watch} from "vue";
import {Head} from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import {IptcItem} from "@/types";
import AttributeList from "@/components/editor/attribute-list/AttributeList.vue";
import EditView from "@/components/editor/edit-view/EditView.vue";
import {cn} from "@/lib/utils";
import FileList from "@/components/editor/file-list/FileList.vue";
import {useEditorData} from "@/composables/useEditorData";

const fileIds = ref<number[]>([]);
const {files, tagDefinitions} = useEditorData(fileIds);

const selectedFileIds = ref<number[]>([]);
const selectedFiles = computed(() => {
    return files.value?.filter(file => selectedFileIds.value.includes(file.id)) || [];
});

const attributes = computed(() => {
    const attributes = new Map<number, IptcItem[]>();

    selectedFiles.value.forEach(file => {
        file.meta_data?.iptc_items?.forEach(item => {
            if (!attributes.has(item.tag.id)) {
                attributes.set(item.tag.id, []);
            }

            item.file = file; // Add the file reference to the item
            attributes.get(item.tag.id)?.push(item);
        });
    });

    return attributes;
});

const selectedTag = ref<number | null>(null);

watch(selectedFiles, (value) => {
    let tagAvailableInRemainingFiles = false;
    value.forEach(file => {
        if (file.meta_data?.iptc_items?.some(item => item.tag.id === selectedTag.value)) {
            tagAvailableInRemainingFiles = true;
        }
    });
    if (!tagAvailableInRemainingFiles) {
        selectedTag.value = null;
    }
})
</script>

<template>
    <Head title="IPTC Editor"/>

    <AppLayout>
        <div
            :class="cn('grid h-full gap-x-0.5 transition-all duration-300', {
                'grid-cols-[1fr_1fr_2fr]': selectedTag,
                'grid-cols-[1fr_1fr_0fr]': !selectedTag
            })">
            <div class="grid-item">
                <FileList :files v-model:selectedFileIds="selectedFileIds" @selected="f => fileIds.push(...f.map(fi => fi.id))"/>
            </div>
            <div class="grid-item">
                <AttributeList v-model:selectedTag="selectedTag" v-model:selectedFileIds="selectedFileIds" :allFileIds="fileIds" :selectedFiles :attributes="tagDefinitions"/>
            </div>
            <div :class="cn('grid-item transition-all duration-300 opacity-100 origin-left', {
                'opacity-100 scale-x-100': selectedTag,
                'opacity-0 scale-x-0 pointer-events-none': !selectedTag
            })">
                <EditView v-model:selectedTag="selectedTag" :attributes :selectedFiles :fileIds/>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
@reference "../../css/app.css";

.grid-item {
    @apply p-1 flex flex-col overflow-hidden h-full bg-mi-warm-light dark:bg-mi-warm-dark m-1 rounded-xl;
}
</style>
