<?php

namespace App\Http\Resources;

use App\Models\File;
use App\Services\Image\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin File */
class FileResource extends JsonResource
{
    protected bool $withMetaData = false;

    public function toArray(Request $request): array
    {
        $image = Image::fromDisk($this->path, 'public');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'path' => $this->path,
            'size' => $this->size,
            'size_for_humans' => $this->size_for_humans,
            'type' => $this->type,
            'meta_data' => $this->when($this->withMetaData, fn () => [
                'exif' => $image->type()->supportsExif() ? rescue(fn () => $image->exif()?->toArray()) : null,
                'iptc' => $image->type()->supportsIptc() ? rescue(fn () => $image->iptc()?->toArray()) : null,
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
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
