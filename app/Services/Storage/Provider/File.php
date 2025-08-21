<?php

namespace App\Services\Storage\Provider;

use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class File extends FilesystemItem
{
    abstract public function content(): string;

    abstract public function size(): ?int;

    abstract public function lastModified(): ?int;

    abstract public function mimeType(): ?string;

    abstract public function download(?string $name = null): StreamedResponse;

    abstract public function write(string $content): ?File;
}
