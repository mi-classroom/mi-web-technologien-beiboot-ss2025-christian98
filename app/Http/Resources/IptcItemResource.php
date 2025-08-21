<?php

namespace App\Http\Resources;

use App\Models\IptcItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin IptcItem */
class IptcItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tag' => new IptcTagDefinitionResource($this->whenLoaded('tagDefinition')),
            'value' => $this->value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'file_id' => $this->file_id,
            'file' => new FileResource($this->whenLoaded('file')),
        ];
    }
}
