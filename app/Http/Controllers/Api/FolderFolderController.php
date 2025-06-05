<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFolderRequest;
use App\Http\Resources\FolderResource;
use App\Models\Folder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FolderFolderController extends Controller
{
    public function index(Folder $folder): AnonymousResourceCollection
    {
        return FolderResource::collection($folder->folders);
    }

    public function store(CreateFolderRequest $request, Folder $folder): FolderResource
    {
        return new FolderResource(
            $folder->folders()->create($request->validated())
        );
    }
}
