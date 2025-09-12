<?php

namespace App\Services\Session\Toast;

readonly class LinkAction implements Action
{
    public function __construct(
        public string $label,
        public string $href,
    ) {}

    public static function make(string $label, string $href): LinkAction
    {
        return new self($label, $href);
    }

    public function toArray()
    {
        return [
            'label' => $this->label,
            'href' => $this->href,
        ];
    }
}
