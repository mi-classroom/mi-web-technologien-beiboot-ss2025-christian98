<?php

namespace App\Models;

use App\Jobs\IndexFileJob;
use App\Services\FullPathGenerator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Bus;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'full_path',
        'size',
        'type',
        'folder_id',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::creating(function (self $file) {
            $file->full_path = app(FullPathGenerator::class)->getFullPath($file->folder, $file->name);
        });

        static::created(function (self $file) {
            Bus::dispatch(new IndexFileJob($file));
        });
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    public function iptcItems(): HasMany
    {
        return $this->hasMany(IptcItem::class)->orderBy('tag');
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
