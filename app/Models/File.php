<?php

namespace App\Models;

use App\Services\FullPathGenerator;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Symfony\Component\HttpFoundation\StreamedResponse;

class File extends Model implements Responsable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'full_path',
        'size',
        'type',
        'folder_id',
        'storage_config_id',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::creating(function (self $file) {
            $file->full_path = app(FullPathGenerator::class)->getFullPath($file->folder, $file->name);
            $file->storage_config_id ??= $file->folder->storage_config_id;
        });

        static::updating(function (self $file) {
            if ($file->isDirty('folder_id')) {
                $file->storage_config_id = $file->folder->storage_config_id;
            }
        });
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    public function storageConfig(): BelongsTo
    {
        return $this->belongsTo(StorageConfig::class);
    }

    public function iptcItems(): HasMany
    {
        return $this->hasMany(IptcItem::class);
    }

    public function sizeForHumans(): Attribute
    {
        return Attribute::get(function () {
            $bytes = $this->size;
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];

            for ($i = 0; $bytes > 1024; $i++) {
                $bytes /= 1024;
            }

            return round($bytes, 2).' '.$units[$i];
        })->shouldCache();
    }

    public function downloadUrl(): Attribute
    {
        return Attribute::get(fn () => url('storage/'.$this->path))->shouldCache();
    }

    public function toResponse($request): ?StreamedResponse
    {
        return $this->storageConfig->getStorage()->download($this->full_path, $this->name);
    }
}
