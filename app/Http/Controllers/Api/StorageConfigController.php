<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorageConfigRequest;
use App\Http\Resources\StorageConfigResource;
use App\Models\StorageConfig;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class StorageConfigController extends Controller
{
    use AuthorizesRequests;

    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', StorageConfig::class);

        return StorageConfigResource::collection(Auth::user()->storageConfigs);
    }

    public function show(StorageConfig $storageConfig): StorageConfigResource
    {
        $this->authorize('view', $storageConfig);

        return new StorageConfigResource($storageConfig);
    }
}
