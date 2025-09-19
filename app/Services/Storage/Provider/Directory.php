<?php

namespace App\Services\Storage\Provider;

use Illuminate\Support\Collection;

abstract class Directory extends FilesystemItem
{
    /**
     * @return Collection<File|Directory>
     */
    abstract public function children(): Collection;

    abstract public function delete(): void;
}
