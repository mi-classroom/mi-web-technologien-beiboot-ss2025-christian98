<?php

namespace App\Services;

use App\Models\Folder;

class FullPathGenerator
{
    public function getFullPath(?Folder $parent, string $name): string
    {
        if ($parent === null || $parent->full_path === DIRECTORY_SEPARATOR) {
            return DIRECTORY_SEPARATOR . $name;
        }

        return $parent->full_path . DIRECTORY_SEPARATOR . $name;
    }
}
