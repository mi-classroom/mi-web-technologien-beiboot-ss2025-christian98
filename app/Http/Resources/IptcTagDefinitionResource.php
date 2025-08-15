<?php

namespace App\Http\Resources;

use App\Models\IptcTagDefinition;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin IptcTagDefinition */
class IptcTagDefinitionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'tag' => $this->tag,
            'description' => $this->description,
            'spec' => $this->spec,
            'is_value_editable' => $this->is_value_editable,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'user_id' => $this->user_id,

            'user' => new UserResource($this->whenLoaded('user')),
            'iptcItems' => IptcItemResource::collection($this->whenLoaded('iptcItems')),
        ];
    }
}
