import {Cloud, HardDrive} from "lucide-vue-next";

export const providers = [
    {value: 'webdav', label: 'WebDav'},
    {value: 'dropbox', label: 'Dropbox'},
] as const;

export const providerIconMap = {
    'local': HardDrive,
    'webdav': Cloud,
    'dropbox': Cloud,
};

export const providerColorMap = {
    'local': '#4a5568',
    'webdav': '#0077c2',
    'dropbox': '#0061fe',
};
