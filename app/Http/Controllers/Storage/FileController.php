<?php

namespace App\Http\Controllers\Storage;

use App\Data\BreadcrumbData;
use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use App\Http\Resources\StorageConfigResource;
use App\Jobs\IndexFileJob;
use App\Models\File;
use App\Models\Folder;
use App\Models\StorageConfig;
use App\Services\Session\Toast\Toast;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class FileController extends Controller
{
    use AuthorizesRequests;

    public function show(StorageConfig $storageConfig, File $file): Response
    {
        $this->authorize('view', $file);

        $folderBreadcrumbs = collect([...$file->folder->all_parents, $file->folder])
            ->map(function (Folder $folder) use ($storageConfig) {
                return [
                    'name' => $folder->name,
                    'url' => $folder->parent_id
                        ? route('storage.folders.show', ['folder' => Str::chopStart($folder->getRouteKey(), '/'), 'storageConfig' => $storageConfig])
                        : route('storage.folders.index', ['storageConfig' => $storageConfig]),
                ];
            });

        return Inertia::render('File', [
            'storageConfig' => new StorageConfigResource($storageConfig),
            'file' => new FileResource($file->loadMissing(['iptcItems', 'iptcItems.tagDefinition']))->withMetaData(),
            'breadcrumbs' => BreadcrumbData::collect($folderBreadcrumbs->add([
                'name' => $file->name,
                'url' => route('storage.files.show', ['file' => $file, 'storageConfig' => $storageConfig]),
            ])),
        ]);
    }

    public function download(StorageConfig $storageConfig, File $file): File
    {
        $this->authorize('view', $file);

        return $file;
    }

    public function reIndex(StorageConfig $storageConfig, File $file): RedirectResponse
    {
        $this->authorize('view', File::class);

        Bus::dispatch(new IndexFileJob($file));

        Toast::success('File re-indexed successfully.')->flash();

        return redirect()->route('storage.files.show', ['file' => $file, 'storageConfig' => $storageConfig]);
    }

    public function destroy(StorageConfig $storageConfig, File $file): RedirectResponse
    {
        $this->authorize('delete', $file);

        $file->delete();

        Toast::success('File deleted successfully.')->flash();

        return redirect()->route('storage.folders.show', ['path' => $file->folder, 'storageConfig' => $storageConfig]);
    }
}
