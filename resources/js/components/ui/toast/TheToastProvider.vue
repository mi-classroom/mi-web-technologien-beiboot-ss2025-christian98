<script setup lang="ts">
import { ToastProvider, ToastViewport } from 'reka-ui';
import { Toast as ToastType } from '@/types';
import Toast from '@/components/ui/toast/Toast.vue';
import { useToasts } from '@/composables/useToasts';

const { messages } = useToasts();

function toastClosed(closedMessage: ToastType) {
    messages.value = messages.value.filter(
        (message) => message.id !== closedMessage.id,
    );
}
</script>

<template>
    <ToastProvider>
        <Toast
            v-for="message in messages"
            :key="message.id"
            :message
            @closed="toastClosed(message)" />

        <ToastViewport
            class="fixed top-0 right-0 z-[2147483647] m-0 flex w-[390px] max-w-[100vw] list-none flex-col gap-[10px] p-[var(--viewport-padding)] outline-none [--viewport-padding:_25px]" />
    </ToastProvider>
</template>
