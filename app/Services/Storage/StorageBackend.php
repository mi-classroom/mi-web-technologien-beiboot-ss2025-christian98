<?php

namespace App\Services\Storage;

use App\Services\Storage\Entities\File;
use App\Services\Storage\Entities\Folder;

interface StorageBackend
{
    public function getFile(string $path): File;

    public function getFolder(string $path): Folder;

    public function getRoot();
}
