<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import {
    type BreadcrumbItem,
    StorageConfig,
    StorageConfigStatus,
} from '@/types';
import { RefreshCw, Settings, Trash2 } from 'lucide-vue-next';
import Icon from '@/components/Icon.vue';
import ProviderIcon from '@/components/pages/settings/index/ProviderIcon.vue';
import { Button } from '@/components/ui/button';
import StorageSize from '@/components/pages/settings/index/StorageSize.vue';

interface Props {
    configs: { data: StorageConfig[] };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Storage settings',
        href: '/settings/storage',
    },
];

function getStatusText(status: StorageConfigStatus) {
    const statusMap = {
        connected: 'Connected',
        indexing: 'Indexing',
        error: 'Error',
    };
    return statusMap[status] || 'Unknown';
}

function getStatusTitle(status: StorageConfigStatus) {
    const titleMap = {
        connected: 'Successfully connected and synchronized',
        indexing: 'Currently indexing files',
        error: 'Connection error - click reconnect to fix',
    };
    return titleMap[status] || '';
}

function formatDate(date: Date) {
    const now = new Date();
    const diff = now.getTime() - date.getTime();

    if (diff < 60000) return 'Just now';
    if (diff < 3600000) return `${Math.floor(diff / 60000)}m ago`;
    if (diff < 86400000) return `${Math.floor(diff / 3600000)}h ago`;
    return `${Math.floor(diff / 86400000)}d ago`;
}

function reconnect(provider: StorageConfig) {
    // TODO: Implement reconnect logic
    alert(`Reconnect functionality is not implemented yet for ${provider.name}.`);
}

function disconnect(provider: StorageConfig) {
    if (confirm(`Are you sure you want to remove ${provider.name}?`)) {
        router.delete(route('settings.storage.destroy', { config: provider }));
    }
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall
                    title="Storage Settings"
                    description="Connect your own Cloud-Storage" />

                <div>
                    <div
                        v-for="provider in props.configs.data"
                        :key="provider.id"
                        class="mb-3 flex flex-col items-center rounded-lg border border-gray-200 bg-gray-50 p-4 transition-all duration-200 hover:shadow-md dark:border-gray-500 dark:bg-gray-800">
                        <div class="flex w-full items-center justify-between">
                            <!-- Provider Icon -->
                            <ProviderIcon :config="provider" />

                            <!-- Provider Info -->
                            <div class="min-w-0 flex-1">
                                <div class="flex justify-between">
                                    <div
                                        class="mb-1 text-lg font-semibold text-gray-900 dark:text-gray-200">
                                        {{ provider.name }}
                                        <span
                                            class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                            {{ provider.provider_type }}
                                        </span>
                                    </div>
                                    <span
                                        :class="[
                                            'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium tracking-wide uppercase',
                                            provider.status === 'connected'
                                                ? 'bg-green-100 text-green-800'
                                                : provider.status === 'error'
                                                  ? 'bg-red-100 text-red-800'
                                                  : 'bg-blue-100 text-blue-800',
                                        ]"
                                        :title="
                                            getStatusTitle(provider.status)
                                        ">
                                        <span
                                            v-if="
                                                provider.status === 'indexing'
                                            "
                                            class="syncing-indicator mr-1">
                                            <Icon
                                                name="Loader2"
                                                class="h-3 w-3 animate-spin"></Icon>
                                        </span>
                                        {{ getStatusText(provider.status) }}
                                    </span>
                                </div>
                                <div
                                    class="flex items-center justify-between gap-4 text-sm text-gray-600">
                                    <span
                                        class="font-medium text-gray-700 dark:text-gray-500">
                                        <StorageSize
                                            :size="provider.storage_used" />
                                    </span>
                                    <span
                                        class="text-xs text-gray-500 dark:text-gray-400">
                                        Last indexed:
                                        {{
                                            provider.last_indexed_at
                                                ? formatDate(
                                                      new Date(
                                                          provider.last_indexed_at,
                                                      ),
                                                  )
                                                : 'Never'
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-4 flex w-full justify-end gap-2">
                            <Button
                                v-if="provider.status === 'error'"
                                @click="reconnect(provider)">
                                <RefreshCw class="h-4 w-4"></RefreshCw>
                                Reconnect
                            </Button>
                            <Link
                                v-else-if="provider.status === 'connected'"
                                :href="
                                    route('settings.storage.re-index', {
                                        config: provider,
                                    })
                                "
                                method="post"
                                class="flex items-center gap-2 rounded-md border border-gray-200 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 hover:border-gray-300 hover:bg-gray-200">
                                <RefreshCw class="h-4 w-4"></RefreshCw>
                                Index Now
                            </Link>
                            <Link
                                v-if="provider.is_editable"
                                :href="
                                    route('settings.storage.edit', {
                                        config: provider,
                                    })
                                "
                                class="flex items-center gap-2 rounded-md border border-gray-200 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 hover:border-gray-300 hover:bg-gray-200">
                                <Settings class="h-4 w-4" />
                                Settings
                            </Link>
                            <Button
                                variant="destructive"
                                @click="disconnect(provider)">
                                <Trash2 class="h-4 w-4" />
                                Remove
                            </Button>
                        </div>
                    </div>

                    <!-- Add Provider Button -->
                    <Link
                        :href="route('settings.storage.create')"
                        class="mt-6 flex cursor-pointer items-center justify-center rounded-lg border-2 border-dashed border-gray-300 p-6 font-medium text-gray-600 transition-all duration-200 hover:border-blue-500 hover:bg-gray-50 hover:text-blue-500">
                        <i data-lucide="plus" class="mr-2 h-5 w-5"></i>
                        Add New Cloud Storage Provider
                    </Link>
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
