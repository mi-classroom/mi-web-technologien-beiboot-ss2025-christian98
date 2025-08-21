<script setup lang="ts">
import IptcStringInput from "@/components/editor/edit-view/IptcStringInput.vue";
import {IptcTagDefinition} from "@/types";
import IptcDateInput from "@/components/editor/edit-view/IptcDateInput.vue";
import IptcNumberInput from "@/components/editor/edit-view/IptcNumberInput.vue";
import IptcTimeInput from "@/components/editor/edit-view/IptcTimeInput.vue";
import IptcEnumInput from "@/components/editor/edit-view/IptcEnumInput.vue";

const model = defineModel<string|undefined>({
    get(value) {
        return value;
    },
    set(value) {
        return value;
    }
});

defineProps<{
    definition: IptcTagDefinition;
    index: number;
    title?: string;
    readonly?: boolean;
    suffix: string;
}>();

</script>

<template>
    <IptcStringInput v-if="definition.spec.data_type === 'string' || definition.spec.data_type === 'binary'" v-model="model" :suffix :definition :index :title :readonly/>
    <IptcNumberInput v-if="definition.spec.data_type === 'number'" v-model="model" :suffix :definition :index :title :readonly/>
    <IptcDateInput v-if="definition.spec.data_type === 'date'" v-model="model" :suffix :definition :index :title :readonly/>
    <IptcTimeInput v-if="definition.spec.data_type === 'time'" v-model="model" :suffix :definition :index :title :readonly/>
    <IptcEnumInput v-if="definition.spec.data_type === 'enum'" v-model="model" :suffix :definition :index :title :readonly/>
</template>
