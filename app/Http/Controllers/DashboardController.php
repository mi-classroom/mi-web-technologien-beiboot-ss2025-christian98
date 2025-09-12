<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResourceCollection;
use App\Models\File;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function show(): Response
    {
        $storageConfigs = auth()->user()->storageConfigs;
        $baseQuery = File::query()
            ->whereIn('files.storage_config_id', $storageConfigs->pluck('id'))
            ->limit(10);

        return Inertia::render('Dashboard', [
            'recentlyEditedFiles' => Inertia::defer(function () use ($baseQuery) {
                return new FileResourceCollection(
                    $baseQuery->clone()->orderByDesc('updated_at')->get()
                );
            }, 'recentlyEditedFiles'),
            'recentlyAddedFiles' => Inertia::defer(function () use ($baseQuery) {
                return new FileResourceCollection(
                    $baseQuery->clone()->orderByDesc('created_at')->get()
                );
            }, 'recentlyAddedFiles'),
        ]);
    }
}
