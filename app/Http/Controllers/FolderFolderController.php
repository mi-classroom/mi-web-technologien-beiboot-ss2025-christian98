<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFolderRequest;
use App\Models\Folder;
use Illuminate\Http\RedirectResponse;

class FolderFolderController extends Controller
{
    public function store(CreateFolderRequest $request, Folder $folder): RedirectResponse
    {
        $newFolder = $folder->folders()->create($request->validated());
        ray($newFolder);

        return redirect()->route('folders.show', $newFolder);
    }
}
