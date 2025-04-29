<?php

namespace App\Services\Image\IPTC;

use Illuminate\Contracts\Support\Arrayable;

class IptcData implements Arrayable
{
    /**
     * @param  array<string, array<int, string>>  $data
     */
    public function __construct(
        protected array $data
    ) {}

    public function get(IptcTag $key): ?array
    {
        return $this->data[$key->toString()] ?? null;
    }

    public function getFirst(IptcTag $key): ?string
    {
        return $this->data[$key->toString()][0] ?? null;
    }

    public function getAll(): array
    {
        return $this->data;
    }

    public function set(IptcTag $key, array $value): void
    {
        $this->data[$key->toString()] = $value;
    }

    public function push(IptcTag $key, string $value): void
    {
        if (! isset($this->data[$key->toString()])) {
            $this->data[$key->toString()] = [];
        }

        $this->data[$key->toString()][] = $value;
    }

    public function remove(IptcTag $key, ?int $index = null): void
    {
        if ($index !== null) {
            unset($this->data[$key->toString()][$index]);

            return;
        }

        unset($this->data[$key->toString()]);
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function toReadableArray(): array
    {
        return collect($this->data)
            ->mapWithKeys(function (array $value, string $key) {
                return [IptcTag::tryFromString($key)?->name ?? $key => $value];
            })
            ->toArray();
    }
}
