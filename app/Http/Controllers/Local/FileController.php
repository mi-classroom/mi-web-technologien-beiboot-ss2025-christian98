<?php

namespace App\Http\Controllers\Local;

use App\Data\BreadcrumbData;
use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use App\Jobs\IndexFileJob;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function show(File $file): Response
    {
        $folderBreadcrumbs = collect([...$file->folder->all_parents, $file->folder])
            ->map(function (Folder $folder) {
                return [
                    'name' => $folder->name,
                    'url' => $folder->parent_id
                        ? route('local.folders.show', ['folder' => Str::chopStart($folder->getRouteKey(), '/')])
                        : route('local.folders.index'),
                ];
            });

        return Inertia::render('File', [
            'file' => new FileResource($file->loadMissing('iptcItems'))->withMetaData(),
            'breadcrumbs' => BreadcrumbData::collect($folderBreadcrumbs->add([
                'name' => $file->name,
                'url' => route('local.files.show', ['file' => $file]),
            ])),
        ]);
    }

    public function download(File $file): StreamedResponse
    {
        return Storage::disk('public')->download($file->path, $file->name);
    }

    public function reIndex(File $file): RedirectResponse
    {
        Bus::dispatch(new IndexFileJob($file));

        return redirect()->route('local.files.show', $file)
            ->with('success', 'File re-indexed successfully.');
    }

    public function destroy(File $file): RedirectResponse
    {
        $file->delete();

        return redirect()->route('local.folders.show', $file->folder)
            ->with('success', 'File deleted successfully.');
    }
}
