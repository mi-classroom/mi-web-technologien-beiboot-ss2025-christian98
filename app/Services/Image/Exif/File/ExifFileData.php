<?php

namespace App\Services\Image\Exif\File;

use DateTimeImmutable;
use Illuminate\Contracts\Support\Arrayable;

class ExifFileData implements Arrayable
{
    /**
     * @param  array<string, string>  $data
     */
    public function __construct(
        protected array $data
    ) {}

    public function get(ExifFileTag $tag): string|int|null
    {
        return $this->data[$tag->value] ?? null;
    }

    public function filename(): ?string
    {
        return $this->get(ExifFileTag::FileName);
    }

    public function dateTime(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromTimestamp((int) $this->get(ExifFileTag::FileDateTime));
    }

    public function size(): int
    {
        return (int) $this->get(ExifFileTag::FileSize);
    }

    public function type(): ?string
    {
        return $this->get(ExifFileTag::FileType); // TODO it should be an enum
    }

    public function mimeType(): ?string
    {
        return $this->get(ExifFileTag::MimeType);
    }

    public function sectionsFound(): ?array
    {
        // TODO parse the sections found with enums
        return $this->get(ExifFileTag::SectionsFound);
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
