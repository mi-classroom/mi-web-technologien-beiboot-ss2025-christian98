<script setup lang="ts">
import { InertiaForm } from '@inertiajs/vue3';
import { CreateStorageConfigForm } from '@/components/pages/settings/storageConfig/create/createStorageConfigForm';
import { CreateWebDavFormData } from '@/components/pages/settings/storageConfig/create/webdav/CreateWebDavFormData';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';

const form = defineModel<
    InertiaForm<CreateStorageConfigForm<CreateWebDavFormData>>
>({
    required: true,
});
</script>

<template>
    <div>
        <Label for="base_uri">Base-Uri</Label>
        <Input
            id="base_uri"
            name="base_uri"
            type="text"
            placeholder="https://..."
            v-model="form.provider_options.base_uri" />
        <InputError :message="form.errors['provider_options.base_uri']" />
    </div>

    <div>
        <Label for="username">Username</Label>
        <Input
            id="username"
            name="username"
            type="text"
            v-model="form.provider_options.username" />
        <InputError :message="form.errors['provider_options.username']" />
    </div>

    <div>
        <Label for="username">Password</Label>
        <Input
            id="password"
            name="password"
            type="password"
            v-model="form.provider_options.password" />
        <InputError :message="form.errors['provider_options.password']" />
    </div>
</template>
