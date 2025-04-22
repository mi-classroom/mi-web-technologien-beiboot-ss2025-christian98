<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'size',
        'type',
        'folder_id',
    ];

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    public function sizeForHumans(): Attribute
    {
        return Attribute::get(function () {
            $bytes = $this->size;
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];

            for ($i = 0; $bytes > 1024; $i++) {
                $bytes /= 1024;
            }

            return round($bytes, 2) . ' ' . $units[$i];
        })->shouldCache();
    }

    public function downloadUrl(): Attribute
    {
        return Attribute::get(fn() => url('storage/' . $this->path))->shouldCache();
    }
}
