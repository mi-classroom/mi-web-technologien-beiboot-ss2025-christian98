import { InertiaForm, useForm } from '@inertiajs/vue3';

export interface CreateStorageConfigForm<
    T extends object = Record<string, string>,
> {
    name: string;
    provider_type: string | null;
    provider_options: T;
}

export function useCreateStorageConfigForm<
    T extends object = Record<string, string>,
>(
    data: Partial<CreateStorageConfigForm<T>> = {},
): InertiaForm<CreateStorageConfigForm<T>> {
    // @ts-expect-error IDK why, but TS complains that the types don't match here
    return useForm({
        name: '',
        provider_type: null,
        provider_options: {},
        ...data,
    });
}
