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
            'baseUri' => $this->config->provider_options['base_uri'] ?? '',
            'userName' => $this->config->provider_options['username'] ?? '',
            'password' => $this->config->provider_options['password'] ?? '',
            'pathPrefix' => $this->config->provider_options['path_prefix'] ?? parse_url($this->config->provider_options['base_uri'], PHP_URL_PATH) ?? '',
        ]);
    }
}
