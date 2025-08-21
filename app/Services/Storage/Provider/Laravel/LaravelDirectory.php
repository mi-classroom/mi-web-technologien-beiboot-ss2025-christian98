<?php

namespace App\Services\Storage\Provider\Laravel;

use App\Services\Storage\Provider\Directory;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\LazyCollection;

class LaravelDirectory extends Directory
{
    public function __construct(
        string      $fullPath,
        public readonly Filesystem $filesystem,
    ) {
        parent::__construct($fullPath);
    }

    public function children(): LazyCollection
    {
        $dirs = collect($this->filesystem->directories($this->fullPath))->lazy()->map(function (string $dir) {
            return new LaravelDirectory($dir, $this->filesystem);
        });

        $files = collect($this->filesystem->files($this->fullPath))->lazy()->map(function (string $file) {;
            return new LaravelFile($file, $this->filesystem);
        });

        return $dirs->merge($files);
    }

    public function delete(): void
    {
        $this->filesystem->deleteDirectory($this->fullPath);
    }
}
