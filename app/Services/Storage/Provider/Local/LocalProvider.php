<?php

namespace App\Services\Storage\Provider\Local;

use App\Services\Storage\Provider\Laravel\LaravelProvider;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class LocalProvider extends LaravelProvider
{
    public function buildStorage(): Filesystem
    {
        return Storage::build([
            'driver' => 'local',
            'root' => storage_path('app/private' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . $this->config->user_id . DIRECTORY_SEPARATOR . 'files'),
        ]);
    }
}
