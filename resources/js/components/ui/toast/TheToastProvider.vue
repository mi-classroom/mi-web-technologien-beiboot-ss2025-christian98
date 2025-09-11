<script setup lang="ts">
import {ToastProvider, ToastViewport} from "reka-ui";
import {Toast as ToastType} from "@/types";
import Toast from "@/components/ui/toast/Toast.vue";
import {useToasts} from "@/composables/useToasts";

const {messages} = useToasts();

function toastClosed(closedMessage: ToastType) {
    messages.value = messages.value.filter(message => message.id !== closedMessage.id);
}
</script>

<template>
    <ToastProvider>
        <Toast v-for="message in messages" :key="message.id" :message @closed="toastClosed(message)"/>

        <ToastViewport
            class="[--viewport-padding:_25px] fixed top-0 right-0 flex flex-col p-[var(--viewport-padding)] gap-[10px] w-[390px] max-w-[100vw] m-0 list-none z-[2147483647] outline-none"/>
    </ToastProvider>
</template>
