<script setup lang="ts">
const props = defineProps<{
    label: string;
}>()
</script>

<template>
    <div>

        <div
            class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <div class="items-baseline justify-between inline-flex">
                <dt class="text-sm/6 font-medium inline">
                    {{ props.label }}
                </dt>
            </div>
            <dd class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0">
                <slot/>
            </dd>
        </div>
        <div class="flex items-center justify-end gap-2 w-full py-2">
            <!-- TODO Convert to meatball menu -->
            <slot name="actions"/>
        </div>
    </div>
</template>
