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

export interface NavGroupItem {
    title?: string;
    items: NavLeafItem[];
    icon?: LucideIcon;
    isActive?: boolean;
    disabled?: boolean;
}

export type NavItem = NavGroup | NavLeafItem;

export interface NavGroup {
    title: string;
    hideOverview?: boolean;
    overviewTitle?: string;
    href: string;
    items: NavLeafItem[];
    icon?: LucideIcon;
    isActive?: boolean;
    disabled?: boolean;
}

export interface NavLeafItem {
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
    storageConfigs?: { data: StorageConfig[] };
}

// region Models/Resources
export interface Resource<T> {
    data: T;
}

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
    full_path: string;
    size: number;
    type: string;
    size_for_humans: string;
    meta_data?: {
        exif?: Record<string, Record<string, string | number>>;
        iptc_items?: IptcItem[];
    };
    created_at: string;
    updated_at: string;
    storage_config_id: number;
}

export interface Folder {
    id: number;
    name: string;
    full_path: string;
    parent_id?: number;
    parent?: Folder;
    folders?: Folder[];
    files?: File[];
    created_at: string;
    updated_at: string;
    storage_config_id: number;
}

export interface IptcItem {
    id: number;
    tag: string;
    value: string[];
    file_id: number;
    file?: File;
    created_at: string;
    updated_at: string;
}

export type StorageConfigStatus = 'connected' | 'indexing' | 'error';

export interface StorageConfig {
    id: number;
    name: string;
    status: StorageConfigStatus;
    last_indexed_at: string | null;
    storage_used: number | null;
    user_id: number;
    root_folder_id: number;
    provider_type: App.Services.Storage.StorageProvider;
    provider_options: Record<string, string>;
    is_editable: boolean;
    created_at: string;
    updated_at: string;
}

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;

    folders_count: number;
    notifications_count: number;
    read_notifications_count: number;
    root_folder_count: number;
    tokens_count: number;
    unread_notifications_count: number;

    folders: Folder[];
    rootFolder: Folder;
}

// endregion

export type BreadcrumbItemType = BreadcrumbItem;
