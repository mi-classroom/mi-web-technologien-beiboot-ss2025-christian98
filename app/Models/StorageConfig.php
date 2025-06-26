<?php

namespace App\Models;

use App\Services\Storage\StorageBackend;
use App\Services\Storage\StorageProvider;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StorageConfig extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'provider_type' => StorageProvider::class,
            'provider_options' => 'array',
            'is_editable' => 'boolean',
        ];
    }

    public function getStorage(): Filesystem
    {
        return $this->provider_type->getBackend($this);
    }

    /**
     * @return BelongsTo<Folder, self>
     */
    public function rootFolder(): BelongsTo
    {
        return $this->belongsTo(Folder::class, 'root_folder_id');
    }
}
