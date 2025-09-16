<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DropdownMenuPortal } from 'reka-ui';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import Icon from '@/components/Icon.vue';
import { router } from '@inertiajs/vue3';
import { File, IptcItem } from '@/types';
import UpdateIptcMeta from '@/components/file/UpdateIptcMeta.vue';
import { ref } from 'vue';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

const props = defineProps<{
    iptcItem: IptcItem;
}>();

const updateDialogOpen = ref(false);
const deleteDialogOpen = ref(false);

function removeIptcTag() {
    router.put(
        route('storage.iptc.destroy', {
            file: props.iptcItem.id,
        }),
    );
}
</script>

<template>
    <UpdateIptcMeta v-model="updateDialogOpen" :iptcItem="iptcItem" />
    <Dialog v-model:open="deleteDialogOpen">
        <DialogContent>
            <form class="space-y-6" @submit="removeIptcTag">
                <DialogHeader class="space-y-3">
                    <DialogTitle>
                        Are you sure you want to delete
                        {{ props.iptcItem.tag.tag }}?
                    </DialogTitle>
                </DialogHeader>

                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button
                            variant="outline"
                            @click="() => (deleteDialogOpen = false)">
                            Cancel
                        </Button>
                    </DialogClose>

                    <Button variant="destructive">
                        <button type="submit">
                            Delete {{ props.iptcItem.tag.tag }}
                        </button>
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button size="icon" variant="ghost">
                <Icon name="MoreVertical" />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuPortal>
            <DropdownMenuContent>
                <DropdownMenuItem @click="() => (updateDialogOpen = true)">
                    <Icon name="Pencil" />
                    Edit
                </DropdownMenuItem>
                <DropdownMenuItem
                    variant="destructive"
                    @click="() => (deleteDialogOpen = true)">
                    <Icon name="Trash2" />
                    <span>Delete</span>
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenuPortal>
    </DropdownMenu>
</template>
