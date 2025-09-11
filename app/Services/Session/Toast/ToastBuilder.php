<?php

namespace App\Services\Session\Toast;

class ToastBuilder
{
    private string $title;

    private ToastType $type = ToastType::Info;

    private ?string $description = null;

    /**
     * @var array<Action>
     */
    private array $actions = [];

    private ?int $duration = null;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function description(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function action(Action $action): static
    {
        $this->actions[] = $action;

        return $this;
    }

    public function type(ToastType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function duration(int $milliseconds): static
    {
        $this->duration = $milliseconds;

        return $this;
    }

    public function flash(): void
    {
        new Toast(
            title: $this->title,
            type: $this->type,
            duration: $this->duration,
            description: $this->description,
            actions: $this->actions,
        )->flash();
    }
}
