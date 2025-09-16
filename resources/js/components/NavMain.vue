<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { type NavGroupItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

defineProps<{
    items: NavGroupItem[];
}>();

const page = usePage<SharedData>();
</script>

<template>
    <SidebarGroup v-for="group in items" class="px-2 pt-2 pb-0">
        <SidebarGroupLabel v-if="group.title">
            {{ group.title }}
        </SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in group.items" :key="item.title">
                <SidebarMenuButton
                    as-child
                    :is-active="page.url.startsWith(item.href)"
                    :tooltip="item.title">
                    <Link
                        :href="item.href"
                        :disabled="item.disabled"
                        class="data-[active=true]:bg-sidebar-foreground/10 data-[active=true]:text-sidebar-foreground">
                        <component :is="item.icon" />
                        <span>{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
