<?php

namespace App\Services\Image\IPTC;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class IptcData implements Arrayable
{
    /**
     * @param  array<string, array<int, string>>  $data
     */
    public function __construct(
        protected array $data = []
    ) {}

    public function get(string $key): ?array
    {
        return $this->data[($key)] ?? null;
    }

    public function getFirst(string $key): ?string
    {
        return $this->data[($key)][0] ?? null;
    }

    public function getAll(): array
    {
        return $this->data;
    }

    public function has(string $key): bool
    {
        return isset($this->data[($key)]);
    }

    public function set(string $key, array $value): void
    {
        $this->data[($key)] = $value;
    }

    public function push(string $key, string $value): void
    {
        if (! isset($this->data[($key)])) {
            $this->data[($key)] = [];
        }

        $this->data[($key)][] = $value;
    }

    public function remove(string $key, ?int $index = null): void
    {
        if ($index !== null) {
            unset($this->data[($key)][$index]);

            return;
        }

        unset($this->data[($key)]);
    }

    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @return Collection<string, array<int, string>>>
     */
    public function collect(): Collection
    {
        return collect($this->data);
    }
}
