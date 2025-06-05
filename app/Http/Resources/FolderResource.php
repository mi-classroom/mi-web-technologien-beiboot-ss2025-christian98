<?php

namespace App\Http\Resources;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Folder */
class FolderResource extends JsonResource
{
    protected bool $withMetaData = false;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'path' => $this->path,
            'parent_id' => $this->parent_id,
            'parent' => new FolderResource($this->whenLoaded('parent')),
            'folders' => self::collection($this->whenLoaded('folders')),
            'files' => new FileResourceCollection($this->whenLoaded('files'))->withMetaData($this->withMetaData),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function withMetaData(bool $withMetaData = true): FolderResource
    {
        $this->withMetaData = $withMetaData;

        return $this;
    }

    public function ray()
    {
        ray(json_decode($this->toJson()));

        return $this;
    }
}
