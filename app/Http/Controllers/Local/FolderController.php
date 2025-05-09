<?php

namespace App\Http\Controllers\Local;

use App\Http\Controllers\Controller;
use App\Http\Resources\FolderResource;
use App\Models\Folder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class FolderController extends Controller
{
    public function index(): Response
    {
        $user = Auth::user();
        $folder = $user->folders()->with(['files', 'folders', 'parent'])->first();

        return Inertia::render('Folder', [
            'folder' => new FolderResource($folder),
            'breadcrumbs' => FolderResource::collection([$folder]),
        ]);
    }

    public function show(Folder $folder): Response
    {
        $folder->loadMissing('files', 'folders');

        return Inertia::render('Folder', [
            'folder' => new FolderResource($folder),
            'breadcrumbs' => FolderResource::collection([...$folder->all_parents, $folder]),
        ]);
    }

    public function store(): RedirectResponse
    {
        $user = Auth::user();
        $folder = $user->folders()->create([
            'name' => 'New Folder',
            'path' => '',
        ]);

        return redirect()->route('local.folders.show', $folder);
    }

    public function destroy(Folder $folder): RedirectResponse
    {
        $folder->delete();

        return redirect()->route('local.folders.index')
            ->with('success', 'Folder has been deleted.');
    }
}
