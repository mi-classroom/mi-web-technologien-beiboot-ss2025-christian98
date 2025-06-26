<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem
} from '@/components/ui/sidebar';
import {NavGroupItem, type NavLeafItem} from '@/types';
import {Link} from '@inertiajs/vue3';
import {BookOpen, Cloud, Folder, FolderGit2, LayoutGrid, Plus, NotebookPen} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavGroupItem[] = [
    {
        items: [
            {
                title: 'Dashboard',
                href: route('dashboard', undefined, false),
                icon: LayoutGrid,
            },
            {
                title: 'Editor',
                href: route('editor', undefined, false),
                icon: NotebookPen,
            },
        ],
    },
    {
        title: 'Cloud Storage',
        items: [
            {
                title: 'Local Files',
                href: route('local', undefined, false),
                icon: Folder,
            },
            {
                title: 'Nextcloud',
                href: '/cloud-storage/nextcloud',
                icon: Cloud,
                disabled: true,
            },
            {
                title: 'Connect Cloud-Storage',
                href: '/settings/cloud-storage',
                icon: Plus,
                disabled: true,
            },
        ],
    },
];

const footerNavItems: NavLeafItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/mi-classroom/mi-web-technologien-beiboot-ss2025-christian98',
        icon: FolderGit2,
    },
    {
        title: 'Issues',
        href: 'https://github.com/mi-classroom/mi-master-wt-beiboot-2025/issues',
        icon: FolderGit2,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo/>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems"/>
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems"/>
            <NavUser/>
        </SidebarFooter>
    </Sidebar>
    <slot/>
</template>
