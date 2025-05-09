<?php

namespace App\Http\Controllers\Local;

use App\Data\BreadcrumbData;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateFileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use App\Models\Folder;
use App\Services\Image\Image;
use App\Services\Image\IPTC\IptcTag;
use Illuminate\Http\RedirectResponse;
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
            'file' => new FileResource($file)->withMetaData(),
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

    public function update(UpdateFileRequest $request, File $file): RedirectResponse
    {
        $file->update($request->except('meta_data'));
        $image = Image::fromDisk($file->path, 'public');

        if ($image->type()->supportsIptc()) {
            $iptc = $image->iptc();
            /** @var array<int, array{tag: string, value: array<int, string>}> $updatedIptc */
            $updatedIptc = $request->validated('meta_data.iptc');

            foreach ($updatedIptc as $updatedIptcItem) {
                $iptcTag = IptcTag::fromString($updatedIptcItem['tag']);

                if (empty($updatedIptcItem)) {
                    $iptc->remove($iptcTag);
                } else {
                    $iptc->set($iptcTag, $updatedIptcItem['value']);
                }
            }

            // Save the IPTC data back to the image
            $image->writeIptc($iptc);
            Storage::disk('public')->put($file->path, $image->contents());
        }

        $file->touch();

        return redirect()->route('local.files.show', $file)
            ->with('success', 'File updated successfully.');
    }

    public function destroy(File $file): RedirectResponse
    {
        $file->delete();

        return redirect()->route('local.folders.show', $file->folder)
            ->with('success', 'File deleted successfully.');
    }
}
