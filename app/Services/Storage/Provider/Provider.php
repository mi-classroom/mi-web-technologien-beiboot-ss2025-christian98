<?php

namespace App\Services\Storage\Provider;

use App\Models\StorageConfig;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class Provider
{
    public function __construct(
        public readonly StorageConfig $config,
    ) {}

    abstract public function directory(string $path): ?Directory;

    abstract public function makeDirectory(string $path): ?Directory;

    /**
     * List the contents of a directory.
     *
     * @return Collection<Directory|File>
     */
    public function listDirectory(string $path): Collection
    {
        $directory = $this->directory($path);

        if ($directory === null) {
            return collect();
        }

        return $directory->children();
    }

    /**
     * Retrieve a file by its path.
     */
    abstract public function file(string $path): ?File;

    public function download(string $path, ?string $name = null): ?StreamedResponse
    {
        return $this->file($path)?->download($name);
    }

    abstract public function upload(string $path, string $content): ?File;

    public function put(string $path, string $content): ?File
    {
        $file = $this->file($path);

        if ($file !== null) {
            return $file->write($content);
        }

        return $this->upload($path, $content);
    }

    abstract public function exists(mixed $oldPath): bool;

    abstract public function move(mixed $oldPath, mixed $newPath): bool;
}
