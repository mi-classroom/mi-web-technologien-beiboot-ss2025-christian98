<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
    DialogFooter
} from "@/components/ui/dialog";
import {Button} from "@/components/ui/button";
import {Input} from "@/components/ui/input";
import {router} from "@inertiajs/vue3";
import {Folder} from "@/types";
import {reactive, ref} from "vue";
import Icon from "@/components/Icon.vue";
import FilePreview from "@/components/folder/FilePreview.vue";

export interface FileInput {
    name: string;
    file: File;
}

const props = defineProps<{
    folder: Folder,
}>();
const form = reactive<{
    files: FileInput[],
}>({
    files: [],
});
const dialogOpen = ref(false);

function uploadFiles(event: Event) {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files.length > 0) {
        for (const file of input.files) {
            form.files.push({
                name: file.name,
                file: file,
            });
        }
    }
}

function handleCreate() {
    router.post(route('folders.files.store', {folder: props.folder.id}), form);
    dialogOpen.value = false;
}
</script>

<template>
    <Dialog v-model:open="dialogOpen">
        <DialogTrigger as-child>
            <Button>Upload Files</Button>
        </DialogTrigger>
        <DialogContent>
            <form @submit.prevent="handleCreate" class="grid gap-4">
                <DialogHeader>
                    <DialogTitle>Upload Files</DialogTitle>
                </DialogHeader>
                <div class="col-span-full">
                    <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                        <div class="text-center">
                            <Icon name="photo" class="mx-auto size-12 text-gray-300" />
                            <div class="mt-4 flex text-sm/6 text-gray-600">
                                <label
                                    for="file-upload"
                                    class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 focus-within:outline-hidden hover:text-indigo-500"
                                >
                                    <span>Upload a file</span>
                                    <Input @change="uploadFiles" id="file-upload" type="file" multiple class="sr-only"/>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs/5 text-gray-600">PNG, JPG, GIF up to 10MB</p>
                        </div>
                    </div>
                </div>

                <ul role="list" class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-8">
                    <li v-for="file in form.files" :key="file.name" class="relative">
                        <FilePreview :file="file" />
                    </li>
                </ul>

                <DialogFooter>
                    <Button type="submit">Upload</Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
