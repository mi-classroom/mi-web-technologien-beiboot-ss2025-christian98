<?php

namespace App\Services\Storage\Provider\Dropbox;

use App\Services\Storage\Provider\Laravel\LaravelProvider;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class DropboxProvider extends LaravelProvider
{
    public function buildStorage(): Filesystem
    {
        return Storage::build([
            'driver' => 'dropbox',
            'accessToken' => $this->config->provider_options['access_token'] ?? '',
            'case_sensitive' => $this->config->provider_options['case_sensitive'] ?? false,
        ]);
    }
}
