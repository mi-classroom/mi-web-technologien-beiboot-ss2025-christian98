<script setup lang="ts">
import { ToastClose, ToastDescription, ToastRoot, ToastTitle } from 'reka-ui';
import Icon from '@/components/Icon.vue';
import { Toast } from '@/types';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{ message: Toast }>();

interface Colors {
    barColor: string;
    iconColor: string;
    icon: string;
}

const colors = computed<Colors>(() => {
    const colorMap: Record<Toast['type'], Colors> = {
        success: {
            barColor: 'bg-green-500',
            iconColor: 'text-green-500',
            icon: 'CheckCircle',
        },
        error: {
            barColor: 'bg-red-500',
            iconColor: 'text-red-500',
            icon: 'Exclamation',
        },
        warning: {
            barColor: 'bg-yellow-500',
            iconColor: 'text-yellow-500',
            icon: 'Exclamation',
        },
        info: {
            barColor: 'bg-blue-500',
            iconColor: 'text-blue-500',
            icon: 'Info',
        },
    };

    return colorMap[props.message.type] ?? colorMap.info;
});

const emits = defineEmits<{
    (e: 'closed'): void;
}>();

function updatedOpen(open: boolean) {
    // You can handle the open state change here if needed
    if (!open) {
        emits('closed');
    }
}
</script>

<template>
    <ToastRoot
        :duration="message.duration"
        @update:open="updatedOpen"
        #default="{ remaining, duration }"
        class="pointer-events-auto relative w-full max-w-md overflow-hidden rounded-xl bg-gray-600 text-white shadow-lg">
        <!-- Colored bar -->
        <div
            :class="[
                'absolute top-0 left-0 h-full w-3 rounded-l-xl',
                colors.barColor,
            ]"
            aria-hidden="true"></div>
        <div
            class="relative grid grid-cols-[auto_1fr] gap-x-3 gap-y-2 py-5 pr-4 pl-6">
            <div class="flex w-fit items-center justify-self-start">
                <Icon
                    :name="colors.icon"
                    :class="['size-4', colors.iconColor]"
                    aria-hidden="true" />
            </div>
            <div class="flex min-w-0 items-center justify-between">
                <div class="flex items-center gap-3">
                    <ToastTitle as="p" class="text-base font-bold text-white">
                        {{ message.title }}
                    </ToastTitle>
                </div>
                <ToastClose
                    class="focus:outline-primary inline-flex rounded-md text-gray-300 hover:text-white focus:outline-2 focus:outline-offset-2">
                    <span class="sr-only">Close</span>
                    <Icon name="x" class="text-2xl" aria-hidden="true" />
                </ToastClose>
            </div>
            <div class="col-start-2 row-start-2 min-w-0">
                <ToastDescription
                    as="p"
                    class="text-sm font-normal text-gray-200">
                    {{ message.description }}
                </ToastDescription>
                <span class="text-sm text-gray-300">
                    {{
                        new Intl.DateTimeFormat('de', {
                            timeStyle: 'short',
                        }).format(new Date(message.timestamp))
                    }}
                </span>
                <div class="mt-2 flex items-center gap-3">
                    <div v-for="action in message.actions">
                        <Button
                            v-if="'href' in action"
                            :as="Link"
                            :href="action.href"
                            variant="outline"
                            class="text-black">
                            {{ action.label }}
                        </Button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Progress bar -->
        <div
            :class="[
                'absolute bottom-0 left-0 h-1 rounded-bl-xl',
                colors.barColor,
            ]"
            :style="{
                width: `${(remaining / duration) * 100}%`,
                transition: 'width 0.1s linear',
            }"></div>
    </ToastRoot>
</template>

<style scoped>
/* Add a subtle drop shadow for the toast */
.ToastRoot {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
}
</style>
