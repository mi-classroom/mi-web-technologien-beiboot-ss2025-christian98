<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Http\Resources\FolderResource;
use App\Http\Resources\StorageConfigResource;
use App\Jobs\IndexFolderJob;
use App\Models\Folder;
use App\Models\StorageConfig;
use App\Services\Session\Toast\Toast;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Bus;
use Inertia\Inertia;
use Inertia\Response;

class FolderController extends Controller
{
    use AuthorizesRequests;

    public function index(StorageConfig $storageConfig): Response
    {
        $this->authorize('viewAny', Folder::class);

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
        $this->authorize('view', $folder);

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
        $this->authorize('delete', $folder);

        if ($folder->parent) {
            Toast::success('Folder has been deleted.')->flash();
            $folder->delete();

            return redirect()->route('storage.folders.show', ['folder' => $folder->parent, 'storageConfig' => $storageConfig]);
        }

        Toast::error('The root folder cannot be deleted.')->flash();

        return redirect()->route('storage.folders.show', ['folder' => $folder, 'storageConfig' => $storageConfig]);
    }
}
