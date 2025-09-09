<script setup lang="ts">

const model = defineModel<boolean>();

const props = defineProps<{
    id?: string;
    name?: string;
}>()
</script>

<template>
    <div class="flex items-center gap-3">
        <div
            class="group relative inline-flex w-11 shrink-0 rounded-full bg-gray-200 p-0.5 inset-ring inset-ring-gray-900/5 outline-offset-2 outline-primary transition-colors duration-200 ease-in-out has-checked:bg-primary has-focus-visible:outline-2 dark:bg-white/5 dark:inset-ring-white/10 dark:outline-primary dark:has-checked:bg-primary">
            <span
                class="size-5 rounded-full bg-white shadow-xs ring-1 ring-gray-900/5 transition-transform duration-200 ease-in-out group-has-checked:translate-x-5"/>
            <input type="checkbox"
                   v-model="model"
                   :id="id"
                   class="absolute inset-0 appearance-none focus:outline-hidden"
                   :name="name"
                   :aria-labelledby="`${id}-label`"
                   :aria-describedby="`${id}-description`"/>
        </div>

        <div class="text-sm flex space-x-2">
            <label :id="`${id}-label`" :for="id" class="font-medium text-gray-900 dark:text-white">
                <slot/>
            </label>
            <span v-if="$slots.description" :id="`${id}-description`" class="text-gray-500 dark:text-gray-400">
                <slot name="description"/>
            </span>
        </div>
    </div>
</template>
