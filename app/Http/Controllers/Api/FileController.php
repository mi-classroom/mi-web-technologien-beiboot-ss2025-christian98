<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFileRequest;
use App\Http\Resources\FileResource;
use App\Http\Resources\FileResourceCollection;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileController extends Controller
{
    use AuthorizesRequests;

    // region nested resources
    public function index(Folder $folder): FileResourceCollection
    {
        $this->authorize('view', $folder);

        return new FileResourceCollection($folder->files);
    }

    public function store(CreateFileRequest $request, Folder $folder): FileResource
    {
        $this->authorize('create', [File::class, $folder]);

        // Create a new file in the specified folder
        $file = $folder->files()->create($request->validated());

        return new FileResource($file);
    }
    // endregion

    // region single resource
    public function indexRoot(Request $request): FileResourceCollection
    {
        $ids = $request->query('ids', []);

        $files = File::query()->whereIn('id', $ids)->with(['iptcItems', 'iptcItems.tagDefinition'])->get();

        foreach ($files as $file) {
            $this->authorize('view', $file);
        }

        return new FileResourceCollection($files);
    }

    public function show(File $file): FileResource
    {
        $this->authorize('view', $file);

        return new FileResource($file)->withMetaData();
    }

    public function update(CreateFileRequest $request, File $file): FileResource
    {
        $this->authorize('update', $file);

        $file->update($request->validated());

        return new FileResource($file);
    }

    public function destroy(File $file): JsonResponse
    {
        $this->authorize('delete', $file);

        $file->delete();

        return response()->json(['message' => 'File deleted successfully.']);
    }
    // endregion

    public function preview(File $file): File
    {
        $this->authorize('view', $file);

        return $file;
    }
}
