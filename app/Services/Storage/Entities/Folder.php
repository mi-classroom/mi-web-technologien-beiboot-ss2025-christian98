<?php

namespace App\Services\Storage\Entities;

use DateTime;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class Folder extends Data
{
    use StorageEntity;

    /**
     * @param  Collection<File>|null  $files
     * @param  Collection<Folder>|null  $folders
     */
    public function __construct(
        public string $name,
        public string $path,
        public DateTime $created_at,
        public DateTime $updated_at,
        public ?Collection $files = null,
        public ?Collection $folders = null,
        public ?Folder $parent = null,
    ) {}
}
