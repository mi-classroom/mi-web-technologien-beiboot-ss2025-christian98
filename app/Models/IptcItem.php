<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IptcItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'iptc_tag_definition_id',
        'value',
        'file_id',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::created(function (self $item) {
            // TODO WriteIptcMetadataJob::dispatch($item->file);
        });

        static::updated(function (self $item) {
            // TODO WriteIptcMetadataJob::dispatch($item->file);
        });

        static::deleted(function (self $item) {
            // TODO WriteIptcMetadataJob::dispatch($item->file);
        });
    }

    /**
     * @return BelongsTo<File, self>
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    /**
     * @return BelongsTo<IptcTagDefinition, self>
     */
    public function tagDefinition(): BelongsTo
    {
        return $this->belongsTo(IptcTagDefinition::class, 'iptc_tag_definition_id');
    }
}
