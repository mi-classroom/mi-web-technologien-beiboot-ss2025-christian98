<?php

namespace App\Services\Storage\Provider\Laravel;

use App\Services\Storage\Provider\File;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaravelFile extends File
{
    public function __construct(
        string $fullPath,
        public readonly Filesystem $filesystem,
    ) {
        parent::__construct($fullPath);
    }

    public function content(): string
    {
        return $this->filesystem->get($this->fullPath);
    }

    public function size(): ?int
    {
        return rescue(fn () => $this->filesystem->size($this->fullPath), 0);
    }

    public function lastModified(): ?int
    {
        return rescue(fn () => $this->filesystem->lastModified($this->fullPath));
    }

    public function mimeType(): ?string
    {
        return rescue(fn () => $this->filesystem->mimeType($this->fullPath), 'application/octet-stream');
    }

    public function download(?string $name = null): StreamedResponse
    {
        return $this->filesystem->download($this->fullPath, $name ?? $this->name());
    }

    public function write(string $content): ?File
    {
        $this->filesystem->put($this->fullPath, $content);

        return new self($this->fullPath, $this->filesystem);
    }
}
