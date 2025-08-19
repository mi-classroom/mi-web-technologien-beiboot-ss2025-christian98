<?php

namespace App\Services\Storage\Provider;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

abstract class Directory extends FilesystemItem
{

    /**
     * @return LazyCollection<File|Directory>
     */
    abstract public function children(): LazyCollection;
}
