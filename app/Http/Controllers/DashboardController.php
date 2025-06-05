<?php

namespace App\Http\Controllers;

use App\Http\Resources\FolderResource;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function show(): Response
    {
        $rootFolder = auth()->user()->folders()
            ->whereNull('parent_id')
            ->first();
        $folder = $rootFolder->folders()
            ->where('name', 'Drop Zone')
            ->with(['files', 'folders', 'parent'])
            ->first();
        $folder->loadMissing('files', 'folders');

        return Inertia::render('Dashboard', [
            'folder' => new FolderResource($folder)->withMetaData()->ray(),
        ]);
    }
}
