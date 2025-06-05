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

    public function get(IptcTag|string $key): ?array
    {
        return $this->data[self::tagValue($key)] ?? null;
    }

    public function getFirst(IptcTag|string $key): ?string
    {
        return $this->data[self::tagValue($key)][0] ?? null;
    }

    public function getAll(): array
    {
        return $this->data;
    }

    public function has(IptcTag|string $key): bool
    {
        return isset($this->data[self::tagValue($key)]);
    }

    public function set(IptcTag|string $key, array $value): void
    {
        $this->data[self::tagValue($key)] = $value;
    }

    public function push(IptcTag|string $key, string $value): void
    {
        if (! isset($this->data[self::tagValue($key)])) {
            $this->data[self::tagValue($key)] = [];
        }

        $this->data[self::tagValue($key)][] = $value;
    }

    public function remove(IptcTag|string $key, ?int $index = null): void
    {
        if ($index !== null) {
            unset($this->data[self::tagValue($key)][$index]);

            return;
        }

        unset($this->data[self::tagValue($key)]);
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

    public function toReadableArray(): array
    {
        return collect($this->data)
            ->mapWithKeys(function (array $value, string $key) {
                return [IptcTag::tryFromString($key)?->name ?? $key => $value];
            })
            ->toArray();
    }

    public static function tagValue(IptcTag|string $tag): string
    {
        return is_string($tag) ? $tag : $tag->toString();
    }
}
