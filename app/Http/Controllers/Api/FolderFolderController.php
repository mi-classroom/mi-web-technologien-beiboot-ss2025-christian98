<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFolderRequest;
use App\Http\Resources\FolderResource;
use App\Models\Folder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FolderFolderController extends Controller
{
    use AuthorizesRequests;

    public function index(Folder $folder): AnonymousResourceCollection
    {
        $this->authorize('view', $folder);

        return FolderResource::collection($folder->folders);
    }

    public function store(CreateFolderRequest $request, Folder $folder): FolderResource
    {
        $this->authorize('create', [Folder::class, $folder]);

        $newFolder = $folder->folders()->create($request->validated());

        $folder->touch();

        return new FolderResource($newFolder);
    }
}
