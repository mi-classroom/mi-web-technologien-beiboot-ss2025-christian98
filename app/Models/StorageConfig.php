<?php

namespace App\Models;

use App\Services\Storage\StorageBackend;
use App\Services\Storage\StorageProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageConfig extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'provider_type' => StorageProvider::class,
            'options' => 'array',
            'is_editable' => 'boolean',
        ];
    }

    public function getStorage(): StorageBackend
    {
        return $this->provider_type->getBackend(['options' => $this->options]);
    }
}
