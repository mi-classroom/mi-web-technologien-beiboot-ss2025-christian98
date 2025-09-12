<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

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

    public static function findByTag(string $tag, ?User $user = null): ?IptcTagDefinition
    {
        $userId = $user?->id ?? Auth::id();

        return self::where('tag', $tag)
            ->where(function (Builder $query) use ($userId) {
                $query->where('user_id', $userId)->orWhereNull('user_id');
            })
            ->first();
    }

    public static function findOrCreateByTag(string $tag, ?User $user = null): IptcTagDefinition
    {
        $userId = $user?->id ?? Auth::id();

        return self::findByTag($tag, $user)
            ?? self::updateOrCreate([
                'tag' => $tag,
                'user_id' => $userId,
            ], [
                'name' => "Unknown Tag - $tag",
                'description' => null,
                'spec' => [
                    'data_type' => 'string',
                    'min_length' => 0,
                    'max_length' => 255,
                    'multiple' => true,
                    'required' => false,
                    'enum_values' => null,
                ],
                'is_value_editable' => true,
            ]);
    }
}
