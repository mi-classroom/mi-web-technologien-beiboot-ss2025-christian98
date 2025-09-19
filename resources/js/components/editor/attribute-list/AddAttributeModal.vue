<script setup lang="ts">
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { IptcTagDefinition, Resource } from '@/types';
import { Button } from '@/components/ui/button';
import Icon from '@/components/Icon.vue';
import { useQuery } from '@tanstack/vue-query';
import { fetchApi } from '@/lib/fetchApi';
import { computed, ref } from 'vue';
import IptcTagDefinitionEntry from '@/components/pages/settings/iptc-tag-definitions/index/IptcTagDefinitionEntry.vue';

const props = defineProps<{
    existingDefinitions: IptcTagDefinition[];
    disabled?: boolean;
}>();

const open = ref(false);

const { data } = useQuery({
    queryKey: ['iptc-tag-definitions'],
    queryFn: () =>
        fetchApi<Resource<IptcTagDefinition[]>>(
            route('api.iptc-tag-definitions.index'),
        ),
});

const availableDefinitions = computed(() => {
    if (!data?.value?.data) {
        return [];
    }
    return data.value?.data.filter(
        (def) =>
            !props.existingDefinitions.find(
                (existing) => existing.id === def.id,
            ),
    );
});

const emits = defineEmits<{
    (e: 'add', definition: IptcTagDefinition): void;
}>();

function selectDefinition(definition: IptcTagDefinition) {
    emits('add', definition);
    open.value = false;
}
</script>

<template>
    <Dialog v-model:open="open">
        <DialogTrigger as-child>
            <Button
                size="icon"
                title="Add Additional Attributes"
                :disabled="disabled">
                <Icon name="Plus" class="size-4" />
            </Button>
        </DialogTrigger>
        <DialogContent
            class="max-h-[80vh] overflow-y-auto rounded-sm bg-white p-6">
            <div class="mb-4 flex items-center justify-between">
                <DialogHeader class="space-y-3">
                    <DialogTitle>Add new tag</DialogTitle>
                </DialogHeader>
            </div>

            <nav class="h-full overflow-y-auto" aria-label="Directory">
                <div class="relative">
                    <div
                        class="sticky top-0 z-10 border-y border-t-gray-100 border-b-gray-200 bg-gray-50 px-3 py-1.5 text-sm/6 font-semibold text-gray-900 dark:border-t-white/5 dark:border-b-white/10 dark:bg-gray-900 dark:text-white dark:before:pointer-events-none dark:before:absolute dark:before:inset-0 dark:before:bg-white/5">
                        <h3 class="relative">Additional Attributes</h3>
                    </div>
                    <ul
                        role="list"
                        class="divide-y divide-gray-100 dark:divide-white/5">
                        <li
                            v-for="def in availableDefinitions"
                            :key="def.id"
                            @click="selectDefinition(def)">
                            <IptcTagDefinitionEntry
                                :definition="def"
                                hide-actions />
                        </li>
                    </ul>
                </div>
                <div class="relative">
                    <div
                        class="sticky top-0 z-10 border-y border-t-gray-100 border-b-gray-200 bg-gray-50 px-3 py-1.5 text-sm/6 font-semibold text-gray-900 dark:border-t-white/5 dark:border-b-white/10 dark:bg-gray-900 dark:text-white dark:before:pointer-events-none dark:before:absolute dark:before:inset-0 dark:before:bg-white/5">
                        <h3 class="relative">Existing Attributes</h3>
                    </div>
                    <ul
                        role="list"
                        class="divide-y divide-gray-100 dark:divide-white/5">
                        <li
                            v-for="def in existingDefinitions"
                            :key="def.id"
                            @click="selectDefinition(def)">
                            <IptcTagDefinitionEntry
                                :definition="def"
                                hide-actions />
                        </li>
                    </ul>
                </div>
            </nav>

            <DialogFooter class="gap-2">
                <DialogClose as-child>
                    <Button variant="outline"> Cancel </Button>
                </DialogClose>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
