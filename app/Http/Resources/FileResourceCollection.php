<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\File */
class FileResourceCollection extends ResourceCollection
{
    protected bool $withMetaData = false;

    public function toArray(Request $request): array
    {
        return $this->collection->map(fn ($file) => new FileResource($file)->withMetaData($this->withMetaData))->toArray();
    }

    public function withMetaData(bool $withMetaData = true): FileResourceCollection
    {
        $this->withMetaData = $withMetaData;

        return $this;
    }
}
