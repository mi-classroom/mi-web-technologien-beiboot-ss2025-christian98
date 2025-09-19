<script setup lang="ts">
import { IptcTagDefinition } from '@/types';
import DeleteIptcTagDefinition from '@/components/pages/settings/iptc-tag-definitions/index/DeleteIptcTagDefinition.vue';
import IptcTagDefinitionDetailsModal from '@/components/pages/settings/iptc-tag-definitions/index/IptcTagDefinitionDetailsModal.vue';
import DataTypeBadge from '@/components/pages/settings/iptc-tag-definitions/index/DataTypeBadge.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    definition: IptcTagDefinition;
    hideActions?: boolean;
}>();
</script>

<template>
    <div
        class="px-6 py-4 transition-colors hover:bg-gray-50 dark:bg-gray-800 hover:dark:bg-gray-700">
        <div class="flex items-center justify-between">
            <div class="min-w-0 flex-1">
                <div class="flex items-center space-x-3">
                    <h4
                        class="truncate text-sm font-medium text-gray-900 dark:text-gray-200">
                        {{ definition.name }}
                    </h4>
                    <DataTypeBadge :definition="definition" />
                    <span
                        v-if="definition.spec.multiple"
                        class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-800 dark:bg-indigo-600 dark:text-indigo-100">
                        Multiple
                    </span>
                    <span
                        v-if="definition.spec.required"
                        class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-100">
                        Required
                    </span>
                    <span
                        v-if="!definition.user_id"
                        class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-600 dark:text-gray-100">
                        Global
                    </span>
                </div>
                <div
                    class="mt-1 flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-300">
                    <span>
                        Tag:
                        <code
                            class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-600">
                            {{ definition.tag }}
                        </code>
                    </span>
                    <span
                        v-if="definition.description"
                        class="line-clamp-2"
                        :title="definition.description"
                        >{{ definition.description }}</span
                    >
                </div>
            </div>
            <div v-if="!hideActions" class="flex items-center space-x-2">
                <IptcTagDefinitionDetailsModal :definition="definition" />
                <Link
                    v-if="definition.user_id"
                    :href="
                        route('settings.iptc-tag-definitions.edit', {
                            iptc_tag_definition: definition,
                        })
                    "
                    class="text-blue-600 transition-colors hover:text-blue-800"
                    title="Edit definition">
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </Link>
                <DeleteIptcTagDefinition
                    v-if="definition.user_id"
                    :definition="definition" />
            </div>
        </div>
    </div>
</template>
