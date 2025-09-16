<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, IptcTagDefinition, Resource } from '@/types';
import IptcTagDefinitionEntry from '@/components/pages/settings/iptc-tag-definitions/index/IptcTagDefinitionEntry.vue';
import { Button } from '@/components/ui/button';

interface Props {
    globalDefinitions: Resource<IptcTagDefinition[]>;
    customDefinitions: Resource<IptcTagDefinition[]>;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'IPTC tag definitions',
        href: '/settings/iptc-tag-definitions',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="IPTC Tag definitions" />

        <SettingsLayout>
            <div class="flex flex-col space-y-8">
                <div class="flex items-center justify-between">
                    <HeadingSmall
                        title="IPTC Tag definitions"
                        description="Manage custom and view global IPTC tag definitions" />
                    <Button
                        :as="Link"
                        :href="route('settings.iptc-tag-definitions.create')">
                        Add Custom Definition
                    </Button>
                </div>

                <!-- Custom IPTC tag definitions -->
                <div
                    class="rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-gray-900">
                    <div
                        class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-gray-50">
                            Custom IPTC Tag Definitions
                        </h3>
                        <p
                            class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            Definitions you can edit and delete
                        </p>
                    </div>

                    <div
                        v-if="customDefinitions.data.length === 0"
                        class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        <div class="mb-2 text-gray-400">
                            <svg
                                class="mx-auto h-12 w-12"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="text-lg font-medium">No custom definitions</p>
                        <p class="text-sm">
                            Create your first custom IPTC tag definition to get
                            started
                        </p>
                    </div>

                    <div
                        v-else
                        class="divide-y divide-gray-200 dark:divide-gray-700">
                        <IptcTagDefinitionEntry
                            v-for="definition in customDefinitions.data"
                            :key="definition.id"
                            :definition="definition" />
                    </div>
                </div>
            </div>

            <!-- Global IPTC tag definitions -->
            <div
                class="rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-gray-900">
                <div
                    class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-gray-50">
                        Global IPTC Tag Definitions
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                        Standard definitions (read-only)
                    </p>
                </div>

                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <IptcTagDefinitionEntry
                        v-for="definition in globalDefinitions.data"
                        :key="definition.id"
                        :definition="definition" />
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
