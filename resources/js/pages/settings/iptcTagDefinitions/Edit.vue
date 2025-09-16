<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, IptcTagDefinition, Resource } from '@/types';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
import { dataTypes } from '@/lib/iptc-tag-definition-data_type';
import Toggle from '@/components/ui/toggle/Toggle.vue';

const props = defineProps<{
    iptcTagDefinition: Resource<IptcTagDefinition>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'IPTC tag definitions',
        href: '/settings/iptc-tag-definitions',
    },
    {
        title: `Edit "${props.iptcTagDefinition.data.name}" definition`,
        href: route('settings.iptc-tag-definitions.edit', {
            iptc_tag_definition: props.iptcTagDefinition.data,
        }),
    },
];

type FormData = Omit<
    IptcTagDefinition,
    'id' | 'created_at' | 'updated_at' | 'user_id' | 'iptcItems'
>;

const form = useForm<FormData>({
    ...props.iptcTagDefinition.data,
});

function submitForm() {
    form.transform((data) => {
        return {
            ...data,
            spec: {
                ...data.spec,
                enum_values:
                    data.spec.data_type === 'enum'
                        ? data.spec.enum_values
                        : null,
            },
        };
    }).put(
        route('settings.iptc-tag-definitions.update', {
            iptc_tag_definition: props.iptcTagDefinition.data,
        }),
    );
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Edit IPTC tag definition" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall
                    title="Edit IPTC tag definition"
                    description="Change your own custom tag definition" />

                <form class="flex flex-col space-y-6">
                    <div>
                        <Label for="name">Name</Label>
                        <Input
                            id="name"
                            name="name"
                            type="text"
                            placeholder="My Tag"
                            v-model="form.name" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div>
                        <Label for="tag">Tag</Label>
                        <Input
                            id="tag"
                            name="tag"
                            type="text"
                            placeholder="2#999"
                            v-model="form.tag" />
                        <InputError :message="form.errors.tag" />
                    </div>

                    <div>
                        <Label for="spec-type">Data Type</Label>
                        <select
                            id="spec-type"
                            name="spec-type"
                            v-model="form.spec.data_type">
                            <option disabled :value="null">
                                Select Data Type
                            </option>
                            <option
                                v-for="dataType in dataTypes"
                                :value="dataType">
                                {{
                                    dataType.charAt(0).toUpperCase() +
                                    dataType.slice(1)
                                }}
                            </option>
                        </select>
                        <div v-if="form.errors['spec.data_type']">
                            {{ form.errors['spec.data_type'] }}
                        </div>
                    </div>

                    <div class="flex flex-col space-y-6">
                        <Toggle
                            id="spec-required"
                            name="spec-required"
                            v-model="form.spec.required">
                            Required
                            <template #description>
                                Whether this field is required to be filled in
                                when editing IPTC data.
                            </template>
                        </Toggle>

                        <Toggle
                            id="spec-multi"
                            name="spec-multi"
                            v-model="form.spec.multiple">
                            Multiple Values
                            <template #description>
                                Whether this field can contain multiple values.
                            </template>
                        </Toggle>

                        <div class="flex w-full space-x-2">
                            <div class="basis-1/2">
                                <Label for="spec-min-length"
                                    >Minimum Length</Label
                                >
                                <Input
                                    id="spec-min-length"
                                    name="spec-min-length"
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    v-model.number="form.spec.min_length" />
                                <InputError
                                    :message="form.errors['spec.min_length']" />
                            </div>

                            <div class="basis-1/2">
                                <Label for="spec-max-length"
                                    >Maximum Length</Label
                                >
                                <Input
                                    id="spec-max-length"
                                    name="spec-max-length"
                                    type="number"
                                    min="1"
                                    placeholder="255"
                                    v-model.number="form.spec.max_length" />
                                <InputError
                                    :message="form.errors['spec.max_length']" />
                            </div>
                        </div>

                        <div v-if="form.spec.data_type === 'enum'">
                            <Label for="spec-enum-values"
                                >Enum Values (one per line)</Label
                            >
                            <textarea
                                id="spec-enum-values"
                                name="spec-enum-values"
                                rows="4"
                                placeholder="Value 1&#10;Value 2&#10;Value 3"
                                :value="form.spec.enum_values?.join('\n')"
                                @input="
                                    form.spec.enum_values = (
                                        $event.target as HTMLTextAreaElement
                                    )?.value.split('\n')
                                " />
                            <InputError
                                :message="form.errors['spec.enum_values']" />
                        </div>
                    </div>

                    <div class="flex flex-col space-y-6">
                        <Toggle
                            id="editable"
                            name="editable"
                            v-model="form.is_value_editable">
                            Value Editable
                            <template #description>
                                Whether the value of this tag can be edited when
                                editing IPTC data.
                            </template>
                        </Toggle>
                    </div>

                    <div class="flex flex-row space-x-2">
                        <Button
                            variant="outline"
                            size="lg"
                            :as="Link"
                            :href="
                                route('settings.iptc-tag-definitions.index')
                            ">
                            Cancel
                        </Button>
                        <Button size="lg" @click.prevent="submitForm"
                            >Save</Button
                        >
                    </div>
                </form>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
