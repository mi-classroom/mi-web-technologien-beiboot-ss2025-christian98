<?php

namespace App\Services\Storage\Provider\Laravel;

use App\Services\Storage\Provider\Directory;
use App\Services\Storage\Provider\Provider;
use Illuminate\Contracts\Filesystem\Filesystem;

abstract class LaravelProvider extends Provider
{
    abstract public function buildStorage(): Filesystem;

    public function getStorage(): Filesystem
    {
        return $this->filesystem ??= $this->buildStorage();
    }

    public function directory(string $path): ?LaravelDirectory
    {
        $storage = $this->getStorage();

        if (!$storage->exists($path)) {
            return null;
        }

        return new LaravelDirectory($path, $storage);
    }

    public function makeDirectory(string $path): ?Directory
    {
        $storage = $this->getStorage();

        if (! $storage->exists($path)) {
            $storage->makeDirectory($path);
        }

        return $this->directory($path);
    }

    public function file(string $path): ?LaravelFile
    {
        $storage = $this->getStorage();

        if (!$storage->exists($path)) {
            return null;
        }

        return new LaravelFile($path, $storage);
    }

    public function upload(string $path, string $content): ?LaravelFile
    {
        $storage = $this->getStorage();
        $storage->put($path, $content);

        return new LaravelFile($path, $storage);
    }
}
