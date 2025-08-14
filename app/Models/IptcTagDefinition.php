<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IptcTagDefinition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tag',
        'description',
        'spec',
        'is_value_editable',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'spec' => 'array',
            'is_value_editable' => 'boolean',
        ];
    }
}
