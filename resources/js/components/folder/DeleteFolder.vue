<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Folder } from '@/types';

// Components
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import Icon from '@/components/Icon.vue';

const props = defineProps<{
    folder: Folder;
}>();

const form = useForm({});

const deleteFolder = (e: Event) => {
    e.preventDefault();

    form.delete(route('folders.destroy', { folder: props.folder }), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button variant="destructive">
                <Icon name="Trash2" />
                <span>Delete</span>
            </Button>
        </DialogTrigger>
        <DialogContent>
            <form class="space-y-6" @submit="deleteFolder">
                <DialogHeader class="space-y-3">
                    <DialogTitle>
                        Are you sure you want to delete the folder
                        <span class="italic">{{ props.folder.name }}</span
                        >?
                    </DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete '{{
                            props.folder.name
                        }}'? This action cannot be undone and all contents will
                        be permanently removed from storage.
                    </DialogDescription>
                </DialogHeader>

                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button variant="secondary" @click="closeModal">
                            Cancel
                        </Button>
                    </DialogClose>

                    <Button variant="destructive" :disabled="form.processing">
                        <button type="submit">Delete Folder</button>
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
