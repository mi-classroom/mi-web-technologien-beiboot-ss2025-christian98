<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {type BreadcrumbItem, Folder} from '@/types';
import {Head} from '@inertiajs/vue3';
import {cn} from "@/lib/utils";
import {computed, ref} from "vue";
import Icon from "@/components/Icon.vue";
import {Button, buttonVariants} from "@/components/ui/button";
import {trans} from "laravel-vue-i18n";
import FileInfoAttribute from "@/components/file/FileInfoAttribute.vue";
import IptcMetadataContextMenu from "@/components/file/IptcMetadataContextMenu.vue";
import FileInfoBlock from "@/components/file/FileInfoBlock.vue";
import DeleteFile from "@/components/file/DeleteFile.vue";

const props = defineProps<{
    folder: { data: Folder };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    }
];

const selectedImageId = ref<number | null>();

const selectedImage = computed(() => {
    return props.folder.data.files?.find(file => file.id === selectedImageId.value);
});

function selectImage(id: number) {
    if (selectedImageId.value === id) {
        selectedImageId.value = null;
    } else {
        selectedImageId.value = id;
    }
}
</script>

<template>
    <Head title="Dashboard"/>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div :class="cn('h-full grid gap-4 p-4 overflow-hidden transition-all duration-700 ease-in-out', {
            'grid-cols-2': !selectedImageId,
            'grid-cols-3 w-[150%] translate-x-[-33%]': !!selectedImageId,
        })">
            <div
                class="relative h-full flex flex-col justify-center items-center text-center overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <Icon name="upload" class="mx-auto size-12 text-gray-300"/>
                <div class="mt-4 gap-y-4 flex flex-col text-sm/6 text-gray-600">
                    <p class="pl-1">Drag'n'Drop files or</p>
                    <label for="file-upload" :class="buttonVariants()">
                        <span>Select files</span>
                        <input id="file-upload" name="file-upload" type="file" class="sr-only"/>
                    </label>
                    <p class="text-xs/5 text-gray-600">PNG, JPG, GIF up to 10MB</p>
                </div>
            </div>

            <ul
                class="relative flex flex-col p-1 gap-3 bg-mi-warm-light h-full overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <li v-for="file in props.folder.data.files ?? []" :key="file.id"
                    :aria-selected="selectedImageId === file.id"
                    class="aria-selected:border-2 bg-white aria-selected:border-mi-primary rounded overflow-hidden">
                    <div class="flex h-28">
                        <div class="bg-mi-dark h-full aspect-square cursor-pointer" @click="selectImage(file.id)">
                            <img class="size-full object-contain overflow-clip"
                                 :src="route('local.files.download', {file: file})" :alt="file.name"/>
                        </div>
                        <div class="flex justify-between w-full items-center" :title="file.name">
                            <div class="flex grow flex-col cursor-pointer h-full justify-center pl-4" @click="selectImage(file.id)">
                                <span class="font-bold">{{ file.name }}</span>
                                <span>{{ new Date(file.created_at).toLocaleDateString() }}</span>
                            </div>
                            <div class="pr-4">
                                <Button variant="ghost" as="a" :href="route('local.files.show', {file})">
                                    <Icon name="pencil" class="size-4"/>
                                </Button>
                                <DeleteFile variant="ghost" :file="file">
                                    <Icon name="Trash2"/>
                                </DeleteFile>
                                <Button variant="ghost" as="a"
                                        :href="route('local.files.download', {file})">
                                    <Icon name="download" class="size-4"/>
                                </Button>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

            <div v-if="selectedImage"
                 class="relative border border-sidebar-border/70 dark:border-sidebar-border">
                <div class="aspect-video bg-mi-dark relative w-full max-h-96 overflow-hidden rounded-xl">
                    <img :src="route('local.files.download', {file: selectedImage.id})" alt="Selected file preview"
                         class="size-full object-contain"/>

                    <div class="absolute bottom-0 left-0 right-0 bg-black/50 text-white p-4">
                        <h2 class="text-lg font-semibold">Selected File</h2>
                        <p class="text-sm">{{ selectedImage?.name }}</p>
                        <p class="text-xs">{{ new Date(selectedImage?.created_at).toLocaleString() }}</p>
                    </div>
                </div>

                <div class="px-6">
                    <FileInfoBlock v-if="selectedImage.meta_data?.iptc">
                        <FileInfoAttribute
                            v-for="[blockHeader, metaBlock] in Object.entries(selectedImage.meta_data.iptc)"
                            :label="trans(`iptc_tag.${blockHeader}`)">
                            <template #actions>
                                <IptcMetadataContextMenu :tag="blockHeader" :file="selectedImage"/>
                            </template>
                            <ul class="list-disc">
                                <li v-for="e in metaBlock">{{ e }}</li>
                            </ul>
                        </FileInfoAttribute>
                    </FileInfoBlock>
                    <div v-else class="flex justify-center items-center w-full">
                        <p class="text-sm italic text-secondary-foreground">
                            No IPTC data found.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
