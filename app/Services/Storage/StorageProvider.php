<?php

namespace App\Services\Storage;

enum StorageProvider: string
{
    case Local = 'local';
    // case S3 = 's3';
    // case SFTP = 'sftp';
    // case FTP = 'ftp';
    // case WebDAV = 'webdav';

    public function getBackend(array $parameters = []): StorageBackend
    {
        return match ($this) {
            self::Local => app(LocalStorageBackend::class, $parameters),
            // self::S3 => app(S3StorageBackend::class, $parameters),
            // self::SFTP => app(SFTPStorageBackend::class, $parameters),
            // self::FTP => app(FTPStorageBackend::class, $parameters),
            // self::WebDAV => app(WebDAVStorageBackend::class, $parameters),
        };
    }
}
