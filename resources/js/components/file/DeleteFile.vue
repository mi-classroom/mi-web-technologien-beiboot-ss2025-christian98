<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { File } from '@/types';

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
    file: File;
    variant?: 'ghost' | 'destructive';
}>();

const form = useForm({});

const deleteFile = (e: Event) => {
    e.preventDefault();

    form.delete(route('files.destroy', { file: props.file }), {
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
            <Button :variant="props.variant ?? 'destructive'">
                <slot>
                    <Icon name="Trash2" />
                    <span>Delete</span>
                </slot>
            </Button>
        </DialogTrigger>
        <DialogContent>
            <form class="space-y-6" @submit="deleteFile">
                <DialogHeader class="space-y-3">
                    <DialogTitle>
                        Are you sure you want to delete the file
                        <span class="italic">{{ props.file.name }}</span
                        >?
                    </DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete '{{ props.file.name }}'?
                        This action cannot be undone and the file will be
                        permanently removed from storage.
                    </DialogDescription>
                </DialogHeader>

                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button variant="outline" @click="closeModal">
                            Cancel
                        </Button>
                    </DialogClose>

                    <Button variant="destructive" :disabled="form.processing">
                        <button type="submit">Delete File</button>
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
