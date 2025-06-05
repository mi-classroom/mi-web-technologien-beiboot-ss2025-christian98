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
        $rootFolder = auth()->user()->folders()
            ->whereNull('parent_id')
            ->first();

        return Inertia::render('Dashboard', [
            'recentlyEditedFiles' => Inertia::defer(function () use ($rootFolder) {
                $files = $this->files($rootFolder)->sortByDesc('updated_at')->take(10);

                return new FileResourceCollection($files);
            }, 'recentlyEditedFiles'),
            'recentlyAddedFiles' => Inertia::defer(function () use ($rootFolder) {
                $files = $this->files($rootFolder)->sortByDesc('created_at')->take(10);

                return new FileResourceCollection($files);
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
