<?php

namespace App\Http\Resources;

use App\Models\StorageConfig;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin StorageConfig */
class StorageConfigResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'provider_type' => $this->provider_type,
            'options' => $this->options,
            'is_editable' => $this->is_editable,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
