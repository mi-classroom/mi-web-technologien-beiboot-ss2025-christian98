import type {PageProps} from '@inertiajs/core';
import type {LucideIcon} from 'lucide-vue-next';
import type {Config} from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
    disabled?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    locale: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
}

// region Models/Resources
export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface File {
    id: number;
    name: string;
    path: string;
    size: number;
    type: string;
    size_for_humans: string;
    meta_data?: {
        exif?: Record<string, Record<string, string|number>>;
        iptc?: Record<string, string[]>;
    };
    created_at: string;
    updated_at: string;
}

export interface Folder {
    id: number;
    name: string;
    path: string;
    parent_id?: number;
    folders?: Folder[];
    files?: File[];
    created_at: string;
    updated_at: string;
}

// endregion

export type BreadcrumbItemType = BreadcrumbItem;
