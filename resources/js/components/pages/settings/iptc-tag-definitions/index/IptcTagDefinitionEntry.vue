<script setup lang="ts">
import {IptcTagDefinition} from "@/types";
import DeleteIptcTagDefinition
    from "@/components/pages/settings/iptc-tag-definitions/index/DeleteIptcTagDefinition.vue";
import IptcTagDefinitionDetailsModal
    from "@/components/pages/settings/iptc-tag-definitions/index/IptcTagDefinitionDetailsModal.vue";
import DataTypeBadge from "@/components/pages/settings/iptc-tag-definitions/index/DataTypeBadge.vue";
import {Link} from "@inertiajs/vue3";

const props = defineProps<{
    definition: IptcTagDefinition;
}>();
</script>

<template>
    <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
        <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0">
                <div class="flex items-center space-x-3">
                    <h4 class="text-sm font-medium text-gray-900 truncate">{{ definition.name }}</h4>
                    <DataTypeBadge :definition="definition" />
                    <span v-if="definition.spec.multiple"
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        Multiple
                    </span>
                    <span v-if="definition.spec.required"
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        Required
                    </span>
                    <span v-if="!definition.user_id"
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                        Global
                    </span>
                </div>
                <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                    <span>Tag: <code class="bg-gray-100 px-1 py-0.5 rounded text-xs">{{ definition.tag }}</code></span>
                    <span v-if="definition.description" class="line-clamp-2"
                          :title="definition.description">{{ definition.description }}</span>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <IptcTagDefinitionDetailsModal :definition="definition"/>
                <Link
                    v-if="definition.user_id"
                    :href="route('settings.iptc-tag-definitions.edit', {'iptc_tag_definition': definition})"
                    class="text-blue-600 hover:text-blue-800 transition-colors"
                    title="Edit definition"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </Link>
                <DeleteIptcTagDefinition v-if="definition.user_id" :definition="definition"/>
            </div>
        </div>
    </div>
</template>
