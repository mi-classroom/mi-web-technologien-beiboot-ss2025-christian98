<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { computed } from 'vue';
import { File, type StorageConfig } from '@/types';
import { Button } from '@/components/ui/button';
import Icon from '@/components/Icon.vue';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import FileInfoBlock from '@/components/file/FileInfoBlock.vue';
import FileInfoAttribute from '@/components/file/FileInfoAttribute.vue';
import DeleteFile from '@/components/file/DeleteFile.vue';
import IptcMetadataContextMenu from '@/components/file/IptcMetadataContextMenu.vue';
import FilePreview from '@/components/file/FilePreview.vue';

const props = defineProps<{
    storageConfig: { data: StorageConfig };
    file: { data: File };
    breadcrumbs: App.Data.BreadcrumbData[];
}>();

const breadcrumbs = computed(() => {
    return props.breadcrumbs.map((folder) => ({
        title: folder.name != '' ? folder.name : '/',
        href: folder.url,
    }));

    return [
        ...folderBreadcrumbs,
        {
            title: props.file.data.name,
            href: route('storage.files.show', {
                file: props.file.data,
                storageConfig: props.storageConfig.data,
            }),
        },
    ];
});
</script>

<template>
    <Head title="Local Files" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mt-4 px-4 py-2 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-base font-semibold">
                        {{ props.file.data.name }}
                    </h1>
                    <p class="mt-2 text-sm">View and edit this file.</p>
                </div>
                <div class="mt-4 flex gap-x-1 sm:mt-0 sm:ml-16 sm:flex-none">
                    <Button
                        variant="default"
                        as="a"
                        :href="
                            route('storage.files.download', {
                                file: props.file.data.id,
                                storageConfig: props.storageConfig.data,
                            })
                        ">
                        <Icon name="ArrowDownToLine" />
                        <span>Download</span>
                    </Button>
                    <DeleteFile :file="props.file.data" />
                </div>
            </div>
            <div class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div
                        class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <Card>
                                <CardHeader>Preview</CardHeader>
                                <CardContent class="flex items-center">
                                    <FilePreview
                                        :file="props.file.data"
                                        class="h-auto w-full rounded-xl" />
                                </CardContent>
                            </Card>
                            <Card>
                                <CardHeader>Details</CardHeader>
                                <CardContent>
                                    <dl class="divide-border divide-y">
                                        <div
                                            class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                            <dt class="text-sm/6 font-medium">
                                                Filename
                                            </dt>
                                            <dd
                                                class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0">
                                                {{ props.file.data.name }}
                                            </dd>
                                        </div>
                                        <div
                                            class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                            <dt class="text-sm/6 font-medium">
                                                Size
                                            </dt>
                                            <dd
                                                class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0">
                                                {{
                                                    props.file.data
                                                        .size_for_humans
                                                }}
                                            </dd>
                                        </div>
                                        <div
                                            class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                            <dt class="text-sm/6 font-medium">
                                                MIME-Type
                                            </dt>
                                            <dd
                                                class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0">
                                                {{ props.file.data.type }}
                                            </dd>
                                        </div>
                                        <div
                                            class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                            <dt class="text-sm/6 font-medium">
                                                Uploaded at
                                            </dt>
                                            <dd
                                                class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0">
                                                {{
                                                    new Date(
                                                        props.file.data.created_at,
                                                    ).toLocaleString(
                                                        undefined,
                                                        {
                                                            dateStyle: 'long',
                                                            timeStyle: 'medium',
                                                        },
                                                    )
                                                }}
                                            </dd>
                                        </div>
                                    </dl>
                                </CardContent>
                            </Card>
                            <Card>
                                <CardHeader>Exif</CardHeader>
                                <CardContent class="space-y-6">
                                    <template
                                        v-if="props.file.data.meta_data?.exif">
                                        <FileInfoBlock
                                            v-for="[
                                                blockHeader,
                                                metaBlock,
                                            ] in Object.entries(
                                                props.file.data.meta_data.exif,
                                            )"
                                            :key="blockHeader"
                                            :header="blockHeader">
                                            <FileInfoAttribute
                                                v-for="[
                                                    label,
                                                    attribute,
                                                ] in Object.entries(metaBlock)"
                                                :key="label"
                                                :label="label">
                                                {{ attribute }}
                                            </FileInfoAttribute>
                                        </FileInfoBlock>
                                    </template>
                                    <div
                                        v-else
                                        class="flex w-full items-center justify-center">
                                        <p
                                            class="text-secondary-foreground text-sm italic">
                                            No Exif data found.
                                        </p>
                                    </div>
                                </CardContent>
                            </Card>
                            <Card>
                                <CardHeader>IPTC</CardHeader>
                                <CardContent>
                                    <FileInfoBlock
                                        v-if="
                                            props.file.data.meta_data
                                                ?.iptc_items
                                        ">
                                        <FileInfoAttribute
                                            v-for="iptcItem in props.file.data
                                                .meta_data.iptc_items"
                                            :key="iptcItem.id"
                                            :label="iptcItem.tag.name">
                                            <template #actions>
                                                <IptcMetadataContextMenu
                                                    :iptcItem="iptcItem" />
                                            </template>
                                            <ul class="list-disc">
                                                <li
                                                    v-for="(
                                                        e, idx
                                                    ) in iptcItem.value"
                                                    :key="idx">
                                                    {{ e }}
                                                </li>
                                            </ul>
                                        </FileInfoAttribute>
                                    </FileInfoBlock>
                                    <div
                                        v-else
                                        class="flex w-full items-center justify-center">
                                        <p
                                            class="text-secondary-foreground text-sm italic">
                                            No IPTC data found.
                                        </p>
                                    </div>
                                </CardContent>
                            </Card>
                            <Card>
                                <Collapsible>
                                    <CollapsibleTrigger
                                        class="group w-full text-left">
                                        <CardHeader
                                            class="flex items-center justify-between">
                                            <span>Raw Meta</span>
                                            <Icon name="ChevronDown" />
                                        </CardHeader>
                                    </CollapsibleTrigger>
                                    <CollapsibleContent>
                                        <CardContent class="text-sm">
                                            <pre class="text-wrap">
                                                <span
                                                    v-for="(line, idx) in JSON.stringify(props.file.data.meta_data, undefined, 2).split('\n')" :key="idx">
                                                    {{ line }}
                                                </span>
                                            </pre>
                                        </CardContent>
                                    </CollapsibleContent>
                                </Collapsible>
                            </Card>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
pre {
    counter-reset: line;
}

pre span {
    display: block;
    line-height: 1.5rem;
}

pre span:before {
    counter-increment: line;
    content: counter(line);
    display: inline-block;
    border-right: 1px solid #ddd;
    padding: 0 0.5em;
    margin-right: 0.5em;
    color: #888;
}
</style>
