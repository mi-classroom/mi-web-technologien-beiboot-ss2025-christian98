<?php

namespace App\Services\Storage\Entities;

use DateTime;
use Spatie\LaravelData\Data;

class File extends Data
{
    use StorageEntity;

    public function __construct(
        public string $name,
        public string $path,
        public ?string $mime_type,
        public ?int $size,
        public ?string $download_url,
        public ?string $preview_url,
        public ?string $thumbnail_url,
        public ?Folder $folder,
        public DateTime $created_at,
        public DateTime $updated_at,
    ) {}
}
