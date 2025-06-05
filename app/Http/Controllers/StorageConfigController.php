<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorageConfigRequest;
use App\Http\Resources\StorageConfigResource;
use App\Models\StorageConfig;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class StorageConfigController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', StorageConfig::class);

        return StorageConfigResource::collection(StorageConfig::all());
    }

    public function store(StorageConfigRequest $request)
    {
        $this->authorize('create', StorageConfig::class);

        return new StorageConfigResource(StorageConfig::create($request->validated()));
    }

    public function show(StorageConfig $storageConfig)
    {
        $this->authorize('view', $storageConfig);

        return new StorageConfigResource($storageConfig);
    }

    public function update(StorageConfigRequest $request, StorageConfig $storageConfig)
    {
        $this->authorize('update', $storageConfig);

        $storageConfig->update($request->validated());

        return new StorageConfigResource($storageConfig);
    }

    public function destroy(StorageConfig $storageConfig)
    {
        $this->authorize('delete', $storageConfig);

        $storageConfig->delete();

        return response()->json();
    }
}
