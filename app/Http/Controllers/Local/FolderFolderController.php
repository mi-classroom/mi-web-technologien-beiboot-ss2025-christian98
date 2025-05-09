<?php

namespace App\Http\Controllers\Local;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFolderRequest;
use App\Models\Folder;
use Illuminate\Http\RedirectResponse;

class FolderFolderController extends Controller
{
    public function store(CreateFolderRequest $request, Folder $folder): RedirectResponse
    {
        $newFolder = $folder->folders()->create($request->validated());

        $folder->touch();

        return redirect()->route('local.folders.show', $newFolder);
    }
}
