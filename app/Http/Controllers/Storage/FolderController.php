<?php

namespace App\Http\Controllers\Storage;

use App\Data\BreadcrumbData;
use App\Http\Controllers\Controller;
use App\Http\Resources\StorageConfigResource;
use App\Models\StorageConfig;

class FolderController extends Controller
{
    public function index(StorageConfig $storageConfig)
    {
        $storage = $storageConfig->getStorage();

        return inertia('Storage/Folder', [
            'folder' => $storage->getRoot(),
            'storageConfig' => new StorageConfigResource($storageConfig),
            'breadcrumbs' => BreadcrumbData::collect([
                [
                    'name' => '/',
                    'url' => route('storage.folders.index', [
                        'storageConfig' => $storageConfig,
                    ]),
                ],
            ]),
        ]);
    }

    public function show(StorageConfig $storageConfig, string $path)
    {
        ray($path);
        ray($this->getBreadCrumbs($path, $storageConfig));
        $storage = $storageConfig->getStorage();

        return inertia('Storage/Folder', [
            'folder' => $storage->getFolder($path),
            'storageConfig' => new StorageConfigResource($storageConfig),
            'breadcrumbs' => BreadcrumbData::collect($this->getBreadCrumbs($path, $storageConfig)),
        ]);
    }

    protected function getBreadCrumbs(string $path, StorageConfig $storageConfig): array
    {
        ray($path);
        if ($path === '.') {
            return [
                $this->createBreadcrumbEntry('/', $storageConfig),
            ];
        }

        return [...$this->getBreadCrumbs(dirname($path), $storageConfig),
            $this->createBreadcrumbEntry($path, $storageConfig),
        ];
    }

    private function createBreadcrumbEntry(string $path, StorageConfig $storageConfig): array
    {
        $isRoot = $path === '/';

        return ray()->pass([
            'name' => $isRoot ? '/' : basename($path),
            'url' => route(
                $isRoot ? 'storage.folders.index' : 'storage.folders.show',
                array_filter([
                    'storageConfig' => $storageConfig,
                    'folder' => $isRoot ? null : ($path),
                ])
            ),
        ]);
    }
}
