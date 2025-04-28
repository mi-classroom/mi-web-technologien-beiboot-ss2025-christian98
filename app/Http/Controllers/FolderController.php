<?php

namespace App\Http\Controllers;

use App\Http\Resources\FolderResource;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class FolderController extends Controller
{
    public function index(): Response
    {
        $user = Auth::user();
        $folder = $user->folders()->with(['files', 'folders'])->first();

        return Inertia::render('Folder', [
            'folder' => new FolderResource($folder),
            'breadcrumbs' => [$folder],
        ]);
    }

    public function show(Folder $folder): Response
    {
        $folder->loadMissing('files', 'folders');

        return Inertia::render('Folder', [
            'folder' => new FolderResource($folder),
            'breadcrumbs' => [...$folder->all_parents, $folder],
        ]);
    }

    public function store(): Response
    {
        $user = Auth::user();
        $folder = $user->folders()->create([
            'name' => 'New Folder',
            'path' => '',
        ]);

        return redirect()->route('folders.show', $folder);
    }
}
