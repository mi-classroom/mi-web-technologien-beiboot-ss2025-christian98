<?php

namespace App\Http\Resources;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Folder */
class FolderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'path' => $this->path,
            'parent_id' => $this->parent_id,
            'folders' => self::collection($this->whenLoaded('folders')),
            'files' => FileResource::collection($this->whenLoaded('files')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
