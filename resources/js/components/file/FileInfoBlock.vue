<script setup lang="ts">
const props = defineProps<{
    header?: string;
    subHeader?: string;
}>();
</script>

<template>
    <div>
        <div
            v-if="props.header || props.subHeader || $slots.actions"
            class="px-4 sm:px-0">
            <div class="flex w-full items-center justify-between">
                <h3 v-if="props.header" class="text-base/7 font-semibold">
                    {{ props.header }}
                </h3>
                <div v-else />
                <slot name="actions" />
            </div>
            <p
                v-if="props.subHeader"
                class="text-secondary mt-1 max-w-2xl text-sm/6">
                {{ props.subHeader }}
            </p>
        </div>
        <div class="border-border mt-3 border-t">
            <dl class="divide-border divide-y">
                <slot />
            </dl>
        </div>
    </div>
</template>

<style scoped></style>
