<?php

namespace App\Http\Controllers\Local;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFileRequest;
use App\Models\Folder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;

class FolderFileController extends Controller
{
    public function store(CreateFileRequest $request, Folder $folder): RedirectResponse
    {
        $files = $request->validated('files');

        $folder->files()->createMany(
            collect($files)->map(function (array $file) {
                /** @var ?UploadedFile $uploadedFile */
                $uploadedFile = $file['file'];

                return [
                    'name' => $file['name'] ?? $uploadedFile->getClientOriginalName(),
                    'path' => $uploadedFile->store('files', 'public'),
                    'size' => $uploadedFile->getSize(),
                    'type' => $uploadedFile->getMimeType(),
                ];
            })
        );

        $folder->touch();

        return redirect()->route('local.folders.show', $folder)
            ->with('success', 'File uploaded successfully.');
    }
}
