<script setup lang="ts">
import IptcInput from "@/components/editor/edit-view/IptcInput.vue";
import {IptcTagDefinition} from "@/types";

defineModel<string|undefined>();

defineProps<{
    definition: IptcTagDefinition;
    index: number;
    readonly?: boolean;
    title?: string;
    suffix: string;
}>();
</script>

<template>
    <IptcInput v-bind:model-value="modelValue" type="text" :readonly :title :definition :index :suffix/>
</template>
