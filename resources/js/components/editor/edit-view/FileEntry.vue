<script setup lang="ts">
import {Minus, RotateCcw} from "lucide-vue-next";
import {Button} from "@/components/ui/button";
import {IptcItem} from "@/types";
import {ref} from "vue";
import Icon from "@/components/Icon.vue";
import {DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger} from "@/components/ui/dropdown-menu";
import {DropdownMenuPortal} from "reka-ui";

const props = defineProps<{
    item: IptcItem;
}>();

const newValue = ref<string[]>([...props.item.value]);

function resetValue(index: number) {
    if (props.item.value[index]) {
        newValue.value[index] = props.item.value[index];
    } else {
        newValue.value[index] = '';
    }
}

function removeValue(index: number) {
    newValue.value.splice(index, 1);
}

function addValue() {
    newValue.value.push('');
}

const emit = defineEmits<{
    (event: 'save', item: IptcItem, newValue: string[]): void;
    (event: 'remove', item: IptcItem): void;
}>();
</script>

<template>
    <div class="flex justify-between">
        <label class="block text-sm/6 font-medium text-gray-900">{{ item.file?.name }}</label>
        <div class="flex gap-1">
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <Button size="icon" variant="ghost">
                        <Icon name="MoreVertical"/>
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuPortal>
                    <DropdownMenuContent align="end">
                        <DropdownMenuItem title="Save attribute for file" @click="$emit('save', item, newValue)">
                            <Icon name="Save" class="size-4"/>
                            <span>Save file</span>
                        </DropdownMenuItem>
                        <DropdownMenuItem title="Reset attribute for file" @click="e => newValue = [...item.value]">
                            <RotateCcw class="size-4"/>
                            <span>Reset file</span>
                        </DropdownMenuItem>
                        <DropdownMenuItem title="Remove attribute from file" @click="$emit('remove', item)">
                            <Icon name="Trash2" class="size-4"/>
                            <span>Remove file</span>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenuPortal>
            </DropdownMenu>
        </div>
    </div>
    <div class="mt-2 flex flex-col gap-1">
        <div v-for="(_, idx) in newValue" class="flex items-end gap-1 **:data-heading:first:not-sr-only">
            <div class="flex grow gap-1">
                <div class="basis-1/2">
                    <label :for="`iptc-${item.tag}-${idx}-current`" class="px-0.5 text-xs/5 text-gray-500 sr-only"
                           :data-heading="idx === 0 ? true : undefined">
                        Current value
                    </label>
                    <input :id="`iptc-${item.tag}-${idx}-current`" :name="`iptc-${item.tag}-${idx}-current`" type="text"
                           class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 disabled:outline-gray-200 sm:text-sm/6 cursor-text"
                           disabled readonly :value="item.value[idx]" title="Current value"/>
                </div>
                <div class="basis-1/2">
                    <label :for="`iptc-${item.tag}-${idx}-new`" class="text-xs/5 text-gray-500 px-0.5 sr-only"
                           :data-heading="idx === 0 ? true : undefined">
                        New value
                    </label>
                    <input :id="`iptc-${item.tag}-${idx}-new`" :name="`iptc-${item.tag}-${idx}-new`" type="text"
                           v-model="newValue[idx]"
                           class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 disabled:outline-gray-200 sm:text-sm/6"/>
                </div>
            </div>
            <div class="flex-none flex gap-1">
                <Button title="Reset Value" @click="resetValue(idx)">
                    <RotateCcw class="size-4"/>
                </Button>
                <Button title="Remove value" variant="destructive" @click="removeValue(idx)">
                    <Minus class="size-4"/>
                </Button>
            </div>
        </div>
    </div>
    <div class="mt-2">
        <Button variant="outline" class="w-full" @click="addValue">
            <Icon name="Plus" class="size-4"/>
            <span>Add Value</span>
        </Button>
    </div>
</template>

<style scoped>

</style>
