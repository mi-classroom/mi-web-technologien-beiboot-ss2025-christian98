<script setup lang="ts">
import { Head, InertiaForm } from '@inertiajs/vue3';
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import {
    CreateStorageConfigForm,
    useCreateStorageConfigForm,
} from '@/components/pages/settings/storageConfig/create/createStorageConfigForm';
import CreateWebDavForm from '@/components/pages/settings/storageConfig/create/webdav/CreateWebDavForm.vue';
import CreateDropboxForm from '@/components/pages/settings/storageConfig/create/dropbox/CreateDropboxForm.vue';
import { CreateDropboxFormData } from '@/components/pages/settings/storageConfig/create/dropbox/CreateDropboxFormData';
import { Label } from '@/components/ui/label';
import { CreateWebDavFormData } from '@/components/pages/settings/storageConfig/create/webdav/CreateWebDavFormData';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
import { providers } from '@/lib/providers';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Storage settings',
        href: '/settings/storage',
    },
    {
        title: 'Create Storage settings',
        href: '/settings/storage/create',
    },
];

const form = useCreateStorageConfigForm();
const webDavForm = form as unknown as InertiaForm<
    CreateStorageConfigForm<CreateWebDavFormData>
>;
const dropboxForm = form as unknown as InertiaForm<
    CreateStorageConfigForm<CreateDropboxFormData>
>;
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Create Storage settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall
                    title="Storage Settings"
                    description="Connect your own Cloud-Storage" />

                <form
                    @submit.prevent="form.post(route('settings.storage.store'))"
                    class="flex flex-col space-y-6">
                    <div>
                        <Label for="name">Name</Label>
                        <Input
                            id="name"
                            name="name"
                            type="text"
                            placeholder="Nextcloud"
                            v-model="form.name" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div>
                        <Label for="provider_type">Provider</Label>
                        <select
                            id="provider_type"
                            name="provider_type"
                            v-model="form.provider_type"
                            @change="form.provider_options = {}">
                            <option disabled :value="null">
                                Select Provider
                            </option>
                            <option
                                v-for="provider in providers"
                                :key="provider.value"
                                :value="provider.value">
                                {{ provider.label }}
                            </option>
                        </select>
                        <div v-if="form.errors.provider_type">
                            {{ form.errors.provider_type }}
                        </div>
                    </div>

                    <div class="flex flex-col space-y-4">
                        <CreateWebDavForm
                            v-if="form.provider_type === 'webdav'"
                            v-model="webDavForm" />
                        <CreateDropboxForm
                            v-if="form.provider_type === 'dropbox'"
                            v-model="dropboxForm" />
                    </div>

                    <div>
                        <Button onclick="submit">Create</Button>
                    </div>
                </form>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
