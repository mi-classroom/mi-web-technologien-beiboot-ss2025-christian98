<?php

namespace App\Services\Storage;

use App\Models\StorageConfig;
use App\Services\Storage\Provider\Dropbox\DropboxProvider;
use App\Services\Storage\Provider\Local\LocalProvider;
use App\Services\Storage\Provider\Provider;
use App\Services\Storage\Provider\Webdav\WebdavProvider;

enum StorageProvider: string
{
    case Local = 'local';
    // case S3 = 's3';
    // case SFTP = 'sftp';
    // case FTP = 'ftp';
    case WebDAV = 'webdav';
    case Dropbox = 'dropbox';

    public function getBackend(StorageConfig $storageConfig): Provider
    {
        return match ($this) {
            self::Local => app(LocalProvider::class, ['config' => $storageConfig]),
            // self::S3 => app(S3StorageBackend::class, $parameters),
            // self::SFTP => app(SFTPStorageBackend::class, $parameters),
            // self::FTP => app(FTPStorageBackend::class, $parameters),
            self::WebDAV => app(WebdavProvider::class, ['config' => $storageConfig]),
            self::Dropbox => app(DropboxProvider::class, ['config' => $storageConfig]),
        };
    }
}
