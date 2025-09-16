<script setup lang="ts">
const props = defineProps<{
    label: string;
}>();
</script>

<template>
    <div>
        <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <div class="inline-flex items-baseline justify-between">
                <dt class="inline text-sm/6 font-medium">
                    {{ props.label }}
                </dt>
            </div>
            <dd class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0">
                <slot />
            </dd>
        </div>
        <div class="flex w-full items-center justify-end gap-2 py-2">
            <slot name="actions" />
        </div>
    </div>
</template>
