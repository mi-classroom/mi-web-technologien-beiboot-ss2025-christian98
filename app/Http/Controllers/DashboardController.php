<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResourceCollection;
use App\Models\Folder;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function show(): Response
    {
        $storageConfigs = auth()->user()->storageConfigs;
        $files = $storageConfigs->flatMap(fn ($config) => $config->allFiles);

        return Inertia::render('Dashboard', [
            'recentlyEditedFiles' => Inertia::defer(function () use ($files) {
                return new FileResourceCollection($files->sortByDesc('updated_at')->take(10));
            }, 'recentlyEditedFiles'),
            'recentlyAddedFiles' => Inertia::defer(function () use ($files) {
                return new FileResourceCollection($files->sortByDesc('created_at')->take(10));
            }, 'recentlyAddedFiles'),
        ]);
    }

    protected function files(Folder $folder)
    {
        $files = $folder->files;
        $folder->folders->each(function (Folder $subFolder) use ($files) {
            $files->push(...$this->files($subFolder));
        });

        return $files;
    }
}
