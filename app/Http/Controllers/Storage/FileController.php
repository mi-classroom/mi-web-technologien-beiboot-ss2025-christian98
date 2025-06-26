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
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function show(StorageConfig $storageConfig, File $file): Response
    {
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
            'file' => new FileResource($file->loadMissing('iptcItems'))->withMetaData(),
            'breadcrumbs' => BreadcrumbData::collect($folderBreadcrumbs->add([
                'name' => $file->name,
                'url' => route('storage.files.show', ['file' => $file, 'storageConfig' => $storageConfig]),
            ])),
        ]);
    }

    public function download(StorageConfig $storageConfig, File $file): StreamedResponse
    {
        return $storageConfig->getStorage()->download($file->full_path, $file->name);
    }

    public function reIndex(StorageConfig $storageConfig, File $file): RedirectResponse
    {
        Bus::dispatch(new IndexFileJob($file));

        return redirect()->route('storage.files.show', ['file' => $file, 'storageConfig' => $storageConfig])
            ->with('success', 'File re-indexed successfully.');
    }

    public function destroy(StorageConfig $storageConfig, File $file): RedirectResponse
    {
        $file->delete();

        return redirect()->route('storage.folders.show', ['path' => $file->folder, 'storageConfig' => $storageConfig])
            ->with('success', 'File deleted successfully.');
    }
}
