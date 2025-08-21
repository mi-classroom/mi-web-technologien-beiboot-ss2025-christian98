<script setup lang="ts">
import {Minus, RotateCcw} from "lucide-vue-next";
import {Button} from "@/components/ui/button";
import {IptcItem, File, IptcTagDefinition} from "@/types";
import {computed, ref, watch} from "vue";
import Icon from "@/components/Icon.vue";
import {DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger} from "@/components/ui/dropdown-menu";
import {DropdownMenuPortal} from "reka-ui";
import IptcInputFactory from "@/components/editor/edit-view/IptcInputFactory.vue";

const props = defineProps<{
    file: File;
    selectedTag: IptcTagDefinition;
}>();

const emit = defineEmits<{
    (event: 'save', item: IptcItem | File, newValue: string[]): void;
    (event: 'remove', item: IptcItem): void;
}>();

const item = computed(() => {
    return props.file.meta_data?.iptc_items?.find(i => i.tag.id === props.selectedTag.id);
});

const newValue = ref<string[]>(item.value ? [...item.value.value] : []);

function prependValue(value: string[]) {
    newValue.value.unshift(...value);
}

function appendValue(value: string[]) {
    newValue.value.push(...value);
}

function overwrite(value: string[]) {
    newValue.value = value;
}

function save() {
    console.log(newValue.value);
    emit('save', item.value ?? props.file, newValue.value);
}

defineExpose({save, prependValue, appendValue, overwrite});

watch([item, () => props.selectedTag], () => {
    newValue.value = item.value ? [...item.value.value] : [];
});

function resetValue(index: number) {
    if (item?.value?.value[index]) {
        newValue.value[index] = item.value.value[index];
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
</script>

<template>
    <li class="py-4 ml-12">
        <div class="flex justify-between">
            <label class="block text-sm/6 font-medium text-gray-900">{{ file.name }}</label>
            <div class="flex gap-1">
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button size="icon" variant="ghost">
                            <Icon name="MoreVertical"/>
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuPortal>
                        <DropdownMenuContent align="end">
                            <DropdownMenuItem title="Save attribute for file" @click="save">
                                <Icon name="Save" class="size-4"/>
                                <span>Save file</span>
                            </DropdownMenuItem>
                            <DropdownMenuItem title="Reset attribute for file"
                                              @click="e => newValue = [...item?.value ?? []]">
                                <RotateCcw class="size-4"/>
                                <span>Reset file</span>
                            </DropdownMenuItem>
                            <DropdownMenuItem v-if="item" title="Remove attribute from file"
                                              @click="$emit('remove', item)">
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
                        <label :for="`iptc-${selectedTag.id}-${idx}-current`"
                               class="px-0.5 text-xs/5 text-gray-500 sr-only"
                               :data-heading="idx === 0 ? true : undefined">
                            Current value
                        </label>
                        <IptcInputFactory :model-value="item?.value[idx]" :definition="selectedTag" :index="idx"
                                          title="Current Value" suffix="current" readonly />
                    </div>
                    <div class="basis-1/2">
                        <label :for="`iptc-${selectedTag.id}-${idx}-new`" class="text-xs/5 text-gray-500 px-0.5 sr-only"
                               :data-heading="idx === 0 ? true : undefined">
                            New value
                        </label>
                        <IptcInputFactory v-model="newValue[idx]" :definition="selectedTag" :index="idx"
                                          title="New/Edited Value" suffix="new" />
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
        <div v-if="item?.tag.spec.multiple || newValue.length < 1" class="mt-2">
            <Button variant="outline" class="w-full" @click="addValue">
                <Icon name="Plus" class="size-4"/>
                <span>Add Value</span>
            </Button>
        </div>
    </li>
</template>
