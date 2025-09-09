<script setup lang="ts">
import {useForm} from '@inertiajs/vue3';
import {IptcTagDefinition} from '@/types';

// Components
import {Button} from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent, DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import Icon from "@/components/Icon.vue";

const props = defineProps<{
    definition: IptcTagDefinition;
}>();

const form = useForm({});

const deleteDefinition = (e: Event) => {
    e.preventDefault();

    form.delete(route('settings.iptc-tag-definitions.destroy', {'iptc_tag_definition': props.definition}), {
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
            <Button variant="destructive" size="icon">
                <Icon name="Trash2"/>
                <span class="sr-only">Delete</span>
            </Button>
        </DialogTrigger>
        <DialogContent>
            <form class="space-y-6" @submit="deleteDefinition">
                <DialogHeader class="space-y-3">
                    <DialogTitle>Are you sure you want to delete the definition <span
                        class="italic">{{ props.definition.name }}</span>?
                    </DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete '{{ props.definition.name }}'? This action cannot be undone and
                        it will be deleted from all associated files.
                    </DialogDescription>
                </DialogHeader>

                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button variant="secondary" @click="closeModal"> Cancel</Button>
                    </DialogClose>

                    <Button variant="destructive" :disabled="form.processing">
                        <button type="submit">Delete definition</button>
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
