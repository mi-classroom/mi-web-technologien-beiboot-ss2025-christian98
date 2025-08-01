<?php

namespace App\Models;

use App\Services\Storage\StorageProvider;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StorageConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'provider_type',
        'provider_options',
        'root_folder_id',
        'is_editable',
        'status',
        'last_indexed_at',
    ];

    protected function casts(): array
    {
        return [
            'provider_type' => StorageProvider::class,
            'provider_options' => 'encrypted:array',
            'is_editable' => 'boolean',
            'last_indexed_at' => 'datetime',
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

    /**
     * @return HasMany<Folder, self>
     */
    public function allFolders(): HasMany
    {
        return $this->hasMany(Folder::class, 'storage_config_id');
    }

    /**
     * @return HasMany<File, self>
     */
    public function allFiles(): HasMany
    {
        return $this->hasMany(File::class, 'storage_config_id');
    }
}
