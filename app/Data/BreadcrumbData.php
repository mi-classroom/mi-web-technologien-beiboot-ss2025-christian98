<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class BreadcrumbData extends Data
{
    public function __construct(
        public string $name,
        public string $url,
    ) {}
}
