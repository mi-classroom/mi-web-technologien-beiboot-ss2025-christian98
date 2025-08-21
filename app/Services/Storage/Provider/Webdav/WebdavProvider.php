<?php

namespace App\Services\Storage\Provider\Webdav;

use App\Services\Storage\Provider\Laravel\LaravelProvider;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class WebdavProvider extends LaravelProvider
{
    public function buildStorage(): Filesystem
    {
        return Storage::build([
            'driver' => 'webdav',
            'baseUri' => $storageConfig->provider_options['base_uri'] ?? '',
            'userName' => $storageConfig->provider_options['username'] ?? '',
            'password' => $storageConfig->provider_options['password'] ?? '',
            'pathPrefix' => $storageConfig->provider_options['path_prefix'] ?? parse_url($storageConfig->provider_options['base_uri'], PHP_URL_PATH) ?? '',
        ]);
    }
}
