<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFileRequest;
use App\Models\Folder;
use App\Models\StorageConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;

class FolderFileController extends Controller
{
    public function store(CreateFileRequest $request, StorageConfig $storageConfig, Folder $folder): RedirectResponse
    {
        $files = $request->validated('files');

        $folder->files()->createMany(
            collect($files)->map(function (array $file) {
                /** @var ?UploadedFile $uploadedFile */
                $uploadedFile = $file['file'];

                return [
                    'name' => $file['name'] ?? $uploadedFile->getClientOriginalName(),
                    'path' => $uploadedFile->storeAS('files', $uploadedFile->getClientOriginalName(), 'public'),
                    'size' => $uploadedFile->getSize(),
                    'type' => $uploadedFile->getMimeType(),
                ];
            })
        );

        $folder->touch();

        return redirect()->route('storage.folders.show', ['folder' => $folder, 'storageConfig' => $storageConfig])
            ->with('success', 'File uploaded successfully.');
    }
}
