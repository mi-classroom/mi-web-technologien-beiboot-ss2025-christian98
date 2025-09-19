<?php

namespace App\Services\Session\Toast;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

final class Toast implements Arrayable
{
    private const string SESSION_KEY = 'status';

    public readonly string $id;

    public readonly CarbonImmutable $timestamp;

    /**
     * @param  array<Action>  $actions
     */
    public function __construct(
        public readonly string $title,
        public readonly ToastType $type,
        public readonly ?int $duration,
        public readonly ?string $description = null,
        public readonly array $actions = [],
    ) {
        $this->id = Str::uuid()->toString();
        $this->timestamp = CarbonImmutable::now();
    }

    /**
     * @return array<Toast>
     */
    public static function all(): array
    {
        $sessionValue = Session::get(self::SESSION_KEY);

        if ($sessionValue instanceof self) {
            return [$sessionValue];
        }

        if (is_array($sessionValue)) {
            return array_filter(array_map(fn ($item) => $item instanceof self ? $item : null, $sessionValue));
        }

        /**
         * This part is needed as Fortify uses the same key. We cannot extend the behaviour of Fortify to serialize
         * the desired value. So we will parse the primitive string into a Status object, so we can handle this
         * without noticing any difference.
         */
        if (is_string($sessionValue)) {
            return [self::info($sessionValue)->build()];
        }

        return [];
    }

    public function flash(): void
    {
        $current = self::all();
        $current[] = $this;

        Session::flash(self::SESSION_KEY, $current);
    }

    public static function exists(): bool
    {
        return Session::has(self::SESSION_KEY);
    }

    protected static function new(string $title): ToastBuilder
    {
        return resolve(ToastBuilder::class, ['title' => $title]);
    }

    public static function success(string $title): ToastBuilder
    {
        return self::new($title)->type(ToastType::Success);
    }

    public static function info(string $title): ToastBuilder
    {
        return self::new($title)->type(ToastType::Info);
    }

    public static function warning(string $title): ToastBuilder
    {
        return self::new($title)->type(ToastType::Warning);
    }

    public static function error(string $title): ToastBuilder
    {
        return self::new($title)->type(ToastType::Error);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type->value,
            'duration' => $this->duration,
            'actions' => array_map(fn (Action $action) => $action->toArray(), $this->actions),
            'timestamp' => $this->timestamp->toIso8601String(),
        ];
    }
}
