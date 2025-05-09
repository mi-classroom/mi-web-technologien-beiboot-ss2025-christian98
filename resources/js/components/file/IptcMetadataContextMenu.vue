<script setup lang="ts">
import {Button} from "@/components/ui/button";
import {DropdownMenuPortal} from "reka-ui";
import {DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger} from "@/components/ui/dropdown-menu";
import Icon from "@/components/Icon.vue";
import {router} from "@inertiajs/vue3";
import {File} from "@/types";
import UpdateIptcMeta from "@/components/file/UpdateIptcMeta.vue";
import {ref} from "vue";
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogFooter,
    DialogHeader, DialogTitle
} from "@/components/ui/dialog";

const props = defineProps<{
    file: File;
    tag: string;
}>();

const updateDialogOpen = ref(false);
const deleteDialogOpen = ref(false);

function removeIptcTag(tag: string) {
    router.put(route('files.update', {
        file: props.file.id,
    }), {
        meta_data: {
            iptc: [
                {
                    tag: tag,
                    value: [],
                },
            ],
        },
    });
}
</script>

<template>
    <UpdateIptcMeta v-model="updateDialogOpen" :file="props.file" :tag="props.tag"/>
    <Dialog v-model:open="deleteDialogOpen">
        <DialogContent>
            <form class="space-y-6" @submit="() => removeIptcTag(tag)">
                <DialogHeader class="space-y-3">
                    <DialogTitle>Are you sure you want to delete {{ props.tag }}?</DialogTitle>
                </DialogHeader>

                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button variant="outline" @click="() => deleteDialogOpen = false"> Cancel</Button>
                    </DialogClose>

                    <Button variant="destructive">
                        <button type="submit">Delete {{ props.tag }}</button>
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button size="icon" variant="ghost">
                <Icon name="MoreVertical"/>
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuPortal>
            <DropdownMenuContent>
                <DropdownMenuItem @click="() => updateDialogOpen = true">
                    <Icon name="Pencil"/>
                    Edit
                </DropdownMenuItem>
                <DropdownMenuItem variant="destructive" @click="() => deleteDialogOpen= true">
                    <Icon name="Trash2"/>
                    <span>Delete</span>
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenuPortal>
    </DropdownMenu>
</template>
