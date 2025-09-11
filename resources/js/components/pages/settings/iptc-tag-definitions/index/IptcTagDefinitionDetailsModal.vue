<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogTrigger
} from "@/components/ui/dialog";
import {IptcTagDefinition} from "@/types";
import DataTypeBadge from "@/components/pages/settings/iptc-tag-definitions/index/DataTypeBadge.vue";

const props = defineProps<{
    definition: IptcTagDefinition;
}>();
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <button class="text-gray-400 dark:text-gray-300 dark:hover:text-gray-400 hover:text-gray-600 transition-colors" title="View details">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </button>
        </DialogTrigger>
        <DialogContent>
            <div class="flex items-center justify-between mb-4">
                <DialogHeader class="space-y-3">
                    <DialogTitle>Definition Details</DialogTitle>
                </DialogHeader>
            </div>

            <div v-if="definition" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-50">{{ definition.name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tag</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-50"><code
                        class="bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded">{{ definition.tag }}</code></p>
                </div>

                <div v-if="definition.description">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-50">{{ definition.description }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data Type</label>
                        <DataTypeBadge :definition="definition" class="mt-1"/>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Length Range</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-50">{{ definition.spec.min_length }} -
                            {{ definition.spec.max_length }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Multiple Values</label>
                        <span
                            :class="['mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium', definition.spec.multiple ? 'bg-indigo-100 dark:bg-indigo-600 text-indigo-800 dark:text-indigo-100' : 'bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-100']">
                                {{ definition.spec.multiple ? 'Yes' : 'No' }}
                            </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Required</label>
                        <span
                            :class="['mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium', definition.spec.required ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-100' : 'bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-100']">
                                {{ definition.spec.required ? 'Yes' : 'No' }}
                            </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Value Editable</label>
                        <span
                            :class="['mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium', definition.is_value_editable ? 'bg-blue-100 dark:bg-blue-600 text-blue-800 dark:text-blue-100' : 'bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-100']">
                                {{ definition.is_value_editable ? 'Yes' : 'No' }}
                            </span>
                    </div>
                </div>

                <div
                    v-if="definition.spec.enum_values && definition.spec.enum_values.length > 0">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Enum Values</label>
                    <div class="mt-1 flex flex-wrap gap-1">
                            <span
                                v-for="value in definition.spec.enum_values"
                                :key="value"
                                class="inline-flex items-center px-2 py-1 rounded text-xs bg-purple-100 text-purple-800"
                            >
                                {{ value }}
                            </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-4 border-t">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Created</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-50">
                            {{ new Date(definition.created_at).toLocaleDateString() }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Updated</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-50">
                            {{ new Date(definition.updated_at).toLocaleDateString() }}</p>
                    </div>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>

<style scoped>

</style>
