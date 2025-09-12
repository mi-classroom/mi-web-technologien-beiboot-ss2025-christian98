<?php

namespace App\Models;

use App\Events\IptcItem\IptcItemCreatedEvent;
use App\Events\IptcItem\IptcItemDeletedEvent;
use App\Events\IptcItem\IptcItemUpdatedEvent;
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

    protected $dispatchesEvents = [
        'created' => IptcItemCreatedEvent::class,
        'updated' => IptcItemUpdatedEvent::class,
        'deleted' => IptcItemDeletedEvent::class,
    ];

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
