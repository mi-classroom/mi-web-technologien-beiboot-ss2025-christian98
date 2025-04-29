<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
    ];

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
}
