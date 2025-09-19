<?php

namespace App\Services\Storage\Provider\Laravel;

use App\Services\Storage\Provider\Directory;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

class LaravelDirectory extends Directory
{
    public function __construct(
        string $fullPath,
        public readonly Filesystem $filesystem,
    ) {
        parent::__construct($fullPath);
    }

    public function children(): Collection
    {
        $dirs = collect($this->filesystem->directories($this->fullPath))->map(function (string $dir) {
            return new LaravelDirectory($dir, $this->filesystem);
        });

        $files = collect($this->filesystem->files($this->fullPath))->map(function (string $file) {
            return new LaravelFile($file, $this->filesystem);
        });

        /** @var Collection<LaravelDirectory|LaravelFile> $result */
        $result = collect();

        return $result->combine($dirs)->combine($files);
    }

    public function delete(): void
    {
        $this->filesystem->deleteDirectory($this->fullPath);
    }
}
