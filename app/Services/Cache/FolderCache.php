<?php

namespace App\Services\Cache;

use App\Models\Folder;
use Illuminate\Cache\Repository;

class FolderCache
{
    public function __construct(readonly Repository $cache) {}

    public function populateFolderIdCache(Folder $folder): string
    {
        $cacheKey = sprintf('folder.path.%s.id', $folder->path);

        return $this->cache->rememberForever($cacheKey, function () use ($folder) {
            return $folder->id;
        });
    }

    public function getFolderId(string $path): ?string
    {
        $cacheKey = sprintf('folder.path.%s.id', $path);

        return $this->cache->get($cacheKey);
    }

    public function clearCache(Folder $folder): void
    {
        $this->cache->forget(sprintf('folder.path.%s.id', $folder->path));
    }
}
