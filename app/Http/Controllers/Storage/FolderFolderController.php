<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFolderRequest;
use App\Models\Folder;
use App\Models\StorageConfig;
use Illuminate\Http\RedirectResponse;

class FolderFolderController extends Controller
{
    public function store(CreateFolderRequest $request, StorageConfig $storageConfig, Folder $folder): RedirectResponse
    {
        $newFolder = $folder->folders()->create([
            ...$request->validated(),
            'storage_config_id' => $storageConfig->id,
        ]);

        $folder->touch();

        return redirect()->route('storage.folders.show', ['folder' => $newFolder, 'storageConfig' => $storageConfig])
            ->with('success', 'Folder created successfully.');
    }
}
