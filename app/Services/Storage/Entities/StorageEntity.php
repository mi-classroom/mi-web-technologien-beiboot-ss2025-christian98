<?php

namespace App\Services\Storage\Entities;

trait StorageEntity
{
    public function defaultWrap(): string
    {
        return 'data';
    }
}
