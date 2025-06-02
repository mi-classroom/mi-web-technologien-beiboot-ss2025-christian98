<?php

namespace App\Models;

use App\Jobs\UpdatePathCache;
use App\Services\Cache\FolderCache;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::created(function (self $folder) {
            $folder->loadMissing('folders');
            app(FolderCache::class)->populateFolderIdCache($folder);
        });

        static::updated(function (self $folder) {
            if ($folder->wasChanged('name', 'parent_id')) {
                Bus::dispatch(new UpdatePathCache($folder));
            }
        });

        static::deleted(function (self $folder) {
            app(FolderCache::class)->clearCache($folder);
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    public function folders(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

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

    public function path(): Attribute
    {
        return Attribute::get(function () {
            return $this->all_parents->push($this)->pluck('name')->implode('/');
        })->shouldCache();
    }

    // region route model binding
    public function getRouteKey(): string
    {
        return $this->path;
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        // if the field is not null and not 'path', use the default behavior
        if (! is_null($field) && $field !== 'path') {
            return parent::resolveRouteBinding($value, $field);
        }

        // retrieve folder id from cache
        if ($folderId = app(FolderCache::class)->getFolderId(Str::start($value, '/'))) {
            return $this->newQuery()->findOrFail($folderId);
        }

        // if not found in cache, fallback to default behavior
        return parent::resolveRouteBinding($value, $field);
    }
    // endregion
}
