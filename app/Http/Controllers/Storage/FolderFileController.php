<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFileRequest;
use App\Models\File;
use App\Models\Folder;
use App\Models\StorageConfig;
use App\Services\Session\Toast\LinkAction;
use App\Services\Session\Toast\Toast;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;

class FolderFileController extends Controller
{
    use AuthorizesRequests;

    public function store(CreateFileRequest $request, StorageConfig $storageConfig, Folder $folder): RedirectResponse
    {
        $this->authorize('create', [File::class, $folder]);

        $files = $request->validated('files');

        $dbFiles = $folder->files()->createMany(
            collect($files)->map(function (array $file) use ($folder) {
                /** @var ?UploadedFile $uploadedFile */
                $uploadedFile = $file['file'];

                $folder->storageConfig->getStorage()
                    ->upload(
                        $folder->full_path.'/'.$uploadedFile->getClientOriginalName(),
                        $uploadedFile->get()
                    );

                return [
                    'name' => $file['name'] ?? $uploadedFile->getClientOriginalName(),
                    'size' => $uploadedFile->getSize(),
                    'type' => $uploadedFile->getMimeType(),
                    'storage_config_id' => $folder->storage_config_id,
                ];
            })
        );

        $folder->touch();

        if (count($dbFiles) === 1) {
            Toast::success('File uploaded successfully.')
                ->action(LinkAction::make(
                    'Got to file',
                    route('storage.files.show', [
                        'file' => $dbFiles[0],
                        'storageConfig' => $storageConfig,
                    ])
                ))
                ->flash();
        } else {
            Toast::success(count($dbFiles).' files uploaded successfully.')->flash();
        }

        return redirect()->route(
            'storage.folders.show',
            ['folder' => $folder, 'storageConfig' => $storageConfig]
        );
    }
}
