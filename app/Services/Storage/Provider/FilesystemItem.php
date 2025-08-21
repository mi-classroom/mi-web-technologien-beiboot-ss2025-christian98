<?php

namespace App\Services\Storage\Provider;

abstract class FilesystemItem
{
    public readonly string $fullPath;

    public function __construct(string $fullPath)
    {
        $this->fullPath = trim($fullPath, '/');
    }

    public function name(): string
    {
        return basename($this->fullPath);
    }
}
