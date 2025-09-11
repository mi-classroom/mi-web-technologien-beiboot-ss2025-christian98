<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFolderRequest;
use App\Http\Resources\FolderResource;
use App\Models\Folder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class FolderController extends Controller
{
    use AuthorizesRequests;

    public function index(): FolderResource
    {
        $rootFolder = auth()->user()->rootFolder;
        $this->authorize('view', $rootFolder);

        return new FolderResource($rootFolder);
    }

    public function show(Folder $folder): FolderResource
    {
        $this->authorize('view', $folder);
        $folder->loadMissing('files', 'folders', 'parent');

        return new FolderResource($folder);
    }

    public function update(CreateFolderRequest $request, Folder $folder): FolderResource
    {
        $this->authorize('update', $folder);
        $folder->update($request->validated());

        return new FolderResource($folder);
    }

    public function destroy(Folder $folder): JsonResponse
    {
        $this->authorize('delete', $folder);
        $folder->delete();

        return response()->json(['message' => 'Folder deleted successfully.']);
    }
}
