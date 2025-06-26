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
            'name' => $this->name,
            'provider_type' => $this->provider_type,
            'provider_options' => $this->provider_options,
            'is_editable' => $this->is_editable,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'root_folder_id' => $this->root_folder_id,
            'root_folder' => new FolderResource($this->whenLoaded('rootFolder')),
        ];
    }
}
