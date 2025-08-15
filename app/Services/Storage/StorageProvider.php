<?php

namespace App\Services\Storage;

use App\Models\StorageConfig;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

enum StorageProvider: string
{
    case Local = 'local';
    // case S3 = 's3';
    // case SFTP = 'sftp';
    // case FTP = 'ftp';
    case WebDAV = 'webdav';
    case Dropbox = 'dropbox';

    public function getBackend(StorageConfig $storageConfig): Filesystem
    {
        return match ($this) {
            self::Local => Storage::build([
                'driver' => 'local',
                'root' => storage_path('app/private' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . $storageConfig->user_id . DIRECTORY_SEPARATOR . 'files'),
            ]),
            // self::S3 => app(S3StorageBackend::class, $parameters),
            // self::SFTP => app(SFTPStorageBackend::class, $parameters),
            // self::FTP => app(FTPStorageBackend::class, $parameters),
            self::WebDAV => Storage::build([
                'driver' => 'webdav',
                'baseUri' => $storageConfig->provider_options['base_uri'] ?? '',
                'userName' => $storageConfig->provider_options['username'] ?? '',
                'password' => $storageConfig->provider_options['password'] ?? '',
                'pathPrefix' => $storageConfig->provider_options['path_prefix'] ?? parse_url($storageConfig->provider_options['base_uri'], PHP_URL_PATH) ?? '',
            ]),
            self::Dropbox => Storage::build([
                'driver' => 'dropbox',
                'accessToken' => $storageConfig->provider_options['access_token'] ?? '',
                'case_sensitive' => $storageConfig->provider_options['case_sensitive'] ?? false,
            ]),
        };
    }
}
