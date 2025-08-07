<?php

namespace App\Models;

use App\Services\FullPathGenerator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use function Illuminate\Events\queueable;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'storage_config_id',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::creating(function (Folder $folder) {
            $folder->full_path = app(FullPathGenerator::class)->getFullPath($folder->parent, $folder->name);
        });

        static::created(queueable(function (self $folder) {
            // Create the folder in the storage
            $folder->storageConfig->getStorage()->makeDirectory($folder->full_path);
        }));

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

        static::deleted(queueable(function (self $folder) {
            // Delete the folder from the storage
            $folder->storageConfig->getStorage()->deleteDirectory($folder->full_path);
        }));
    }

    /**
     * @return BelongsTo<StorageConfig, self>
     */
    public function storageConfig(): BelongsTo
    {
        return $this->belongsTo(StorageConfig::class);
    }

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    /**
     * @return HasMany<Folder, self>
     */
    public function folders(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    /**
     * @return HasMany<File, self>
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function allParents(): Attribute
    {
        return Attribute::get(function () {
            $parents = collect([]);
            $currentFolder = $this->parent;

            while ($currentFolder) {
                $parents->push($currentFolder);
                $currentFolder = $currentFolder->parent;
            }

            return $parents->reverse();
        });
    }
}
