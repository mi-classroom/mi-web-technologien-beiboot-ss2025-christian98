<?php

namespace App\Http\Resources;

use App\Models\File;
use App\Services\Image\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/** @mixin File */
class FileResource extends JsonResource
{
    protected bool $withMetaData = true;

    public function toArray(Request $request): array
    {
        // $image = Image::fromDisk($this->full_path, $this->folder->storageConfig->getStorage()); // TODO: import exif into database as well

        return [
            'id' => $this->id,
            'name' => $this->name,
            'full_path' => $this->full_path,
            'size' => $this->size,
            'size_for_humans' => $this->size_for_humans,
            'type' => $this->type,
            'meta_data' => $this->when($this->withMetaData, fn () => [
                // 'exif' => $image->type()->supportsExif() ? rescue(fn () => $image->exif()?->toArray()) : null,
                'iptc_items' => IptcItemResource::collection($this->whenLoaded('iptcItems')),
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'storage_config_id' => $this->storage_config_id,
            'storage_config' => StorageConfigResource::make($this->whenLoaded('storageConfig')),
        ];
    }

    public function withMetaData(bool $withMetaData = true): FileResource
    {
        $this->withMetaData = $withMetaData;

        return $this;
    }

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     */
    protected static function newCollection($resource): FileResourceCollection
    {
        return new FileResourceCollection($resource);
    }
}
