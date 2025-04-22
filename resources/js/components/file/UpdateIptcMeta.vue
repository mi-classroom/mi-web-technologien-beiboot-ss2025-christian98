<script setup lang="ts">
import Icon from "@/components/Icon.vue";
import {Button} from "@/components/ui/button";
import {File} from "@/types";
import {Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger} from "@/components/ui/dialog";
import {ref} from "vue";
import {Input} from "@/components/ui/input";
import {useCloned} from "@vueuse/core";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";

const props = defineProps<{
    file: File;
    tag: string;
}>();

const dialogOpen = ref(false);
const {cloned: iptcMeta, sync} = useCloned<(string | number)[]>(props.file.meta_data.iptc?.[props.tag] ?? []);

function updateMeta() {
    router.put(route('files.update', {id: props.file.id}), {
        meta_data: {
            iptc: [
                {
                    tag: props.tag,
                    value: iptcMeta.value,
                },
            ],
        },
    }, {
        onSuccess: () => {
            dialogOpen.value = false;
        }
    })
}

function closeDialog() {
    dialogOpen.value = false;
    sync();
}
</script>

<template>
    <Dialog v-model:open="dialogOpen">
        <DialogTrigger>
            <Button variant="ghost" size="default">
                <Icon name="Pencil"/>
                <span>Edit</span>
            </Button>
        </DialogTrigger>
        <DialogContent>
            <form @submit.prevent="updateMeta" class="grid gap-4">
                <DialogHeader>
                    <DialogTitle>
                        Edit IPTC-Meta: <span class="italic">{{ props.tag }}</span>
                    </DialogTitle>
                </DialogHeader>
                <div class="mx-2">
                    <ul>
                        <li v-for="(l, index) in iptcMeta" class="list-decimal">
                            <div class="flex gap-2 ml-2">
                                <Input
                                    :model-value="l"
                                    @update:model-value="value => iptcMeta[index] = value"
                                    type="text"
                                    class="mb-2"
                                    required
                                    placeholder="Enter IPTC meta data"/>
                                <Button variant="destructive"
                                        size="icon"
                                        @click="iptcMeta.splice(index, 1)">
                                    <Icon name="Trash"/>
                                </Button>
                            </div>
                        </li>
                    </ul>
                    <Button variant="secondary" class="w-full" @click="iptcMeta.push('')">
                        Add Value
                    </Button>
                </div>

                <DialogFooter>
                    <Button variant="ghost" @click="closeDialog">Abort</Button>
                    <Button type="submit">Save</Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>

<style scoped>

</style>
