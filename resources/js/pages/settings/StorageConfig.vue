<script setup lang="ts">
import {Head, Link, router} from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import {type BreadcrumbItem, StorageConfig, StorageConfigStatus} from '@/types';
import {RefreshCw, Settings, Trash2} from 'lucide-vue-next';
import Icon from "@/components/Icon.vue";
import ProviderIcon from "@/components/pages/settings/index/ProviderIcon.vue";
import {Button} from "@/components/ui/button";

interface Props {
    configs: { data: StorageConfig[] }
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
        error: 'Error'
    };
    return statusMap[status] || 'Unknown';
}

function getStatusTitle(status: StorageConfigStatus) {
    const titleMap = {
        connected: 'Successfully connected and synchronized',
        indexing: 'Currently indexing files',
        error: 'Connection error - click reconnect to fix'
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
    alert('Reconnect functionality is not implemented yet.');
}

function disconnect(provider: StorageConfig) {
    if (confirm(`Are you sure you want to remove ${provider.name}?`)) {
        router.delete(route('settings.storage.destroy', {config: provider}));
    }
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Profile settings"/>

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Storage Settings" description="Connect your own Cloud-Storage"/>

                <div>
                    <div
                        v-for="provider in props.configs.data"
                        :key="provider.id"
                        class="flex flex-col items-center p-4 mb-3 bg-gray-50 border border-gray-200 rounded-lg hover:shadow-md transition-all duration-200"
                    >
                        <div class="flex items-center justify-between w-full">
                            <!-- Provider Icon -->
                            <ProviderIcon :config="provider"/>

                            <!-- Provider Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between">
                                    <div class="text-lg font-semibold text-gray-900 mb-1">
                                        {{ provider.name }}
                                        <span class="text-gray-500 text-sm ml-2">
                                            {{ provider.provider_type }}
                                        </span>
                                    </div>
                                    <span
                                        :class="[
                                            'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium uppercase tracking-wide',
                                            provider.status === 'connected' ? 'bg-green-100 text-green-800' :
                                            provider.status === 'error' ? 'bg-red-100 text-red-800' :
                                            'bg-blue-100 text-blue-800'
                                        ]"
                                        :title="getStatusTitle(provider.status)"
                                    >
                                        <span v-if="provider.status === 'indexing'" class="syncing-indicator mr-1">
                                            <Icon name="Loader2" class="w-3 h-3 animate-spin"></Icon>
                                        </span>
                                        {{ getStatusText(provider.status) }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between gap-4 text-sm text-gray-600">
                                    <span class="font-medium text-gray-700">{{ provider.storage_used }} used</span>
                                    <span class="text-gray-500 text-xs">
                                        Last indexed: {{
                                            provider.last_indexed_at ? formatDate(new Date(provider.last_indexed_at)) : 'Never'
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex w-full justify-end mt-4 gap-2">
                            <Button v-if="provider.status === 'error'" @click="reconnect(provider)">
                                <RefreshCw class="w-4 h-4"></RefreshCw>
                                Reconnect
                            </Button>
                            <Link
                                v-else-if="provider.status === 'connected'"
                                :href="route('settings.storage.re-index', {config: provider})"
                                method="post"
                                class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md border border-gray-200 hover:bg-gray-200 hover:border-gray-300 transition-all duration-200 flex items-center gap-2"
                            >
                                <RefreshCw class="w-4 h-4"></RefreshCw>
                                Index Now
                            </Link>
                            <Link
                                v-if="provider.is_editable"
                                :href="route('settings.storage.edit', {config: provider})"
                                class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md border border-gray-200 hover:bg-gray-200 hover:border-gray-300 transition-all duration-200 flex items-center gap-2"
                            >
                                <Settings class="w-4 h-4"/>
                                Settings
                            </Link>
                            <Button variant="destructive" @click="disconnect(provider)">
                                <Trash2 class="w-4 h-4"/>
                                Remove
                            </Button>
                        </div>
                    </div>

                    <!-- Add Provider Button -->
                    <Link
                        :href="route('settings.storage.create')"
                        class="flex items-center justify-center p-6 mt-6 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 font-medium cursor-pointer hover:border-blue-500 hover:text-blue-500 hover:bg-gray-50 transition-all duration-200"
                    >
                        <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                        Add New Cloud Storage Provider
                    </Link>
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
