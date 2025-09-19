import { useQuery } from '@tanstack/vue-query';
import { fetchApi } from '@/lib/fetchApi';
import type { File, IptcTagDefinition } from '@/types';
import { computed, ref, Ref } from 'vue';

export function useEditorData(fileIds: Ref<number[], number[]>) {
    const { data } = useQuery({
        queryKey: ['files', fileIds],
        queryFn: () =>
            fetchApi<{ data: File[] }>(
                route('api.files.index', { ids: fileIds.value }),
            ),
    });
    const tagDefinitions = ref(new Map<number, IptcTagDefinition>());

    const files = computed(() => {
        const responseFiles = data.value?.data || [];

        for (const file of responseFiles) {
            // Check if file has meta_data and iptc_items
            if (file.meta_data?.iptc_items) {
                for (const iptcItem of file.meta_data.iptc_items) {
                    // Add to map if not already present (ensures uniqueness by ID)
                    if (tagDefinitions.value.has(iptcItem.tag.id)) {
                        // If the tag already exists, update the reference in the iptcItem
                        iptcItem.tag = tagDefinitions.value.get(
                            iptcItem.tag.id,
                        )!;
                    } else {
                        tagDefinitions.value.set(iptcItem.tag.id, iptcItem.tag);
                    }

                    // Ensure iptcItems array exists and push the current item once
                    iptcItem.tag.iptcItems =
                        iptcItem.tag.iptcItems?.filter(
                            (item) => item.id !== iptcItem.id,
                        ) || [];
                    (iptcItem.tag.iptcItems ??= []).push(iptcItem);
                }
            }
        }

        return data.value?.data;
    });

    return { files, tagDefinitions } as const;
}
