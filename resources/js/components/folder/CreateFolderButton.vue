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
import {Folder, StorageConfig} from "@/types";
import {reactive, ref} from "vue";

const props = defineProps<{
    folder: Folder,
    storageConfig: StorageConfig,
}>();
const form = reactive({
    name: '',
});
const dialogOpen = ref(false);

function handleCreate() {
    router.post(route('storage.folders.folders.store', {folder: props.folder, storageConfig: props.storageConfig}), form);
    dialogOpen.value = false;
}
</script>

<template>
    <Dialog v-model:open="dialogOpen">
        <DialogTrigger as-child>
            <Button>Create Folder</Button>
        </DialogTrigger>
        <DialogContent>
            <form @submit.prevent="handleCreate" class="grid gap-4">
                <DialogHeader>
                    <DialogTitle>Create folder</DialogTitle>
                </DialogHeader>
                <Input v-model="form.name"/>
                <DialogFooter>
                    <Button type="submit">Create</Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
