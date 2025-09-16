<script setup lang="ts">
import { InertiaForm } from '@inertiajs/vue3';
import { CreateStorageConfigForm } from '@/components/pages/settings/storageConfig/create/createStorageConfigForm';
import { CreateDropboxFormData } from '@/components/pages/settings/storageConfig/create/dropbox/CreateDropboxFormData';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';

const props = defineProps<{
    form: InertiaForm<CreateStorageConfigForm<CreateDropboxFormData>>;
}>();
</script>

<template>
    <span>
        Creating a new Dropbox connection is not fully supported, because we
        would need to implement OAuth2 flow. The provider backend is functional
        and could be used by retrieving a token manually.
    </span>
    <div>
        You can create a new App and generate an Access Token here:
        <a
            href="https://www.dropbox.com/developers/apps"
            target="_blank"
            class="text-blue-600 underline">
            https://www.dropbox.com/developers/apps
        </a>
        . Make sure to select "Full Dropbox" access. Give the following
        permissions to the app:
        <pre>files.content.read</pre>
        <pre>files.content.write</pre>
        <pre>files.metadata.read</pre>
        <pre>files.metadata.write</pre>
        Then copy the generated token into the field below.
    </div>

    <div>
        <Label for="access_token">AccessToken</Label>
        <Input
            id="access_token"
            name="access_token"
            type="text"
            v-model="form.provider_options.access_token" />
        <InputError :message="form.errors['provider_options.access_token']" />
    </div>
</template>
