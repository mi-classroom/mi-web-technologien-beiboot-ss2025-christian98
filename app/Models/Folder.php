<?php

namespace App\Models;

use App\Events\Folder\FolderCreatedEvent;
use App\Events\Folder\FolderDeletedEvent;
use App\Events\Folder\FolderUpdatedEvent;
use App\Services\FullPathGenerator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'storage_config_id',
    ];

    protected $dispatchesEvents = [
        'created' => FolderCreatedEvent::class,
        'updated' => FolderUpdatedEvent::class,
        'deleted' => FolderDeletedEvent::class,
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::creating(function (Folder $folder) {
            $folder->full_path = app(FullPathGenerator::class)->getFullPath($folder->parent, $folder->name);
        });

        static::updated(function (self $folder) {
            if ($folder->wasChanged('name', 'parent_id')) {

                DB::transaction(function () use ($folder) {
                    // Update the full path for the folder
                    $folder->full_path = app(FullPathGenerator::class)->getFullPath($folder->parent, $folder->name);

                    // Update the full path for all subfolders
                    $folder->folders()->each(function (Folder $subFolder) use ($folder) {
                        $subFolder->full_path = app(FullPathGenerator::class)->getFullPath($folder, $subFolder->name);
                        $subFolder->save();
                    });

                    // Update the full path for all files in this folder
                    $folder->files()->each(function (File $file) use ($folder) {
                        $file->full_path = app(FullPathGenerator::class)->getFullPath($folder, $file->name);
                        $file->save();
                    });
                });
            }
        });
    }

    /**
     * @return BelongsTo<StorageConfig, $this>
     */
    public function storageConfig(): BelongsTo
    {
        return $this->belongsTo(StorageConfig::class);
    }

    /**
     * @return BelongsTo<Folder, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    /**
     * @return HasMany<Folder, $this>
     */
    public function folders(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    /**
     * @return HasMany<File, $this>
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    /**
     * @return Attribute<Collection<int, static>, never>
     */
    protected function allParents(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->parent) {
                /** @var Collection<int, static> */
                return collect([$this]);
            }

            /** @var Collection<int, static> $chain */
            $chain = $this->parent->all_parents;

            return $chain->prepend($this);
        });
    }
}
