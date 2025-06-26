<script setup lang="ts">
import {computed, ref, watch} from "vue";
import {Head} from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import {useQuery} from '@tanstack/vue-query';
import {fetchApi} from "@/lib/fetchApi";
import {type File, IptcItem} from "@/types";
import AttributeList from "@/components/editor/attribute-list/AttributeList.vue";
import EditView from "@/components/editor/edit-view/EditView.vue";
import {cn} from "@/lib/utils";
import FileList from "@/components/editor/file-list/FileList.vue";

const fileIds = ref<number[]>([3, 5, 15]);
const {isPending, isFetching, isError, data, error} = useQuery({
    queryKey: ['files', fileIds],
    queryFn: () => fetchApi<{ data: File[] }>(route('api.files.index', {ids: fileIds.value})),
});
const files = computed(() => data.value?.data);

const selectedFileIds = ref<number[]>([]);
const selectedFiles = computed(() => {
    return files.value?.filter(file => selectedFileIds.value.includes(file.id)) || [];
});

const attributes = computed(() => {
    const attributes = new Map<string, IptcItem[]>();

    selectedFiles.value.forEach(file => {
        file.meta_data?.iptc_items?.forEach(item => {
            if (!attributes.has(item.tag)) {
                attributes.set(item.tag, []);
            }

            item.file = file; // Add the file reference to the item
            attributes.get(item.tag)?.push(item);
        });
    });

    return attributes;
});

const selectedTag = ref<string | null>(null);

watch(selectedFiles, (value) => {
    let tagAvailableInRemainingFiles = false;
    value.forEach(file => {
        if (file.meta_data?.iptc_items?.some(item => item.tag === selectedTag.value)) {
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
                <FileList :files v-model:selectedFileIds="selectedFileIds"/>
            </div>
            <div class="grid-item">
                <AttributeList v-model:selectedTag="selectedTag" v-model:selectedFileIds="selectedFileIds" :allFileIds="fileIds" :selectedFiles :attributes/>
            </div>
            <div :class="cn('grid-item transition-all duration-300 opacity-100 origin-left', {
                'opacity-100 scale-x-100': selectedTag,
                'opacity-0 scale-x-0 pointer-events-none': !selectedTag
            })">
                <EditView v-model:selectedTag="selectedTag" :attributes/>
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
