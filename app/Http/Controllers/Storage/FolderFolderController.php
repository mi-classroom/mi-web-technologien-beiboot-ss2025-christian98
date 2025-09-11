<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFolderRequest;
use App\Models\Folder;
use App\Models\StorageConfig;
use App\Services\Session\Toast\LinkAction;
use App\Services\Session\Toast\Toast;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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

        Toast::success('Folder has been created.')
            ->action(LinkAction::make('Go to folder', route('storage.folders.show', ['folder' => $newFolder, 'storageConfig' => $storageConfig])))
            ->flash();

        return redirect()->back();
    }
}
