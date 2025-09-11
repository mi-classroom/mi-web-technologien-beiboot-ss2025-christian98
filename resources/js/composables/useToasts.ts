import {ref, watch} from "vue";
import {SharedData, Toast as ToastType, ToastAction} from "@/types";
import {usePage} from "@inertiajs/vue3";

const messages = ref<ToastType[]>([]);

function toast(toast: Omit<ToastType, 'id' | 'timestamp'>) {
    const id = crypto.randomUUID();
    messages.value.push({...toast, id, timestamp: new Date().toISOString()});
}

// region Sync with Inertia messages
const page = usePage<SharedData>();
watch(page, (page) => {
    messages.value.push(...(page.props.messages || []));
});

// endregion

export function useToasts() {
    return {messages, toast} as const;
}
