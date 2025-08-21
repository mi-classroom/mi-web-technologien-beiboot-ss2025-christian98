<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Http\Resources\FolderResource;
use App\Http\Resources\StorageConfigResource;
use App\Jobs\IndexFolderJob;
use App\Models\Folder;
use App\Models\StorageConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Bus;
use Inertia\Inertia;
use Inertia\Response;

class FolderController extends Controller
{
    public function index(StorageConfig $storageConfig): Response
    {
        $folder = $storageConfig->rootFolder()->with(['files', 'folders'])->firstOrFail();

        Bus::dispatch(new IndexFolderJob($folder));

        return Inertia::render('Folder', [
            'storageConfig' => new StorageConfigResource($storageConfig),
            'folder' => new FolderResource($folder),
            'breadcrumbs' => FolderResource::collection([$folder]),
        ]);
    }

    public function show(StorageConfig $storageConfig, Folder $folder): Response
    {
        $folder->loadMissing('files', 'folders');

        Bus::dispatch(new IndexFolderJob($folder));

        return Inertia::render('Folder', [
            'storageConfig' => new StorageConfigResource($storageConfig),
            'folder' => new FolderResource($folder),
            'breadcrumbs' => FolderResource::collection([...$folder->all_parents, $folder]),
        ]);
    }

    public function destroy(StorageConfig $storageConfig, Folder $folder): RedirectResponse
    {
        $folder->delete();

        return redirect()->route('storage.folders.index', ['storageConfig' => $storageConfig])
            ->with('success', 'Folder has been deleted.');
    }
}
