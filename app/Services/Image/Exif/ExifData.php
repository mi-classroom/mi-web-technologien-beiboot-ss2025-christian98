<?php

namespace App\Services\Image\Exif;

use Illuminate\Contracts\Support\Arrayable;

class ExifData implements Arrayable
{
    /**
     * @param  array<string, array<string, string>>  $data
     */
    public function __construct(
        protected array $data
    ) {}

    public function get(ExifGroup $key): ?array
    {
        return $this->data[$key->value] ?? null;
    }

    public function file(): ExifFileData
    {
        return new ExifFileData($this->get(ExifGroup::File));
    }

    public function getAll(): array
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
