<?php

namespace App\Services\Image\Exif;

use Illuminate\Contracts\Support\Arrayable;

class ExifData implements Arrayable
{
    /**
     * @param  array<string, string>  $data
     */
    public function __construct(
        protected array $data
    ) {}

    public function get(ExifTag $key): ?string
    {
        return $this->data[$key->toString()] ?? null;
    }

    public function getAll(): array
    {
        return $this->data;
    }

    public function set(ExifTag $key, string $value): void
    {
        $this->data[$key->toString()] = $value;
    }

    public function remove(ExifTag $key): void
    {
        unset($this->data[$key->toString()]);
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
